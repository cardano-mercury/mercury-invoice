@component('mail::message')

# Invoice {{ $invoiceReference }} Paid

@if ($isUserNotification)
Dear {{ $userName  }},

Your customer {{ $customerName }} has paid **{{ round($invoicePayment->payment_amount, 2) }} {{ $invoiceCurrency }}**<br>
on {{ $invoicePayment->payment_date->toDateString() }} via **{{ $invoicePayment->payment_method }}** payment method.

Payment Reference: **{{ $invoicePayment->payment_reference }}**
@else
Dear {{ $recipientName }},

Thank you for invoice payment of **{{ round($invoicePayment->payment_amount, 2) }} {{ $invoiceCurrency }}**<br>
on {{ $invoicePayment->payment_date->toDateString() }} via **{{ $invoicePayment->payment_method }}** payment method.

Payment Reference: **{{ $invoicePayment->payment_reference }}**
@endif

@if (!empty($txExplorerUrl))
<table>
    <tr>
        <td><small>Exchange Rate:</small></td>
        <td><small><strong>{{ $invoicePayment->crypto_asset_ada_price }}</strong> ({{ $invoiceCurrency }} ➡️ ₳DA)</small></td>
    </tr>
    <tr>
        <td><small>Total ₳DA Paid:</small></td>
        <td><small><strong>{{ $invoicePayment->crypto_asset_quantity }} ₳DA</strong></small></td>
    </tr>
    <tr>
        <td><small>Wallet Used:</small></td>
        <td><small><strong>{{ $invoicePayment->crypto_wallet_name }}</strong></small></td>
    </tr>
</table>
@endif

<table style="display: flex; justify-content: center;">
    <tr>
        <td>
            <x-mail::button :url="$invoiceViewerUrl">
                View Invoice Online
            </x-mail::button>
        </td>
        @if (!empty($txExplorerUrl))
        <td>&nbsp;</td>
        <td>
            <x-mail::button :url="$txExplorerUrl">
                View Payment Transaction
            </x-mail::button>
        </td>
        @endif
    </tr>
</table>

@component('mail::panel')
    Please note, this is an automated email.
    @if (!$isUserNotification)
    If you need assistance with this invoice, please contact invoice issuer {{ $userName }} via <a href="mailto:{{ $userEmail }}">{{ $userEmail }}</a> email address.
    @endif
@endcomponent

Thank you for using Cardano Mercury: Invoice

@endcomponent
