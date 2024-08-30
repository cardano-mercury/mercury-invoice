@component('mail::message')
# {{ $isReminder ? 'REMINDER: ' : '' }}New Invoice {{ $invoiceReference }}

Hello {{ $recipientName }},

<strong>{{ $issuerUsername }}</strong> has issued a new invoice dated <strong>{{ $issueDate }}</strong>,
which is due on <strong>{{ $dueDate }}</strong> ({{ $dueDateDiff }}).

<x-mail::button :url="$invoiceViewerUrl">
    View / Pay Invoice Online
</x-mail::button>

@component('mail::table')
    | Items      | Amounts ({{ $currency }}) |
    | :-------: | :-------------: |
    | Subtotal  | {{ $subTotal }} |
    | Total Tax | {{ $totalTax }} |
    | Total Due | {{ $totalDue }} |
@endcomponent

@component('mail::panel')
    Please note, this is an automated email.
    If you need assistance with this invoice, please contact invoice issuer via <a href="mailto:{{ $issuerEmail }}">{{ $issuerEmail }}</a> email address.
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
