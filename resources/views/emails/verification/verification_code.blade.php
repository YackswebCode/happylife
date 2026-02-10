<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
</head>
<body style="margin: 0; padding: 20px; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; line-height: 1.6; color: #333333; background-color: #f8f8f8;">
    <!-- Main Email Container -->
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 10px; overflow: hidden; box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);">
        <!-- Header Section -->
        <tr>
            <td style="background-color: #E63323; color: white; padding: 30px 40px; text-align: center;">
                <div style="font-size: 32px; font-weight: 700; margin-bottom: 15px; display: inline-block;">BRANDLOGO</div>
                <h1 style="font-size: 28px; font-weight: 600; margin: 0 0 10px 0;">Email Verification</h1>
                <p style="margin: 0; font-size: 16px;">Secure your account with verification</p>
            </td>
        </tr>
        
        <!-- Content Section -->
        <tr>
            <td style="padding: 40px;">
                <!-- Greeting -->
                <p style="font-size: 20px; margin: 0 0 25px 0; color: #333333;">Hello {{ $name }},</p>
                
                <!-- Instructions -->
                <p style="margin: 0 0 30px 0; font-size: 16px; color: #333333;">
                    You're receiving this email because you requested to verify your email address for your account. 
                    Please use the verification code below to complete the process.
                </p>
                
                <!-- Verification Code Box -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #FFF5F5; border-left: 5px solid #E63323; padding: 25px; margin: 30px 0; border-radius: 0 8px 8px 0;">
                    <tr>
                        <td style="text-align: center; padding-bottom: 15px;">
                            <p style="margin: 0; font-size: 16px;">Your verification code is:</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding: 15px 0;">
                            <div style="font-size: 42px; font-weight: 700; letter-spacing: 8px; color: #E63323; margin: 15px 0; font-family: monospace;">{{ $verificationCode }}</div>
                        </td>
                    </tr>
                    <tr>
                        <td style="text-align: center; padding-top: 15px;">
                            <p style="margin: 0; color: #666; font-size: 14px;">This code will expire in 15 minutes</p>
                        </td>
                    </tr>
                </table>
                
                <!-- Additional Instructions -->
                <p style="margin: 0 0 30px 0; font-size: 16px; color: #333333;">
                    Enter this code on the verification page to confirm your email address. 
                    If you didn't request this code, you can safely ignore this email.
                </p>
                
                <!-- Verify Button -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 30px 0; text-align: center;">
                    <tr>
                        <td>
                            <a href="#" style="display: inline-block; background-color: #E63323; color: white; text-decoration: none; padding: 14px 30px; border-radius: 6px; font-weight: 600; font-size: 16px; margin-top: 10px; transition: background-color 0.3s;">Verify Email Address</a>
                        </td>
                    </tr>
                </table>
                
                <!-- Divider -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 35px 0;">
                    <tr>
                        <td style="height: 1px; background-color: #E6E6E6;"></td>
                    </tr>
                </table>
                
                <!-- Help Text -->
                <p style="font-size: 14px; color: #666; margin: 0;">
                    <strong>Need help?</strong> If you're having trouble with the verification code, 
                    you can also click the button above to be taken directly to the verification page.
                </p>
            </td>
        </tr>
        
        <!-- Footer Section -->
        <tr>
            <td style="padding: 25px 40px; background-color: #f9f9f9; color: #666666; font-size: 14px; text-align: center;">
                <p style="margin: 0;">This email was sent to you as part of your account security at BrandName.</p>
                
                <!-- Footer Links -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 20px 0; text-align: center;">
                    <tr>
                        <td>
                            <a href="#" style="color: #1FA3C4; text-decoration: none; margin: 0 10px;">Help Center</a>
                            <a href="#" style="color: #1FA3C4; text-decoration: none; margin: 0 10px;">Privacy Policy</a>
                            <a href="#" style="color: #1FA3C4; text-decoration: none; margin: 0 10px;">Contact Us</a>
                        </td>
                    </tr>
                </table>
                
                <!-- Copyright -->
                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin: 20px 0 0 0;">
                    <tr>
                        <td style="font-size: 13px; color: #888888;">
                            &copy; 2023 BrandName. All rights reserved.<br>
                            123 Business Ave, Suite 100, San Francisco, CA 94107
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>