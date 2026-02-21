<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $code;
    public $name;

    /**
     * Create a new message instance.
     */
    public function __construct($code, $name)
    {
        $this->code = $code;
        $this->name = $name;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Password Reset Code - Happylife Multipurpose Int\'l')
                    ->html($this->getEmailTemplate());
    }

    /**
     * HTML email template (same style as your verification mail)
     */
    private function getEmailTemplate()
    {
        $currentYear = date('Y');

        return <<<HTML
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <title>Password Reset Code</title>
            <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
        </head>
        <body style="margin: 0; padding: 0; font-family: 'Poppins', Arial, sans-serif; background-color: #f7f9fc; color: #333333;">
            <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f7f9fc">
                <tr><td align="center">
                    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px;">
                        <!-- Header -->
                        <tr><td style="padding: 40px 30px 30px; text-align: center;">
                            <table width="100%"><tr><td style="background: linear-gradient(135deg, #E63323 0%, #d6281a 100%); padding: 25px; border-radius: 12px 12px 0 0;">
                                <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700;">Happylife Multipurpose</h1>
                                <p style="margin: 10px 0 0; color: rgba(255,255,255,0.9); font-size: 16px;">International</p>
                            </td></tr></table>
                        </td></tr>
                        <!-- Body -->
                        <tr><td style="padding: 0 30px 30px;">
                            <table width="100%" bgcolor="#ffffff" style="border-radius: 0 0 12px 12px; box-shadow: 0 6px 25px rgba(0,0,0,0.08);">
                                <tr><td style="padding: 40px 40px 20px;">
                                    <h2 style="margin: 0; color: #333; font-size: 24px; font-weight: 600;">Hello, {$this->name}!</h2>
                                    <p style="margin: 15px 0 0; color: #666; font-size: 16px; line-height: 1.6;">
                                        We received a request to reset the password for your Happylife account. 
                                        Use the 6-digit code below to proceed. This code will expire in 30 minutes.
                                    </p>
                                </td></tr>
                                <!-- Code Box -->
                                <tr><td style="padding: 0 40px 30px;">
                                    <table width="100%"><tr><td align="center">
                                        <div style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%); padding: 30px; border-radius: 12px;">
                                            <p style="margin: 0 0 15px; color: rgba(255,255,255,0.9); font-size: 16px;">Your Password Reset Code</p>
                                            <div style="background: #ffffff; padding: 20px; border-radius: 8px; display: inline-block;">
                                                <span style="letter-spacing: 10px; font-size: 32px; font-weight: 700; color: #E63323;">{$this->code}</span>
                                            </div>
                                            <p style="margin: 20px 0 0; color: rgba(255,255,255,0.8); font-size: 14px;">Valid for 30 minutes</p>
                                        </div>
                                    </td></tr></table>
                                </td></tr>
                                <!-- Instructions & Security -->
                                <tr><td style="padding: 0 40px 40px;">
                                    <div style="background: #f8f9fa; padding: 25px; border-left: 4px solid #E63323; border-radius: 8px;">
                                        <h3 style="margin: 0 0 15px; color: #333; font-size: 18px;">🔐 How to Reset Your Password</h3>
                                        <ol style="margin: 0; padding-left: 20px; color: #555; font-size: 15px; line-height: 1.8;">
                                            <li>Enter the 6-digit code on the verification page</li>
                                            <li>Create a new strong password</li>
                                            <li>Log in with your new password</li>
                                        </ol>
                                    </div>
                                </td></tr>
                                <tr><td style="padding: 0 40px 40px;">
                                    <div style="border-top: 1px solid #E6E6E6; padding-top: 30px;">
                                        <table><tr>
                                            <td width="60" valign="top" style="padding-right: 15px;">
                                                <div style="background: #E63323; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                    <span style="color: #fff; font-size: 20px;">🔒</span>
                                                </div>
                                            </td>
                                            <td valign="top">
                                                <h4 style="margin: 0 0 8px; color: #333; font-size: 17px;">Security Notice</h4>
                                                <p style="margin: 0; color: #666; font-size: 14px;">
                                                    Never share this code with anyone. Happylife staff will never ask for it. 
                                                    If you didn't request this, please ignore the email or contact support.
                                                </p>
                                            </td>
                                        </tr></table>
                                    </div>
                                </td></tr>
                                <!-- Footer CTA (optional) -->
                                <tr><td style="padding: 0 40px 40px;" align="center">
                                    <p style="margin: 0; color: #888; font-size: 14px;">
                                        Need help? <a href="mailto:support@happylife.com" style="color: #1FA3C4; text-decoration: none;">Contact Support</a>
                                    </p>
                                </td></tr>
                            </table>
                        </td></tr>
                        <!-- Footer -->
                        <tr><td style="padding: 0 30px 40px;">
                            <table width="100%">
                                <tr><td style="text-align: center; padding: 30px 0; border-top: 1px solid #E6E6E6;">
                                    <h3 style="margin: 0 0 15px; color: #333; font-size: 20px;">Happylife Multipurpose International</h3>
                                    <p style="margin: 0 0 20px; color: #666; font-size: 14px;">
                                        <a href="mailto:support@happylife.com" style="color: #1FA3C4;">support@happylife.com</a> | +234 123 456 7890
                                    </p>
                                    <p style="margin: 0; color: #888; font-size: 13px;">
                                        © {$currentYear} Happylife Multipurpose International. All rights reserved.
                                    </p>
                                </td></tr>
                            </table>
                        </td></tr>
                    </table>
                </td></tr>
            </table>
        </body>
        </html>
        HTML;
    }
}