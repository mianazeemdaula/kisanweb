<x-mail::message>
# Account Deletion

Your account deletion link is below. Click on the link to delete your account.

<x-mail::button :url="url('/delete-account/'.$code)">
Delete Account
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
