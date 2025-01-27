<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: 'Inter'; background-color: #f4f4f9; padding: 20px;">
    <tr>
        <td align="center">
            <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); overflow: hidden;">
                
                <!-- Header Section with Background Image -->
                <tr>
                    <td style="background: url('('login-bg.jpg')') center/cover no-repeat; padding: 60px 30px; text-align: enter;">
                        <img src="{{ asset('logo-w.png') }}" alt="Your Logo" style="max-width: 200px; margin-bottom: 10px;">
                        <h1 style="color: #ffffff; font-size: 26px; margin: 0; font-weight: bold;">Welcome to Our Platform!</h1>
                    </td>
                </tr>
                
                <!-- Body Section with Token -->
                <tr>
                    <td style="padding: 40px 20px; text-align: left;">
                        <p style="font-size: 16px; line-height: 1.8; margin: 0 0 10px; color: #555;">
                            Hi <strong>{{ $updatePass->name }}</strong>,
                        </p>
                        <p style="font-size: 16px; line-height: 1.8; margin: 0 0 20px; color: #555;">
                            Below is your new password :
                        </p>
                        <p  style="color: #007bff; text-decoration: underline; padding: 10px; border-radius: 5px;">
                            <p style="margin: 0;">{{ $newPass}}</p>
                        </p>

                    </td>
                </tr>
                
                <!-- Footer Section -->
                <tr>
                    <td style="padding: 20px; text-align: center; background-color: #f4f4f9; font-size: 14px; color: #888;">
                        <p style="margin: 0;">If you have any questions, feel free to reach out to our support team.</p>
                        <p style="margin: 0;">&copy; 2025 Your Company. All rights reserved.</p>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
