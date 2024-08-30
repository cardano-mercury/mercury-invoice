<script setup>
import { ref } from 'vue';
import InvoiceLayout from '@/Layouts/InvoiceLayout.vue';
import { Buffer } from 'buffer'
import {
    LinearFee,
    BigNum,
    Value,
    Address,
    TransactionBuilder,
    TransactionBuilderConfigBuilder,
    CoinSelectionStrategyCIP2,
    TransactionMetadatum,
    AuxiliaryData,
    GeneralTransactionMetadata,
    TransactionWitnessSet,
    Transaction,
    TransactionOutputBuilder,
    TransactionUnspentOutputs,
    TransactionUnspentOutput,
} from '@emurgo/cardano-serialization-lib-asmjs';
import {useToast} from 'vue-toast-notification';

const paymentMethod = ref('');
const cryptoTxStatus = ref('');
const selectedWallet = ref(null);
const showPayInvoiceOnline = ref(true);

const props = defineProps({
    invoice: Object,
    billingAddress: Array,
    shippingAddress: Array,
    availablePaymentMethods: Array,
    stripePaymentCancelled: Boolean,
    stripePaymentCompleted: Boolean,
    targetCardanoNetwork: Object,
    adaInvoiceCurrencyValue: Number,
    cryptoPaymentAddress: String,
    cryptoPaymentDeadline: Number,
    cryptoProtocolParameters: Object,
});

const $toast = useToast();

const calculateSubTotal = () => {
    let result = 0.00;
    props.invoice.items.forEach((item) => {
        const lineSubTotal = parseFloat(item.quantity) * parseFloat(item.unit_price);
        if (!isNaN(lineSubTotal)) {
            result += lineSubTotal;
        }
    });
    return result;
};

const calculateTotalTax = () => {
    let result = 0.00;
    props.invoice.items.forEach((item) => {
        const lineSubTotal = parseFloat(item.quantity) * parseFloat(item.unit_price);
        const lineTaxRate = parseFloat(item.tax_rate);
        if (!isNaN(lineSubTotal) && !isNaN(lineTaxRate)) {
            result += (lineSubTotal * (lineTaxRate / 100));
        }
    });
    return result;
};

const calculateGrandTotal = () => {
    return (calculateSubTotal() + calculateTotalTax());
};

const payWithStripe = () => {
    window.location.href = route('public.invoice.pay-via-stripe', { encodedId: props.invoice.invoice_reference });
};

const availableWallets = [];
setTimeout(() => {
    const knownWallet = [];
    for (const [walletName, walletObject] of Object.entries(window.cardano)) {
        if (!['enable', 'isEnabled'].includes(walletName)) {
            let walletDisplayName = walletObject.name.replace('Wallet', '').trim();
            walletDisplayName = walletDisplayName.charAt(0).toUpperCase() + walletDisplayName.slice(1);
            if (!knownWallet.includes(walletDisplayName) && walletObject.icon) {
                availableWallets.push({
                    walletName,
                    walletDisplayName,
                    walletIcon: walletObject.icon,
                })
                knownWallet.push(walletDisplayName);
            }
        }
    }
}, 500);

const fromHex = (hex) => {
    return Buffer.from(hex, "hex");
};

const toHex = (bytes) => {
    return Buffer.from(bytes).toString('hex');
};

const fromAscii = (str) => {
    return Buffer.from(str).toString('hex');
};

const toUint8Array = (cbor) => {
    return Uint8Array.from(Buffer.from(cbor, 'hex'));
};

const showError = (message, exception) => {
    $toast.error(message, {
        position: 'top-right',
        duration: 0,
    });
    cryptoTxStatus.value = '';
    showPayInvoiceOnline.value = true;
    if (exception) {
        console.trace(exception);
    }
};

