@component('mail::message')
# Hello {{ $name ?: 'Friend' }},

Thank you for registering with **Happylife**.  
Your verification code is:

@component('mail::panel')
## {{ $code }}
@endcomponent

This code will expire in 15 minutes.  
If you did not request this code, please ignore this email.

Thanks,<br>
Happylife Team
@endcomponent
