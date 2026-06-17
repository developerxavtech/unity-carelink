<x-mail::message>
# Welcome to Unity CareLink, {{ $name }}!

Thanks for joining the pilot program. Your account has been created and is ready to use.

<x-mail::panel>
**Email:** {{ $email }}<br>
**Temporary password:** {{ $temporaryPassword }}
</x-mail::panel>

We also sent this temporary password by text message. Please log in and change your password from your profile settings as soon as you can.

<x-mail::button :url="$loginUrl">
Log In to Your Account
</x-mail::button>

If you didn't request this, you can safely ignore this email.

Thanks,<br>
The Unity CareLink Team
</x-mail::message>
