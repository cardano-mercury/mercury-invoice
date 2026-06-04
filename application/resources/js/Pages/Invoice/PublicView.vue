<script setup>
import { ref, computed, onMounted, onBeforeUnmount } from 'vue';
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
import { useToast } from 'vue-toast-notification';

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

const grandTotalInAda = computed(() => {
    if (!props.adaInvoiceCurrencyValue) {
        return null;
    }
    return (calculateGrandTotal() / props.adaInvoiceCurrencyValue).toFixed(6);
});

const formatMoney = (value) => {
    return Number(value).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    });
};

const formatAda = (value) => {
    if (value === null || value === undefined) {
        return null;
    }
    return Number(value).toLocaleString(undefined, {
        minimumFractionDigits: 2,
        maximumFractionDigits: 6,
    });
};

const payWithStripe = () => {
    window.location.href = route('public.invoice.pay-via-stripe', { encodedId: props.invoice.invoice_reference });
};

const statusMeta = computed(() => {
    const status = props.invoice?.status;
    switch (status) {
        case 'Draft':
            return { color: 'secondary', icon: 'mdi-file-document-outline', label: 'Draft' };
        case 'Published':
            return { color: 'info', icon: 'mdi-send', label: 'Awaiting Payment' };
        case 'Payment Processing':
            return { color: 'warning', icon: 'mdi-clock-outline', label: 'Payment Processing' };
        case 'Paid':
            return { color: 'success', icon: 'mdi-check-circle', label: 'Paid' };
        case 'Voided':
            return { color: 'error', icon: 'mdi-cancel', label: 'Voided' };
        default:
            return { color: 'secondary', icon: 'mdi-file-document', label: status };
    }
});

const isOverdue = computed(() => {
    if (!props.invoice?.due_date || props.invoice.status !== 'Published') {
        return false;
    }
    const due = new Date(props.invoice.due_date);
    const today = new Date();
    today.setHours(0, 0, 0, 0);
    return due < today;
});

const isPublished = computed(() => props.invoice?.status === 'Published');

const hasPaymentMethods = computed(() => Array.isArray(props.availablePaymentMethods) && props.availablePaymentMethods.length > 0);

const showStripe = computed(() => props.availablePaymentMethods?.includes('Stripe'));
const showCrypto = computed(() => props.availablePaymentMethods?.includes('Crypto'));

const availableWallets = ref([]);
let walletDetectionTimeout = null;

const detectWallets = () => {
    const detected = [];
    if (typeof window !== 'undefined' && window.cardano !== undefined) {
        for (const [walletName, walletObject] of Object.entries(window.cardano)) {
            if (['enable', 'isEnabled'].includes(walletName)) {
                continue;
            }
            if (!walletObject?.icon) {
                continue;
            }
            let walletDisplayName = walletObject.name.replace('Wallet', '').trim();
            walletDisplayName = walletDisplayName.charAt(0).toUpperCase() + walletDisplayName.slice(1);
            detected.push({
                walletName,
                walletDisplayName,
                walletIcon: walletObject.icon,
            });
        }
    }
    availableWallets.value = detected;
};

onMounted(() => {
    walletDetectionTimeout = setTimeout(detectWallets, 500);
});

onBeforeUnmount(() => {
    if (walletDetectionTimeout) {
        clearTimeout(walletDetectionTimeout);
    }
});

