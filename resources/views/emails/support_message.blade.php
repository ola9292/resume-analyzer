<x-mail::message>
# New Support Inquiry

You have received a new message from the support form on your website.

**Details:**
* **Name:** {{ $data['name'] }}
* **Email:** {{ $data['email'] }}

**Message:**
<x-mail::panel>
{{ $data['message'] }}
</x-mail::panel>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>