<x-mail::message>
# Introduction

The body of your message.

<x-mail::button :url="''">
Button Text
</x-mail::button>



Thanks,<br>
{{ config('app.name') }}
</x-mail::message>

<x-mail::panel>
    This is the panel content.
</x-mail::panel>


{{--<x-mail::message>--}}
{{--    # Order Shipped--}}

{{--    Your order has been shipped!--}}

{{--    <x-mail::button :url="$url">--}}
{{--        View Order--}}
{{--    </x-mail::button>--}}

{{--    Thanks,<br>--}}
{{--    {{ config('app.name') }}--}}
{{--</x-mail::message>--}}