const payWithCrypto = async (walletName, walletDisplayName) =>
{
    // Set status
    cryptoTxStatus.value = `Connecting to ${walletDisplayName} Wallet...`;

    // Hide pay invoice online option
    showPayInvoiceOnline.value = false;

    // Connect to wallet
    try {
        selectedWallet.value = await window.cardano[walletName].enable();
    } catch (err) {
        showError(`Could not connect to ${walletDisplayName} Wallet: ${err.message}`, err);
        return;
    }

    // Check wallet network
    try {
        const selectedWalletNetworkId = await selectedWallet.value.getNetworkId();
        if (selectedWalletNetworkId !== props.targetCardanoNetwork.id) {
            showError(`${walletDisplayName} Wallet is in the wrong network, please switch to: ${props.targetCardanoNetwork.name}`);
            return;
        }
    } catch (err) {
        showError(`Failed to detect ${walletDisplayName} Wallet network: ${err.message}`, err);
        return;
    }

    // Set status
    cryptoTxStatus.value = `Connected to ${walletDisplayName} Wallet, building transaction...`;

    // Build, Sign and Submit transaction
    try {

        // Build transaction
        const txBuilder = TransactionBuilder.new(
            TransactionBuilderConfigBuilder.new()
                .fee_algo(
                    LinearFee.new(
                        BigNum.from_str(props.cryptoProtocolParameters.linearFee.minFeeA),
                        BigNum.from_str(props.cryptoProtocolParameters.linearFee.minFeeB)
                    )
                )
                .coins_per_utxo_byte(BigNum.from_str(props.cryptoProtocolParameters.costPerWord))
                .pool_deposit(BigNum.from_str(props.cryptoProtocolParameters.poolDeposit))
                .key_deposit(BigNum.from_str(props.cryptoProtocolParameters.keyDeposit))
                .max_value_size(props.cryptoProtocolParameters.maxValSize)
                .max_tx_size(props.cryptoProtocolParameters.maxTxSize)
                .build()
        );

        // Configure payment info
        const paymentAddress = props.cryptoPaymentAddress;
        const paymentAdaAmount = (calculateGrandTotal() / props.adaInvoiceCurrencyValue).toFixed(6);

        // Set the inputs
        const getUtxosCbor = Value.new(
            // Look for input utxo that satisfies intended payment ada amount + 2 ADA (to ensure change has sufficient balance to satisfy minUTXO)
            BigNum.from_str(((parseFloat(paymentAdaAmount) + 1) * 2_000_000).toString())
        ).to_hex();
        const inputs = TransactionUnspentOutputs.new();
        (await selectedWallet.value.getUtxos(getUtxosCbor)).map((utxo) => {
            inputs.add(TransactionUnspentOutput.from_bytes(fromHex(utxo)));
        });

        // Set the outputs
        txBuilder.add_output(
            TransactionOutputBuilder.new()
                .with_address(Address.from_bech32(paymentAddress))
                .next()
                .with_coin(BigNum.from_str((parseFloat(paymentAdaAmount) * 1_000_000).toString()))
                .build()
        );

        // Set the inputs
        try {
            txBuilder.add_inputs_from(inputs, CoinSelectionStrategyCIP2.LargestFirstMultiAsset);
        } catch (err) {
            showError(`Failed to set inputs from ${walletDisplayName} Wallet: ${err.message}`, err);
            return;
        }

        // Add metadata
        const metadatumIndex = '674';
        const metadatumValue = `CMIRef:${props.invoice.invoice_reference}`;
        const auxData = AuxiliaryData.new();
        const metadata = GeneralTransactionMetadata.new();
        metadata.insert(
            BigNum.from_str(metadatumIndex),
            TransactionMetadatum.new_text(metadatumValue)
        );
        auxData.set_metadata(metadata);
        txBuilder.add_metadatum(
            BigNum.from_str(metadatumIndex),
            TransactionMetadatum.new_text(metadatumValue)
        );

        // Set deadline for the transaction
        txBuilder.set_ttl(props.cryptoPaymentDeadline);

        // Set the change address
        const changeAddress = Address.from_bytes(Uint8Array.from(fromHex(await selectedWallet.value.getChangeAddress())));
        try {
            txBuilder.add_change_if_needed(changeAddress);
        } catch (err) {
            txBuilder.add_inputs_from(inputs, CoinSelectionStrategyCIP2.LargestFirstMultiAsset);
            try {
                txBuilder.add_change_if_needed(changeAddress);
            } catch (err) {
                showError(`Failed to set change address for ${walletDisplayName} Wallet: ${err.message}`, err);
                return;
            }
        }

        // Build transaction
        const transactionWitnessSet = TransactionWitnessSet.new();
        const txBody = txBuilder.build();
        const tx = Transaction.new(
            txBody,
            TransactionWitnessSet.from_bytes(transactionWitnessSet.to_bytes()),
            auxData,
        );

        // Sign the transaction
        let signedTx = null;
        try {

            // Set status
            cryptoTxStatus.value = `Connected to ${walletDisplayName} Wallet, requesting transaction signature...`;

            // Set witness
            const txVkeyWitnesses = await selectedWallet.value.signTx(tx.to_hex(), true);
            const witnesses = TransactionWitnessSet.from_bytes(fromHex(txVkeyWitnesses));
            transactionWitnessSet.set_vkeys(witnesses.vkeys());

            // Get signed transaction
            signedTx = Transaction.new(
                tx.body(),
                transactionWitnessSet,
                tx.auxiliary_data(),
            );

            // Submit the transaction
            try {

                // Set status
                cryptoTxStatus.value = `Connected to ${walletDisplayName} Wallet, submitting signed transaction...`;

                // Submit the signed transaction via connected wallet
                const txId = await selectedWallet.value.submitTx(signedTx.to_hex());

                // Register payment with backend
                axios.post(route('public.invoice.pay-via-crypto', { encodedId: props.invoice.invoice_reference }), {
                    payment_reference: txId,
                    crypto_wallet_name: walletDisplayName,
                })
                .then(res => {

                    // Check for success
                    if (res.data.success) {

                        // Toast
                        $toast.success(`Invoice successfully paid via ${walletDisplayName} Wallet. Email confirmation will be sent out shortly.`, {
                            position: 'top-right',
                            duration: 0,
                        });

                        // Set success status
                        const txExplorerUrl = `https://${props.targetCardanoNetwork.id !== 1 ? 'preprod.' : ''}cardanoscan.io/transaction/${txId}`;
                        cryptoTxStatus.value = `
                            <div class="mb-3">Payment submitted via ${walletDisplayName} Wallet</div>
                            <div>${props.targetCardanoNetwork.name} Transaction ID: <a href="${txExplorerUrl}" target="_blank"><strong>${txId}</strong></a></div>
                        `;

                    }

                    // Check for error
                    if (res.data.error) {

                        // Toast
                        $toast.error(res.data.error, {
                            position: 'top-right',
                            duration: 0,
                        });

                        // Set error status
                        cryptoTxStatus.value = `There was a problem processing your request`;

                    }

                });

            } catch (err) {
                showError(`Failed to submit transaction with ${walletDisplayName} Wallet: ${err.message}`, err);
            }

        } catch (err) {
            showError(`Payment cancelled by ${walletDisplayName} Wallet: ${err.message}`, err);
        }

    } catch (err) {
        showError(`Failed to build transaction with ${walletDisplayName} Wallet: ${err.message}`, err);
    }
};