const fromHex = (hex) => {
    return Buffer.from(hex, "hex");
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

const payWithCrypto = async (walletName, walletDisplayName) => {
    cryptoTxStatus.value = `Connecting to ${walletDisplayName} Wallet...`;
    showPayInvoiceOnline.value = false;

    try {
        selectedWallet.value = await window.cardano[walletName].enable();
    } catch (err) {
        showError(`Could not connect to ${walletDisplayName} Wallet: ${err.message}`, err);
        return;
    }

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

    cryptoTxStatus.value = `Connected to ${walletDisplayName} Wallet, building transaction...`;

    try {
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

        const paymentAddress = props.cryptoPaymentAddress;
        const paymentAdaAmount = (calculateGrandTotal() / props.adaInvoiceCurrencyValue).toFixed(6);

        const getUtxosCbor = Value.new(
            BigNum.from_str(((parseFloat(paymentAdaAmount) + 1) * 2_000_000).toString())
        ).to_hex();
        const inputs = TransactionUnspentOutputs.new();
        (await selectedWallet.value.getUtxos(getUtxosCbor)).map((utxo) => {
            inputs.add(TransactionUnspentOutput.from_bytes(fromHex(utxo)));
        });

        txBuilder.add_output(
            TransactionOutputBuilder.new()
                .with_address(Address.from_bech32(paymentAddress))
                .next()
                .with_coin(BigNum.from_str((parseFloat(paymentAdaAmount) * 1_000_000).toString()))
                .build()
        );

        try {
            txBuilder.add_inputs_from(inputs, CoinSelectionStrategyCIP2.LargestFirstMultiAsset);
        } catch (err) {
            showError(`Failed to set inputs from ${walletDisplayName} Wallet: ${err.message}`, err);
            return;
        }

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

        txBuilder.set_ttl(props.cryptoPaymentDeadline);

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

        const transactionWitnessSet = TransactionWitnessSet.new();
        const txBody = txBuilder.build();
        const tx = Transaction.new(
            txBody,
            TransactionWitnessSet.from_bytes(transactionWitnessSet.to_bytes()),
            auxData,
        );

        let signedTx = null;
        try {
            cryptoTxStatus.value = `Connected to ${walletDisplayName} Wallet, requesting transaction signature...`;

            const txVkeyWitnesses = await selectedWallet.value.signTx(tx.to_hex(), true);
            const witnesses = TransactionWitnessSet.from_bytes(fromHex(txVkeyWitnesses));
            transactionWitnessSet.set_vkeys(witnesses.vkeys());

            signedTx = Transaction.new(
                tx.body(),
                transactionWitnessSet,
                tx.auxiliary_data(),
            );

            try {
                cryptoTxStatus.value = `Connected to ${walletDisplayName} Wallet, submitting signed transaction...`;

                const txId = await selectedWallet.value.submitTx(signedTx.to_hex());

                axios.post(route('public.invoice.pay-via-crypto', { encodedId: props.invoice.invoice_reference }), {
                    payment_reference: txId,
                    crypto_wallet_name: walletDisplayName,
                })
                    .then(res => {
                        if (res.data.success) {
                            $toast.success(`Invoice successfully paid via ${walletDisplayName} Wallet. Email confirmation will be sent out shortly.`, {
                                position: 'top-right',
                                duration: 0,
                            });

                            const txExplorerUrl = `https://${props.targetCardanoNetwork.id !== 1 ? 'preprod.' : ''}cardanoscan.io/transaction/${txId}`;
                            cryptoTxStatus.value = `
                            <div class="mb-2 font-weight-bold">Payment submitted via ${walletDisplayName} Wallet</div>
                            <div>${props.targetCardanoNetwork.name} Transaction ID: <a href="${txExplorerUrl}" target="_blank"><strong>${txId}</strong></a></div>
                        `;
                        }

                        if (res.data.error) {
                            $toast.error(res.data.error, {
                                position: 'top-right',
                                duration: 0,
                            });
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

const selectPaymentMethod = (method) => {
    paymentMethod.value = method;
    cryptoTxStatus.value = '';
    showPayInvoiceOnline.value = true;
};
</script>

<template>
    <invoice-layout title="View Invoice">
        <div class="invoice-page">
            <!-- Header / hero -->
            <v-card class="invoice-header-card mb-4 mb-md-6" elevation="2">
                <div class="invoice-header-inner">
                    <div class="invoice-header-brand">
                        <div class="invoice-header-avatar">
                            <v-icon icon="mdi-domain" size="28" color="primary" />
                        </div>
                        <div class="invoice-header-meta">
                            <h1 class="invoice-header-business">
                                {{ invoice.user.business_name }}
                            </h1>
                            <p class="invoice-header-person">
                                <v-icon icon="mdi-account-outline" size="14" class="me-1" />
                                {{ invoice.user.name }}
                            </p>
                        </div>
                    </div>

                    <div class="invoice-header-right">
                        <div class="invoice-header-ref">
                            <span class="invoice-header-ref-label">Invoice</span>
                            <span class="invoice-header-ref-value">#{{ invoice.invoice_reference }}</span>
                        </div>
                        <v-chip
                            :color="statusMeta.color"
                            variant="tonal"
                            size="small"
                            class="invoice-status-chip"
                        >
                            <v-icon :icon="statusMeta.icon" size="14" start />
                            {{ statusMeta.label }}
                        </v-chip>
                    </div>
                </div>
            </v-card>

            <!-- Stripe result banners -->
            <v-alert
                v-if="stripePaymentCompleted"
                type="success"
                variant="tonal"
                class="mb-4 mb-md-6"
                icon="mdi-check-circle"
                title="Stripe payment received"
                text="We're now processing your invoice. Email confirmation will be sent out shortly."
            />
            <v-alert
                v-if="stripePaymentCancelled"
                type="warning"
                variant="tonal"
                class="mb-4 mb-md-6"
                icon="mdi-alert-circle"
                title="Stripe payment cancelled"
                text="Your payment was cancelled and no charges were made. You can try again below."
            />

            <!-- Status not 'Published' banner -->
            <v-alert
                v-if="!isPublished"
                :type="statusMeta.color"
                variant="tonal"
                class="mb-4 mb-md-6"
                :icon="statusMeta.icon"
            >
                <div class="d-flex align-center flex-wrap ga-2">
                    <strong>This invoice is currently {{ statusMeta.label.toLowerCase() }}.</strong>
                    <span class="text-medium-emphasis">Payments are not available at this time.</span>
                </div>
            </v-alert>

            <!-- Overdue banner -->
            <v-alert
                v-if="isPublished && isOverdue"
                type="warning"
                variant="tonal"
                class="mb-4 mb-md-6"
                icon="mdi-clock-alert-outline"
                :title="`This invoice is past its due date of ${invoice.due_date}.`"
            />

            <!-- Main two-column layout -->
            <v-row class="invoice-main-row">
                <!-- Left: invoice details -->
                <v-col cols="12" md="8" class="invoice-details-col">
                    <!-- Customer + dates card -->
                    <v-card class="invoice-section mb-4 mb-md-6" elevation="2">
                        <div class="section-header">
                            <h2 class="section-title">
                                <v-icon icon="mdi-file-document-outline" size="20" color="primary" class="me-2" />
                                Invoice Details
                            </h2>
                        </div>
                        <div class="section-body">
                            <div class="detail-grid">
                                <div class="detail-block">
                                    <span class="detail-label">Billed To</span>
                                    <p class="detail-value detail-value-strong">
                                        {{ invoice.customer.name }}
                                    </p>
                                    <p
                                        v-if="invoice.customer_reference && invoice.customer_reference.length"
                                        class="detail-value detail-value-muted"
                                    >
                                        Ref: {{ invoice.customer_reference }}
                                    </p>
                                </div>

                                <div class="detail-block">
                                    <span class="detail-label">Issue Date</span>
                                    <p class="detail-value">
                                        <v-icon icon="mdi-calendar-start" size="16" class="me-1 text-medium-emphasis" />
                                        {{ invoice.issue_date }}
                                    </p>
                                </div>

                                <div class="detail-block">
                                    <span class="detail-label">Due Date</span>
                                    <p class="detail-value" :class="{ 'detail-value-overdue': isOverdue && isPublished }">
                                        <v-icon
                                            :icon="isOverdue && isPublished ? 'mdi-clock-alert-outline' : 'mdi-calendar-end'"
                                            size="16"
                                            class="me-1"
                                            :class="{ 'text-warning': isOverdue && isPublished }"
                                        />
                                        {{ invoice.due_date }}
                                    </p>
                                </div>
                            </div>

                            <v-divider v-if="billingAddress.length || shippingAddress.length" class="my-4 my-md-5" />

                            <div v-if="billingAddress.length || shippingAddress.length" class="address-grid">
                                <div v-if="billingAddress.length" class="address-block">
                                    <span class="detail-label">
                                        <v-icon icon="mdi-map-marker-outline" size="14" class="me-1" />
                                        Billing Address
                                    </span>
                                    <p
                                        v-for="line in billingAddress"
                                        :key="`bill-${line}`"
                                        class="address-line"
                                    >
                                        {{ line }}
                                    </p>
                                </div>
                                <div v-if="shippingAddress.length" class="address-block">
                                    <span class="detail-label">
                                        <v-icon icon="mdi-truck-delivery-outline" size="14" class="me-1" />
                                        Shipping Address
                                    </span>
                                    <p
                                        v-for="line in shippingAddress"
                                        :key="`ship-${line}`"
                                        class="address-line"
                                    >
                                        {{ line }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </v-card>

                    <!-- Line items -->
                    <v-card class="invoice-section mb-4 mb-md-6" elevation="2">
                        <div class="section-header">
                            <h2 class="section-title">
                                <v-icon icon="mdi-format-list-bulleted" size="20" color="primary" class="me-2" />
                                Line Items
                            </h2>
                        </div>
                        <div class="section-body section-body-flush">
                            <!-- Desktop / tablet: table view -->
                            <div class="line-items-table-wrapper d-none d-md-block">
                                <table class="line-items-table">
                                    <thead>
                                        <tr>
                                            <th class="text-start">Item</th>
                                            <th class="text-end">Qty</th>
                                            <th class="text-end">Unit Price</th>
                                            <th class="text-end">Tax %</th>
                                            <th class="text-end">Subtotal</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr v-for="item in invoice.items" :key="item.id">
                                            <td>
                                                <div v-if="item.sku" class="line-item-sku">{{ item.sku }}</div>
                                                <div class="line-item-desc">{{ item.description }}</div>
                                            </td>
                                            <td class="text-end">{{ parseFloat(item.quantity) }}</td>
                                            <td class="text-end">{{ formatMoney(item.unit_price) }} {{ invoice.currency }}</td>
                                            <td class="text-end">{{ parseFloat(item.tax_rate) }}%</td>
                                            <td class="text-end line-item-subtotal">
                                                {{ formatMoney(parseFloat(item.quantity) * parseFloat(item.unit_price)) }} {{ invoice.currency }}
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                            <!-- Mobile: card view -->
                            <div class="line-items-cards d-md-none">
                                <div
                                    v-for="item in invoice.items"
                                    :key="`m-${item.id}`"
                                    class="line-item-card"
                                >
                                    <div class="line-item-card-head">
                                        <div class="line-item-card-meta">
                                            <div v-if="item.sku" class="line-item-sku">{{ item.sku }}</div>
                                            <div class="line-item-desc">{{ item.description }}</div>
                                        </div>
                                        <div class="line-item-card-subtotal">
                                            {{ formatMoney(parseFloat(item.quantity) * parseFloat(item.unit_price)) }} {{ invoice.currency }}
                                        </div>
                                    </div>
                                    <div class="line-item-card-grid">
                                        <div>
                                            <span class="line-item-card-label">Qty</span>
                                            <span class="line-item-card-value">{{ parseFloat(item.quantity) }}</span>
                                        </div>
                                        <div>
                                            <span class="line-item-card-label">Unit Price</span>
                                            <span class="line-item-card-value">{{ formatMoney(item.unit_price) }} {{ invoice.currency }}</span>
                                        </div>
                                        <div>
                                            <span class="line-item-card-label">Tax</span>
                                            <span class="line-item-card-value">{{ parseFloat(item.tax_rate) }}%</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-card>

                    <!-- Summary -->
                    <v-card class="invoice-section mb-4 mb-md-6" elevation="2">
                        <div class="section-body section-body-tight">
                            <div class="summary-wrap">
                                <div class="summary-rows">
                                    <div class="summary-row">
                                        <span class="summary-label">Subtotal</span>
                                        <span class="summary-value">
                                            {{ formatMoney(calculateSubTotal()) }} {{ invoice.currency }}
                                            <span v-if="paymentMethod === 'Crypto'" class="summary-ada">
                                                ≈ {{ formatAda(calculateSubTotal() / props.adaInvoiceCurrencyValue) }} ₳
                                            </span>
                                        </span>
                                    </div>
                                    <div class="summary-row">
                                        <span class="summary-label">Total Tax</span>
                                        <span class="summary-value">
                                            {{ formatMoney(calculateTotalTax()) }} {{ invoice.currency }}
                                            <span v-if="paymentMethod === 'Crypto'" class="summary-ada">
                                                ≈ {{ formatAda(calculateTotalTax() / props.adaInvoiceCurrencyValue) }} ₳
                                            </span>
                                        </span>
                                    </div>
                                    <div class="summary-row summary-row-total">
                                        <span class="summary-label">Total Due</span>
                                        <span class="summary-value summary-value-total">
                                            <span class="summary-amount">
                                                {{ formatMoney(calculateGrandTotal()) }} {{ invoice.currency }}
                                            </span>
                                            <span v-if="paymentMethod === 'Crypto'" class="summary-ada summary-ada-strong">
                                                ≈ {{ formatAda(grandTotalInAda) }} ₳ ADA
                                            </span>
                                        </span>
                                    </div>
                                    <div
                                        v-if="paymentMethod === 'Crypto'"
                                        class="summary-row summary-row-conversion"
                                    >
                                        <span class="summary-label">Currency Conversion</span>
                                        <span class="summary-value summary-conversion">
                                            1 {{ invoice.currency }} = {{ props.adaInvoiceCurrencyValue }} ₳ ADA
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </v-card>

                    <!-- Terms & conditions -->
                    <v-card
                        v-if="invoice.user.business_terms && invoice.user.business_terms.length"
                        class="invoice-section"
                        elevation="2"
                    >
                        <div class="section-header">
                            <h2 class="section-title">
                                <v-icon icon="mdi-text-box-check-outline" size="20" color="primary" class="me-2" />
                                Terms &amp; Conditions
                            </h2>
                        </div>
                        <div class="section-body">
                            <p class="terms-text">{{ invoice.user.business_terms }}</p>
                        </div>
                    </v-card>
                </v-col>

                <!-- Right: payment panel (hidden in print) -->
                <v-col v-if="isPublished" cols="12" md="4" class="payment-col no-print">
                    <div class="payment-sticky">
                        <v-card class="payment-card" elevation="3">
                            <div class="payment-card-head">
                                <span class="payment-card-eyebrow">Pay this invoice</span>
                                <div class="payment-card-total">
                                    <span class="payment-card-total-amount">
                                        {{ formatMoney(calculateGrandTotal()) }}
                                    </span>
                                    <span class="payment-card-total-currency">
                                        {{ invoice.currency }}
                                    </span>
                                </div>
                                <div v-if="paymentMethod === 'Crypto'" class="payment-card-ada">
                                    ≈ {{ formatAda(grandTotalInAda) }} ₳ ADA
                                </div>
                                <div v-else class="payment-card-due">
                                    Due {{ invoice.due_date }}
                                </div>
                            </div>

                            <v-divider class="payment-divider" />

                            <div class="payment-card-body">
                                <p class="payment-method-prompt">
                                    Choose how you'd like to pay
                                </p>

                                <div class="payment-method-grid">
                                    <button
                                        v-if="showStripe"
                                        type="button"
                                        class="payment-method-tile"
                                        :class="{ 'payment-method-tile-selected': paymentMethod === 'Stripe' }"
                                        :aria-pressed="paymentMethod === 'Stripe'"
                                        @click="selectPaymentMethod('Stripe')"
                                    >
                                        <div class="payment-method-tile-icon payment-method-tile-icon-stripe">
                                            <v-icon icon="mdi-credit-card-outline" size="22" />
                                        </div>
                                        <div class="payment-method-tile-body">
                                            <span class="payment-method-tile-title">Credit Card</span>
                                            <span class="payment-method-tile-sub">Pay with Stripe</span>
                                        </div>
                                        <v-icon
                                            v-if="paymentMethod === 'Stripe'"
                                            icon="mdi-check-circle"
                                            color="primary"
                                            class="payment-method-tile-check"
                                        />
                                    </button>

                                    <button
                                        v-if="showCrypto"
                                        type="button"
                                        class="payment-method-tile"
                                        :class="{ 'payment-method-tile-selected': paymentMethod === 'Crypto' }"
                                        :aria-pressed="paymentMethod === 'Crypto'"
                                        @click="selectPaymentMethod('Crypto')"
                                    >
                                        <div class="payment-method-tile-icon payment-method-tile-icon-crypto">
                                            <v-icon icon="mdi-currency-btc" size="22" />
                                        </div>
                                        <div class="payment-method-tile-body">
                                            <span class="payment-method-tile-title">Crypto (ADA)</span>
                                            <span class="payment-method-tile-sub">Pay with Cardano</span>
                                        </div>
                                        <v-icon
                                            v-if="paymentMethod === 'Crypto'"
                                            icon="mdi-check-circle"
                                            color="primary"
                                            class="payment-method-tile-check"
                                        />
                                    </button>
                                </div>

                                <v-alert
                                    v-if="!hasPaymentMethods"
                                    type="error"
                                    variant="tonal"
                                    class="mt-3"
                                    icon="mdi-alert-circle-outline"
                                    text="No payment methods are currently available for this invoice."
                                />

                                <!-- Action area per selected method -->
                                <div v-if="paymentMethod" class="payment-action-area">
                                    <template v-if="paymentMethod === 'Stripe'">
                                        <v-btn
                                            block
                                            size="large"
                                            color="primary"
                                            prepend-icon="mdi-credit-card-lock-outline"
                                            @click="payWithStripe"
                                        >
                                            Pay {{ formatMoney(calculateGrandTotal()) }} {{ invoice.currency }}
                                        </v-btn>
                                        <p class="payment-action-hint">
                                            <v-icon icon="mdi-shield-check-outline" size="14" />
                                            <span class="payment-action-hint-text">
                                                Secure checkout via Stripe
                                            </span>
                                        </p>
                                    </template>

                                    <template v-if="paymentMethod === 'Crypto'">
                                        <v-alert
                                            v-if="cryptoTxStatus"
                                            type="info"
                                            variant="tonal"
                                            class="mb-3"
                                        >
                                            <span v-html="cryptoTxStatus" />
                                        </v-alert>

                                        <template v-if="availableWallets.length === 0">
                                            <v-alert
                                                type="error"
                                                variant="tonal"
                                                class="mb-3"
                                                icon="mdi-wallet-off-outline"
                                            >
                                                <div class="font-weight-bold mb-1">No Wallets Found</div>
                                                <div>
                                                    We could not detect any Cardano wallets. Please visit
                                                    <a
                                                        href="https://cardanowallets.io"
                                                        target="_blank"
                                                        rel="noopener"
                                                    >cardanowallets.io</a>
                                                    to install a wallet, then refresh this page.
                                                </div>
                                            </v-alert>
                                        </template>

                                        <template v-else>
                                            <p class="payment-wallet-label">Select your wallet</p>
                                            <div class="payment-wallet-grid">
                                                <button
                                                    v-for="wallet in availableWallets"
                                                    :key="wallet.walletName"
                                                    type="button"
                                                    class="payment-wallet-tile"
                                                    :title="`Pay with ${wallet.walletDisplayName}`"
                                                    :disabled="cryptoTxStatus.length > 0"
                                                    @click="payWithCrypto(wallet.walletName, wallet.walletDisplayName)"
                                                >
                                                    <img
                                                        :src="wallet.walletIcon"
                                                        :alt="wallet.walletDisplayName"
                                                        class="payment-wallet-icon"
                                                    />
                                                    <span class="payment-wallet-name">{{ wallet.walletDisplayName }}</span>
                                                </button>
                                            </div>
                                            <p class="payment-action-hint">
                                                <v-icon icon="mdi-information-outline" size="14" />
                                                <span class="payment-action-hint-text">
                                                    Make sure your wallet is set to the
                                                    <strong>{{ props.targetCardanoNetwork.name }}</strong> network.
                                                </span>
                                            </p>
                                        </template>
                                    </template>
                                </div>
                            </div>
                        </v-card>
                    </div>
                </v-col>
            </v-row>
        </div>
    </invoice-layout>
</template>

<style scoped>
.invoice-page {
    max-width: 1200px;
    margin: 0 auto;
}

/* Header card */
.invoice-header-card {
    border-radius: var(--radius-xl);
}

.invoice-header-inner {
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: var(--space-4);
    padding: var(--space-5) var(--space-6);
    flex-wrap: wrap;
}

.invoice-header-brand {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    min-width: 0;
}

.invoice-header-avatar {
    width: 52px;
    height: 52px;
    border-radius: var(--radius-lg);
    background: rgba(var(--color-primary-rgb), 0.12);
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.invoice-header-meta {
    min-width: 0;
}

.invoice-header-business {
    font-family: var(--font-family-heading);
    font-size: var(--text-xl);
    font-weight: var(--font-black);
    color: var(--color-text-primary);
    margin: 0;
    line-height: 1.2;
    word-break: break-word;
}

.invoice-header-person {
    font-size: var(--text-sm);
    color: var(--color-text-secondary);
    margin: 4px 0 0;
    display: flex;
    align-items: center;
}

.invoice-header-right {
    display: flex;
    align-items: center;
    gap: var(--space-3);
    flex-wrap: wrap;
}

.invoice-header-ref {
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    line-height: 1.2;
}

.invoice-header-ref-label {
    font-size: var(--text-xs);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-text-muted);
    font-weight: var(--font-semibold);
}

.invoice-header-ref-value {
    font-family: var(--font-family-heading);
    font-size: var(--text-lg);
    font-weight: var(--font-bold);
    color: var(--color-text-primary);
}

.invoice-status-chip {
    font-weight: var(--font-semibold) !important;
}

/* Section cards */
.invoice-section {
    border-radius: var(--radius-xl);
    overflow: hidden;
}

.section-header {
    padding: var(--space-4) var(--space-6);
    border-bottom: 1px solid var(--color-surface-border);
    background: var(--color-surface-secondary);
}

.section-title {
    font-family: var(--font-family-heading);
    font-size: var(--text-base);
    font-weight: var(--font-bold);
    color: var(--color-text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    letter-spacing: 0.01em;
}

.section-body {
    padding: var(--space-5) var(--space-6);
}

.section-body-flush {
    padding: 0;
}

.section-body-tight {
    padding: var(--space-4) var(--space-6);
}

/* Detail grid */
.detail-grid {
    display: grid;
    grid-template-columns: 2fr 1fr 1fr;
    gap: var(--space-5);
}

.detail-block {
    min-width: 0;
}

.detail-label {
    display: inline-flex;
    align-items: center;
    font-size: var(--text-xs);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-text-muted);
    font-weight: var(--font-semibold);
    margin-bottom: var(--space-1);
}

.detail-value {
    font-size: var(--text-sm);
    color: var(--color-text-primary);
    margin: 0;
    display: flex;
    align-items: center;
    word-break: break-word;
}

.detail-value-strong {
    font-weight: var(--font-semibold);
    font-size: var(--text-base);
}

.detail-value-muted {
    color: var(--color-text-secondary);
    margin-top: 2px;
}

.detail-value-overdue {
    color: var(--color-warning);
    font-weight: var(--font-semibold);
}

.address-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-5);
}

.address-line {
    font-size: var(--text-sm);
    color: var(--color-text-primary);
    margin: 0;
    line-height: 1.5;
}

/* Line items table */
.line-items-table-wrapper {
    overflow-x: auto;
}

.line-items-table {
    width: 100%;
    border-collapse: collapse;
    font-size: var(--text-sm);
}

.line-items-table thead th {
    background: var(--color-surface-secondary);
    font-family: var(--font-family-body);
    font-weight: var(--font-semibold);
    color: var(--color-text-secondary);
    text-transform: uppercase;
    font-size: var(--text-xs);
    letter-spacing: 0.05em;
    padding: var(--space-3) var(--space-4);
    border-bottom: 1px solid var(--color-surface-border);
    white-space: nowrap;
}

.line-items-table tbody td {
    padding: var(--space-3) var(--space-4);
    border-bottom: 1px solid var(--color-surface-border);
    color: var(--color-text-primary);
    vertical-align: top;
}

.line-items-table tbody tr:last-child td {
    border-bottom: none;
}

.line-item-sku {
    display: inline-block;
    font-family: var(--font-family-heading);
    font-size: var(--text-xs);
    font-weight: var(--font-semibold);
    color: var(--color-text-secondary);
    background: var(--color-surface-tertiary);
    padding: 2px 8px;
    border-radius: var(--radius-sm);
    margin-bottom: 4px;
    letter-spacing: 0.02em;
}

.line-item-desc {
    color: var(--color-text-primary);
    line-height: 1.4;
}

.line-item-subtotal {
    font-weight: var(--font-semibold);
    white-space: nowrap;
}

/* Line items card (mobile) */
.line-items-cards {
    display: flex;
    flex-direction: column;
}

.line-item-card {
    padding: var(--space-4);
    border-bottom: 1px solid var(--color-surface-border);
}

.line-item-card:last-child {
    border-bottom: none;
}

.line-item-card-head {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: var(--space-3);
    margin-bottom: var(--space-2);
}

.line-item-card-meta {
    min-width: 0;
    flex: 1;
}

.line-item-card-subtotal {
    font-weight: var(--font-bold);
    font-size: var(--text-sm);
    white-space: nowrap;
}

.line-item-card-grid {
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: var(--space-2);
}

.line-item-card-grid > div {
    display: flex;
    flex-direction: column;
}

.line-item-card-label {
    font-size: var(--text-xs);
    text-transform: uppercase;
    color: var(--color-text-muted);
    letter-spacing: 0.05em;
    font-weight: var(--font-semibold);
}

.line-item-card-value {
    font-size: var(--text-sm);
    color: var(--color-text-primary);
    margin-top: 2px;
}

/* Summary */
.summary-wrap {
    display: flex;
    justify-content: flex-end;
}

.summary-rows {
    width: 100%;
    max-width: 380px;
    display: flex;
    flex-direction: column;
    gap: var(--space-2);
}

.summary-row {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    gap: var(--space-3);
    padding: var(--space-2) 0;
    font-size: var(--text-sm);
}

.summary-label {
    color: var(--color-text-secondary);
    font-weight: var(--font-medium);
    white-space: nowrap;
    padding-top: 2px;
}

.summary-value {
    font-weight: var(--font-semibold);
    color: var(--color-text-primary);
    text-align: right;
    white-space: nowrap;
}

.summary-ada {
    display: block;
    color: var(--color-text-muted);
    font-weight: var(--font-regular);
    font-size: var(--text-xs);
    margin-top: 2px;
}

.summary-row-total {
    border-top: 1px solid var(--color-surface-border);
    padding-top: var(--space-3);
    margin-top: var(--space-1);
}

.summary-value-total {
    text-align: right;
    display: flex;
    flex-direction: column;
    align-items: flex-end;
    gap: 2px;
}

.summary-amount {
    font-family: var(--font-family-heading);
    font-size: var(--text-xl);
    font-weight: var(--font-black);
    color: var(--color-text-primary);
    line-height: 1.1;
}

.summary-ada-strong {
    color: var(--color-text-secondary);
    font-weight: var(--font-medium);
    font-size: var(--text-sm);
    margin-top: 0;
}

.summary-row-conversion {
    margin-top: var(--space-2);
    padding-top: var(--space-3);
    border-top: 1px dashed var(--color-surface-border);
}

.summary-conversion {
    color: var(--color-text-secondary);
    font-weight: var(--font-medium);
}

/* Terms */
.terms-text {
    font-size: var(--text-sm);
    color: var(--color-text-primary);
    line-height: 1.6;
    white-space: pre-line;
    margin: 0;
}

/* Payment card */
.payment-col {
    display: flex;
}

.payment-sticky {
    width: 100%;
    position: sticky;
    top: var(--space-4);
}

.payment-card {
    border-radius: var(--radius-xl);
    overflow: hidden;
    width: 100%;
}

.payment-card-head {
    padding: var(--space-5) var(--space-5) var(--space-4);
    background: linear-gradient(135deg, rgba(var(--color-primary-rgb), 0.08), rgba(var(--color-primary-rgb), 0.02));
}

.payment-card-eyebrow {
    display: block;
    font-size: var(--text-xs);
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: var(--color-text-secondary);
    font-weight: var(--font-semibold);
    margin-bottom: var(--space-2);
}

.payment-card-total {
    display: flex;
    align-items: baseline;
    gap: var(--space-2);
    flex-wrap: wrap;
}

.payment-card-total-amount {
    font-family: var(--font-family-heading);
    font-size: var(--text-3xl);
    font-weight: var(--font-black);
    color: var(--color-text-primary);
    line-height: 1;
}

.payment-card-total-currency {
    font-size: var(--text-base);
    font-weight: var(--font-semibold);
    color: var(--color-text-secondary);
}

.payment-card-ada {
    margin-top: var(--space-2);
    font-size: var(--text-sm);
    color: var(--color-primary-dark);
    font-weight: var(--font-semibold);
}

.payment-card-due {
    margin-top: var(--space-2);
    font-size: var(--text-sm);
    color: var(--color-text-secondary);
    display: inline-flex;
    align-items: center;
}

.payment-divider {
    border-color: var(--color-surface-border) !important;
}

.payment-card-body {
    padding: var(--space-5);
}

.payment-method-prompt {
    font-size: var(--text-sm);
    color: var(--color-text-secondary);
    margin: 0 0 var(--space-3);
    font-weight: var(--font-medium);
}

.payment-method-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: var(--space-2);
}

.payment-method-tile {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    text-align: center;
    gap: var(--space-2);
    padding: var(--space-4) var(--space-2);
    background: var(--color-surface);
    border: 1.5px solid var(--color-surface-border);
    border-radius: var(--radius-lg);
    cursor: pointer;
    transition: all var(--transition-fast);
    color: var(--color-text-primary);
    font: inherit;
}

.payment-method-tile:hover {
    border-color: rgba(var(--color-primary-rgb), 0.4);
    background: rgba(var(--color-primary-rgb), 0.03);
}

.payment-method-tile:focus-visible {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(var(--color-primary-rgb), 0.15);
}

.payment-method-tile-selected {
    border-color: var(--color-primary);
    background: rgba(var(--color-primary-rgb), 0.06);
    box-shadow: 0 0 0 3px rgba(var(--color-primary-rgb), 0.12);
}

.payment-method-tile-icon {
    width: 40px;
    height: 40px;
    border-radius: var(--radius-md);
    display: flex;
    align-items: center;
    justify-content: center;
}

.payment-method-tile-icon-stripe {
    background: rgba(99, 91, 255, 0.1);
    color: #635BFF;
}

.payment-method-tile-icon-crypto {
    background: rgba(var(--color-primary-rgb), 0.12);
    color: var(--color-primary-dark);
}

.payment-method-tile-title {
    display: block;
    font-size: var(--text-sm);
    font-weight: var(--font-bold);
    color: var(--color-text-primary);
    line-height: 1.2;
}

.payment-method-tile-sub {
    display: block;
    font-size: var(--text-xs);
    color: var(--color-text-secondary);
    margin-top: 2px;
}

.payment-method-tile-check {
    position: absolute;
    top: 6px;
    right: 6px;
}

.payment-action-area {
    margin-top: var(--space-4);
}

.payment-action-hint {
    display: flex;
    align-items: flex-start;
    justify-content: center;
    gap: var(--space-2);
    font-size: var(--text-xs);
    color: var(--color-text-muted);
    background: var(--color-surface-tertiary);
    border-radius: var(--radius-md);
    padding: var(--space-2) var(--space-3);
    margin: var(--space-3) 0 0;
    line-height: 1.5;
    text-align: left;
}

.payment-action-hint :deep(.v-icon) {
    flex-shrink: 0;
    margin-top: 2px;
}

.payment-action-hint-text {
    min-width: 0;
}

.payment-action-hint strong {
    color: var(--color-text-primary);
    font-weight: var(--font-semibold);
    white-space: nowrap;
}

.payment-wallet-label {
    font-size: var(--text-xs);
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--color-text-muted);
    font-weight: var(--font-semibold);
    margin: 0 0 var(--space-2);
}

.payment-wallet-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(110px, 1fr));
    gap: var(--space-2);
}

.payment-wallet-tile {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: var(--space-2);
    padding: var(--space-3) var(--space-2);
    background: var(--color-surface);
    border: 1.5px solid var(--color-surface-border);
    border-radius: var(--radius-lg);
    cursor: pointer;
    transition: all var(--transition-fast);
    color: var(--color-text-primary);
    font: inherit;
}

.payment-wallet-tile:hover:not(:disabled) {
    border-color: var(--color-primary);
    background: rgba(var(--color-primary-rgb), 0.04);
    transform: translateY(-1px);
}

.payment-wallet-tile:disabled {
    opacity: 0.5;
    cursor: not-allowed;
}

.payment-wallet-tile:focus-visible {
    outline: none;
    border-color: var(--color-primary);
    box-shadow: 0 0 0 3px rgba(var(--color-primary-rgb), 0.15);
}

.payment-wallet-icon {
    width: 40px;
    height: 40px;
    object-fit: contain;
}

.payment-wallet-name {
    font-size: var(--text-xs);
    font-weight: var(--font-semibold);
    color: var(--color-text-primary);
    text-align: center;
    line-height: 1.2;
}

/* Responsive */
@media (max-width: 959px) {
    .invoice-header-inner {
        padding: var(--space-4);
    }

    .invoice-header-avatar {
        width: 44px;
        height: 44px;
    }

    .invoice-header-business {
        font-size: var(--text-lg);
    }

    .section-header,
    .section-body,
    .section-body-tight {
        padding-left: var(--space-4);
        padding-right: var(--space-4);
    }

    .detail-grid {
        grid-template-columns: 1fr 1fr;
        gap: var(--space-4);
    }

    .detail-grid > .detail-block:first-child {
        grid-column: 1 / -1;
    }

    .address-grid {
        grid-template-columns: 1fr;
        gap: var(--space-4);
    }

    .summary-wrap {
        justify-content: stretch;
    }

    .summary-rows {
        max-width: none;
    }

    .payment-sticky {
        position: static;
    }
}

@media (max-width: 600px) {
    .invoice-header-inner {
        flex-direction: column;
        align-items: flex-start;
    }

    .invoice-header-right {
        width: 100%;
        justify-content: space-between;
    }

    .invoice-header-ref {
        align-items: flex-start;
    }

    .detail-grid {
        grid-template-columns: 1fr;
        gap: var(--space-3);
    }

    .detail-grid > .detail-block:first-child {
        grid-column: auto;
    }

    .payment-card-total-amount {
        font-size: var(--text-2xl);
    }

    .payment-method-grid {
        grid-template-columns: 1fr;
    }
}

@media print {
    .no-print {
        display: none !important;
    }
}
</style>
