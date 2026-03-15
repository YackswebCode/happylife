<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class VerificationCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $verificationCode;
    public $name;
  

    /**
     * Create a new message instance.
     */
    public function __construct($verificationCode, $name, $username)
    {
        $this->verificationCode = $verificationCode;
        $this->name = $name;
        $this->username = $username;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Verify Your Email - Happylife Multipurpose International')
                    ->html($this->getEmailTemplate());
    }

    /**
     * Generate the email HTML template
     */
    private function getEmailTemplate()
    {
        $currentYear = date('Y');

        return '
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Email Verification</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        </head>
        <body style="margin:0;padding:0;font-family:\'Poppins\',Arial,sans-serif;background:#f7f9fc;color:#333;">

        <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f7f9fc">
        <tr>
        <td align="center">

        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width:600px;margin:0 auto;">

        <!-- Header -->
        <tr>
        <td style="padding:40px 30px 30px;text-align:center;">
        <table width="100%">
        <tr>
        <td style="background:linear-gradient(135deg,#E63323 0%,#d6281a 100%);padding:25px;border-radius:12px 12px 0 0;">
        <h1 style="margin:0;color:#fff;font-size:28px;font-weight:700;">Happylife Multipurpose</h1>
        <p style="margin:8px 0 0;color:#fff;font-size:16px;">International</p>
        </td>
        </tr>
        </table>
        </td>
        </tr>

        <!-- Body -->
        <tr>
        <td style="padding:0 30px 30px;">
        <table width="100%" bgcolor="#ffffff" style="border-radius:0 0 12px 12px;box-shadow:0 6px 25px rgba(0,0,0,0.08);">

        <!-- Welcome -->
        <tr>
        <td style="padding:40px 40px 20px;">
        <h2 style="margin:0;font-size:24px;font-weight:600;">
        Welcome, ' . htmlspecialchars($this->name) . '!
        </h2>

        <p style="margin:15px 0 0;color:#666;font-size:16px;line-height:1.6;">
        Thank you for joining Happylife Multipurpose International.
        Please verify your email address using the code below.
        </p>
        </td>
        </tr>

        <!-- Login Details -->
        <tr>
        <td style="padding:0 40px 30px;">
        <div style="background:#f4f8fb;padding:25px;border-radius:10px;border:1px solid #e6eef5;text-align:center;">

        <h3 style="margin:0 0 15px;font-size:18px;color:#333;">
        Your Login Details
        </h3>

        <p style="margin:8px 0;font-size:15px;">
        <strong>Username:</strong> ' . htmlspecialchars($this->username) . '
        </p>

        <p style="margin:8px 0;font-size:15px;">
        <strong>Password:</strong> The password you created during registration
        </p>

        <p style="margin-top:10px;font-size:13px;color:#777;">
        Use these details to login after verifying your email.
        </p>

        </div>
        </td>
        </tr>

        <!-- Verification Code -->
        <tr>
        <td style="padding:0 40px 30px;text-align:center;">

        <div style="background:linear-gradient(135deg,#1FA3C4 0%,#3DB7D6 100%);padding:30px;border-radius:12px;">

        <p style="margin:0 0 15px;color:#fff;font-size:16px;">
        Your Verification Code
        </p>

        <div style="background:#fff;padding:20px;border-radius:8px;display:inline-block;min-width:200px;">
        <div style="letter-spacing:10px;font-size:32px;font-weight:700;color:#E63323;">
        ' . $this->verificationCode . '
        </div>
        </div>

        <p style="margin:20px 0 0;color:#fff;font-size:14px;">
        This code expires in 30 minutes
        </p>

        </div>

        </td>
        </tr>

        <!-- Instructions -->
        <tr>
        <td style="padding:0 40px 40px;">
        <div style="background:#f8f9fa;padding:25px;border-radius:8px;border-left:4px solid #E63323;">

        <h3 style="margin:0 0 15px;font-size:18px;">
        ðŸ“‹ How to Use This Code
        </h3>

        <ol style="margin:0;padding-left:20px;color:#555;font-size:15px;line-height:1.8;">
        <li>Go back to the verification page</li>
        <li>Enter the 6-digit code above</li>
        <li>Click "Verify Email"</li>
        <li>Complete your account setup</li>
        </ol>

        </div>
        </td>
        </tr>

        <!-- Security -->
        <tr>
        <td style="padding:0 40px 40px;border-top:1px solid #eee;padding-top:30px;">

        <h4 style="margin:0 0 10px;font-size:17px;">
        ðŸ”’ Security Notice
        </h4>

        <p style="margin:0;color:#666;font-size:14px;line-height:1.6;">
        Never share this verification code with anyone.
        Happylife staff will never ask for your verification code.
        If you didn\'t request this email, you can ignore it.
        </p>

        </td>
        </tr>

        <!-- CTA -->
        <tr>
        <td align="center" style="padding-bottom:40px;">

        <a href="' . url('/login') . '" 
        style="display:inline-block;background:#E63323;color:#fff;
        text-decoration:none;font-weight:600;font-size:16px;
        padding:16px 40px;border-radius:8px;">
        Login to Your Account
        </a>

        <p style="margin:20px 0 0;font-size:14px;color:#888;">
        Need help? Contact support
        </p>

        </td>
        </tr>

        </table>
        </td>
        </tr>

        <!-- Footer -->
        <tr>
        <td style="padding:0 30px 40px;text-align:center;border-top:1px solid #eee;padding-top:30px;">

        <h3 style="margin:0 0 10px;font-size:20px;">
        Happylife Multipurpose International
        </h3>

        <p style="margin:0 0 20px;color:#666;font-size:14px;">
        Building Successful Entrepreneurs Worldwide
        </p>

        <p style="margin:0;color:#888;font-size:13px;">
        Â© ' . $currentYear . ' Happylife Multipurpose International.<br>
        All rights reserved.
        </p>

        </td>
        </tr>

        </table>
        </td>
        </tr>
        </table>

        </body>
        </html>
        ';
    }
}