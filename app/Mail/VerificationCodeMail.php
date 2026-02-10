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
    public function __construct($verificationCode, $name)
    {
        $this->verificationCode = $verificationCode;
        $this->name = $name;
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
        <body style="margin: 0; padding: 0; font-family: \'Poppins\', Arial, sans-serif; background-color: #f7f9fc; color: #333333;">
            
            <!-- Email Container -->
            <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#f7f9fc">
                <tr>
                    <td align="center">
                        
                        <!-- Main Content Table -->
                        <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 0 auto;">
                            
                            <!-- Header Section -->
                            <tr>
                                <td style="padding: 40px 30px 30px; text-align: center;">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="background: linear-gradient(135deg, #E63323 0%, #d6281a 100%); padding: 25px; border-radius: 12px 12px 0 0; box-shadow: 0 4px 20px rgba(230, 51, 35, 0.2);">
                                                <h1 style="margin: 0; color: #ffffff; font-size: 28px; font-weight: 700; letter-spacing: 0.5px;">
                                                    Happylife Multipurpose
                                                </h1>
                                                <p style="margin: 10px 0 0; color: rgba(255, 255, 255, 0.9); font-size: 16px; font-weight: 400;">
                                                    International
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                            <!-- Body Section -->
                            <tr>
                                <td style="padding: 0 30px 30px;">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0" bgcolor="#ffffff" style="border-radius: 0 0 12px 12px; box-shadow: 0 6px 25px rgba(0, 0, 0, 0.08);">
                                        
                                        <!-- Welcome Message -->
                                        <tr>
                                            <td style="padding: 40px 40px 20px;">
                                                <h2 style="margin: 0; color: #333333; font-size: 24px; font-weight: 600; line-height: 1.4;">
                                                    Welcome, ' . htmlspecialchars($this->name) . '!
                                                </h2>
                                                <p style="margin: 15px 0 0; color: #666666; font-size: 16px; line-height: 1.6;">
                                                    Thank you for joining Happylife Multipurpose International. To complete your registration and start your journey with us, please verify your email address using the code below.
                                                </p>
                                            </td>
                                        </tr>
                                        
                                        <!-- Verification Code Box -->
                                        <tr>
                                            <td style="padding: 0 40px 30px;">
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td align="center">
                                                            <div style="background: linear-gradient(135deg, #1FA3C4 0%, #3DB7D6 100%); padding: 30px; border-radius: 12px; text-align: center; box-shadow: 0 4px 15px rgba(31, 163, 196, 0.2);">
                                                                <p style="margin: 0 0 15px; color: rgba(255, 255, 255, 0.9); font-size: 16px; font-weight: 500;">
                                                                    Your Verification Code
                                                                </p>
                                                                <div style="background: #ffffff; padding: 20px; border-radius: 8px; display: inline-block; min-width: 200px;">
                                                                    <div style="letter-spacing: 10px; font-size: 32px; font-weight: 700; color: #E63323; text-align: center;">
                                                                        ' . $this->verificationCode . '
                                                                    </div>
                                                                </div>
                                                                <p style="margin: 20px 0 0; color: rgba(255, 255, 255, 0.8); font-size: 14px;">
                                                                    This code expires in 30 minutes
                                                                </p>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        
                                        <!-- Instructions -->
                                        <tr>
                                            <td style="padding: 0 40px 40px;">
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td>
                                                            <div style="background-color: #f8f9fa; padding: 25px; border-radius: 8px; border-left: 4px solid #E63323;">
                                                                <h3 style="margin: 0 0 15px; color: #333333; font-size: 18px; font-weight: 600;">
                                                                    <span style="color: #E63323;">📋</span> How to Use This Code
                                                                </h3>
                                                                <ol style="margin: 0; padding-left: 20px; color: #555555; font-size: 15px; line-height: 1.8;">
                                                                    <li>Go back to the verification page</li>
                                                                    <li>Enter the 6-digit code above</li>
                                                                    <li>Click on "Verify Email" button</li>
                                                                    <li>Complete your account setup</li>
                                                                </ol>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        
                                        <!-- Security Notice -->
                                        <tr>
                                            <td style="padding: 0 40px 40px;">
                                                <div style="border-top: 1px solid #E6E6E6; padding-top: 30px;">
                                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                        <tr>
                                                            <td width="60" valign="top" style="padding-right: 15px;">
                                                                <div style="background-color: #E63323; width: 48px; height: 48px; border-radius: 50%; display: flex; align-items: center; justify-content: center;">
                                                                    <span style="color: #ffffff; font-size: 20px;">🔒</span>
                                                                </div>
                                                            </td>
                                                            <td valign="top">
                                                                <h4 style="margin: 0 0 8px; color: #333333; font-size: 17px; font-weight: 600;">
                                                                    Security Notice
                                                                </h4>
                                                                <p style="margin: 0; color: #666666; font-size: 14px; line-height: 1.6;">
                                                                    For your security, never share this code with anyone. Happylife staff will never ask for your verification code. If you didn\'t request this email, please ignore it or contact our support team immediately.
                                                                </p>
                                                            </td>
                                                        </tr>
                                                    </table>
                                                </div>
                                            </td>
                                        </tr>
                                        
                                        <!-- Call to Action -->
                                        <tr>
                                            <td style="padding: 0 40px 40px;">
                                                <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                                    <tr>
                                                        <td align="center">
                                                            <a href="#" style="display: inline-block; background: linear-gradient(135deg, #E63323 0%, #d6281a 100%); color: #ffffff; text-decoration: none; font-weight: 600; font-size: 16px; padding: 16px 40px; border-radius: 8px; box-shadow: 0 4px 12px rgba(230, 51, 35, 0.3); transition: all 0.3s ease;">
                                                                Return to Verification Page
                                                            </a>
                                                            <p style="margin: 20px 0 0; color: #888888; font-size: 14px;">
                                                                Having trouble? <a href="mailto:support@happylife.com" style="color: #1FA3C4; text-decoration: none; font-weight: 500;">Contact Support</a>
                                                            </p>
                                                        </td>
                                                    </tr>
                                                </table>
                                            </td>
                                        </tr>
                                        
                                    </table>
                                </td>
                            </tr>
                            
                            <!-- Footer Section -->
                            <tr>
                                <td style="padding: 0 30px 40px;">
                                    <table width="100%" cellpadding="0" cellspacing="0" border="0">
                                        <tr>
                                            <td style="text-align: center; padding: 30px 0; border-top: 1px solid #E6E6E6;">
                                                <!-- Company Logo/Name -->
                                                <h3 style="margin: 0 0 15px; color: #333333; font-size: 20px; font-weight: 700;">
                                                    Happylife Multipurpose International
                                                </h3>
                                                
                                                <!-- Contact Info -->
                                                <p style="margin: 0 0 20px; color: #666666; font-size: 14px; line-height: 1.6;">
                                                    Building Successful Entrepreneurs Worldwide<br>
                                                    <a href="mailto:support@happylife.com" style="color: #1FA3C4; text-decoration: none;">support@happylife.com</a> | 
                                                    <a href="tel:+2341234567890" style="color: #1FA3C4; text-decoration: none;">+234 123 456 7890</a>
                                                </p>
                                                
                                                <!-- Social Media -->
                                                <table align="center" cellpadding="0" cellspacing="0" border="0" style="margin: 0 auto 25px;">
                                                    <tr>
                                                        <td style="padding: 0 10px;">
                                                            <a href="#" style="display: inline-block; width: 40px; height: 40px; background-color: #1FA3C4; border-radius: 50%; text-align: center; line-height: 40px; color: #ffffff; text-decoration: none;">
                                                                <span style="font-size: 18px;">f</span>
                                                            </a>
                                                        </td>
                                                        <td style="padding: 0 10px;">
                                                            <a href="#" style="display: inline-block; width: 40px; height: 40px; background-color: #1FA3C4; border-radius: 50%; text-align: center; line-height: 40px; color: #ffffff; text-decoration: none;">
                                                                <span style="font-size: 18px;">in</span>
                                                            </a>
                                                        </td>
                                                        <td style="padding: 0 10px;">
                                                            <a href="#" style="display: inline-block; width: 40px; height: 40px; background-color: #1FA3C4; border-radius: 50%; text-align: center; line-height: 40px; color: #ffffff; text-decoration: none;">
                                                                <span style="font-size: 18px;">t</span>
                                                            </a>
                                                        </td>
                                                        <td style="padding: 0 10px;">
                                                            <a href="#" style="display: inline-block; width: 40px; height: 40px; background-color: #1FA3C4; border-radius: 50%; text-align: center; line-height: 40px; color: #ffffff; text-decoration: none;">
                                                                <span style="font-size: 18px;">ig</span>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                </table>
                                                
                                                <!-- Copyright -->
                                                <p style="margin: 0; color: #888888; font-size: 13px; line-height: 1.5;">
                                                    © ' . $currentYear . ' Happylife Multipurpose International. All rights reserved.<br>
                                                    <span style="font-size: 12px;">This email was sent to you as part of your account registration process.</span>
                                                </p>
                                            </td>
                                        </tr>
                                    </table>
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