</script>

<template>
    <invoice-layout title="View Invoice">
        <div class="container">

            <div v-if="stripePaymentCompleted" class="rounded-3xl flex items-center bg-blue-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert">
                <p>Stripe payment completed, we're now processing your invoice. Email confirmation will be sent out shortly.</p>
            </div>

            <div v-if="stripePaymentCancelled" class="rounded-3xl flex items-center bg-red-500 text-white text-sm font-bold px-4 py-3 mb-5" role="alert">
                <p>Stripe payment cancelled.</p>
            </div>

            <div class="rounded-3xl bg-white md:p-16 p-10 print:p-0 print:bg-black">
                <div class="flex flex-wrap items-center justify-between gap-6">
                    <div>
                        <h1 class="text-xl font-semibold uppercase tracking-widest">{{ invoice.user.business_name }}</h1>
                        <p class="text-gray-600">{{ invoice.user.name }}</p>
                    </div>
                    <div>
                        <h4 class="text-lg font-medium uppercase tracking-widest">Invoice #</h4>
                        <p class="text-lg float-rights tracking-widest font-semibold">{{ invoice.invoice_reference }}</p>
                    </div>
                </div>

                <div class="mt-10">
                    <div class="flex flex-wrap align-text-top justify-between gap-6">
                        <div v-if="billingAddress.length > 0">
                            <h4 class="text-lg font-medium uppercase tracking-widest mt-10">Billing To:</h4>
                            <p class="w-60 text-base font-normal tracking-widest">
                                <span v-for="billingAddressLine of billingAddress">
                                    {{ billingAddressLine }}<br>
                                </span>
                            </p>
                        </div>
                        <div>
                            <h4 class="text-lg font-medium uppercase tracking-widest mt-10">Invoice To:</h4>
                            <p class="w-60 text-base font-normal tracking-widest">
                                {{ invoice.customer.name }}
                                <span v-if="invoice.customer_reference && invoice.customer_reference.length > 0" class="text-sm text-gray-600">
                                    <br>
                                    Ref: {{ invoice.customer_reference }}
                                </span>
                            </p>
                            <h4 class="text-lg font-medium uppercase tracking-widest mt-5">Issue Date:</h4>
                            <p class="w-60 text-base font-normal tracking-widest">
                                {{ invoice.issue_date }}
                            </p>
                            <h4 class="text-lg font-medium uppercase tracking-widest mt-5">Due Date:</h4>
                            <p class="w-60 text-base font-normal tracking-widest">
                                {{ invoice.due_date }}
                            </p>
                        </div>
                        <div v-if="shippingAddress.length > 0">
                            <h4 class="text-lg font-medium uppercase tracking-widest mt-10">Shipping To:</h4>
                            <p class="w-60 text-base font-normal tracking-widest">
                                <span v-for="shippingAddressLine of shippingAddress">
                                    {{ shippingAddressLine }}<br>
                                </span>
                            </p>
                        </div>
                    </div>

                </div>

                <div class="overflow-x-auto">
                    <table class="border-collapse table-auto w-full text-sm mt-14 mb-12 whitespace-pre">
                        <thead>
                            <tr class=" bg-black">
                                <th class="p-4 border-b uppercase tracking-widest text-xl font-medium text-start text-white">Item</th>
                                <th class="p-4 border-b uppercase tracking-widest text-xl font-medium text-start text-white">Quantity</th>
                                <th class="p-4 border-b uppercase tracking-widest text-xl font-medium text-start text-white">Unit Price ({{ invoice.currency }})</th>
                                <th class="p-4 border-b uppercase tracking-widest text-xl font-medium text-end text-white">Tax Rate (%)</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white">
                            <tr v-for="item in invoice.items">
                                <td class="p-5 text-lg text-wrap font-medium border-b border-gray-800">
                                    <span v-if="item.sku" class="sm-badge">{{ item.sku }}</span>
                                    {{ item.description }}
                                </td>
                                <td class="p-5 text-lg font-medium border-b border-gray-800 text-center">{{ parseFloat(item.quantity) }}</td>
                                <td class="p-5 text-lg font-medium border-b border-gray-800">{{ parseFloat(item.unit_price) }}</td>
                                <td class="p-5 text-lg font-medium border-b border-gray-800 text-end">{{ parseFloat(item.tax_rate) }}</td>
                            </tr>
                            <tr>
                                <td colspan="4" class="p-5">
                                    <div class="flex justify-end">
                                        <table class="text-lg font-medium text-right">
                                            <tr>
                                                <td class="uppercase">Subtotal</td>
                                                <td>&nbsp;</td>
                                                <td>
                                                    {{ calculateSubTotal().toFixed(2) }} {{ invoice.currency }}
                                                    <span v-if="paymentMethod && paymentMethod === 'Crypto'" class="text-gray-600">({{ (calculateSubTotal() / props.adaInvoiceCurrencyValue).toFixed(2) }} ₳DA)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="uppercase">Total Tax</td>
                                                <td>&nbsp;</td>
                                                <td>
                                                    {{ calculateTotalTax().toFixed(2) }} {{ invoice.currency }}
                                                    <span v-if="paymentMethod && paymentMethod === 'Crypto'" class="text-gray-600">({{ (calculateTotalTax() / props.adaInvoiceCurrencyValue).toFixed(2) }} ₳DA)</span>
                                                </td>
                                            </tr>
                                            <tr>
                                                <td class="uppercase">Total Due</td>
                                                <td>&nbsp;</td>
                                                <td class="font-bold">
                                                    {{ calculateGrandTotal().toFixed(2) }} {{ invoice.currency }}
                                                    <span v-if="paymentMethod && paymentMethod === 'Crypto'" class="text-gray-600">({{ (calculateGrandTotal() / props.adaInvoiceCurrencyValue).toFixed(2) }} ₳DA)</span>
                                                </td>
                                            </tr>
                                        </table>
                                    </div>
                                </td>
                            </tr>
                            <tr v-if="paymentMethod && paymentMethod === 'Crypto'">
                                <td colspan="4" class="text-right pr-5">
                                    <div class="text-sm text-gray-600">
                                        Automatic Currency Conversion
                                        <br>
                                        <strong>1 {{ props.invoice.currency }}</strong> = <strong>{{ props.adaInvoiceCurrencyValue }} ₳DA</strong>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="grid grid-cols-2">
                    <div class="md:max-w-3xl" v-if="invoice.user.business_terms.length > 0">
                        <h1 class="text-xl font-semibold uppercase tracking-widest">Terms & conditions:</h1>
                        <p class="text-base font-medium mt-3">{{ invoice.user.business_terms }}</p>
                    </div>
                    <div v-if="invoice.status === 'Published'">
                        <div v-if="showPayInvoiceOnline">
                            <h1 class="text-xl font-semibold uppercase tracking-widest">Pay Invoice Online:</h1>
                            <div class="flex justify-center gap-1 mt-3">
                                <select
                                    id="account_currency"
                                    class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm w-full"
                                    v-model="paymentMethod"
                                >
                                    <option value="" disabled>Choose payment method</option>
                                    <option v-for="availablePaymentMethod in availablePaymentMethods" :value="availablePaymentMethod">
                                        {{ availablePaymentMethod }}
                                    </option>
                                </select>
                                <button v-if="paymentMethod && paymentMethod === 'Stripe'" @click="payWithStripe" type="button" class="btn btn-blue w-1/4">
                                    Pay Now
                                </button>
                            </div>
                        </div>
                        <div v-if="paymentMethod && paymentMethod === 'Crypto'" class="mt-5">
                            <div v-if="cryptoTxStatus.length > 0" v-html="cryptoTxStatus" class="p-2 bg-blue-100 text-blue-700 text-center rounded" />

                            <div v-if="availableWallets.length === 0" class="p-2 bg-amber-100 text-amber-700 text-center rounded">
                                Could not detect any suitable cardano wallet.
                                Please visit <a class="underline" href="https://cardanowallets.io" target="_blank">https://cardanowallets.io</a> and install a wallet first, and then try again.
                            </div>

                            <div v-if="!cryptoTxStatus.length && availableWallets.length > 0" class="p-2 grid grid-cols-4 gap-5 rounded shadow">
                                <div v-for="availableWallet in availableWallets" class="flex justify-center">
                                    <button @click="payWithCrypto(availableWallet.walletName, availableWallet.walletDisplayName)" :title="`Pay with ${availableWallet.walletDisplayName} Wallet`" type="button" class="text-center">
                                        <img :src="availableWallet.walletIcon" alt="" width="64" />
                                        {{ availableWallet.walletDisplayName }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-if="invoice.status !== 'Published'">
                        <h1 class="text-xl font-semibold uppercase tracking-widest">Invoice Status:</h1>
                        <div class="mt-3">
                            <span :class="`font-medium status-${invoice.status.replace(' ', '_')}`">{{ invoice.status }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <div class="text-center text-sm text-gray-600 mt-5">
                Powered by <a target="_blank" class="text-blue-600" href="/">Cardano Mercury: Invoice</a>
            </div>

        </div>
    </invoice-layout>
</template>
