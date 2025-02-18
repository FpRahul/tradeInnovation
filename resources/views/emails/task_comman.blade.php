<table width="100%" border="0" cellspacing="0" cellpadding="0" style="font-family: 'Inter'; background-color: #f4f4f9; padding: 20px;">
    <tr>
        <td align="center">
            <table width="600" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); overflow: hidden;">
                <tr>
                    <td style="background: url('assets/images/login-bg.jpg') center/cover no-repeat; padding: 60px 30px; text-align: center;">
                        <img src="https://futureprofilez.com/wp-content/themes/fptheme/assets2/img/logo.png?dshf" alt="Your Logo" style="max-width: 200px; margin-bottom: 10px;">
                        <h1 style="color: #ffffff; font-size: 26px; margin: 0; font-weight: bold;">Welcome to Our Platform!</h1>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 40px 20px; text-align: left;">
                        <p style="font-size: 16px; line-height: 1.8; margin: 0 0 10px; color: #555;">
                            Hello <strong>{{ $clientName }}</strong>,
                        </p>
                        <p style="font-size: 16px; line-height: 1.8; margin: 0 0 20px; color: #555;"> 
                            Your service request for <strong>{{ $service }}</strong> has been processed. Below are the details of your service and the associated pricing:
                        </p>
                        <table width="100%" border="0" cellspacing="0" cellpadding="10" style="border-collapse: collapse; border: 1px solid #ddd; background-color: #fafafa;">
                            <tr>
                                <td width="50%" style="font-size: 14px; font-weight: bold; color: #333; background-color: #f0f0f0; border-bottom: 1px solid #ddd;">Service:</td>
                                <td width="50%" style="font-size: 14px; color: #555; border-bottom: 1px solid #ddd;">{{ $service }}</td>
                            </tr>
                            <tr>
                                <td width="50%" style="font-size: 14px; font-weight: bold; color: #333; background-color: #f0f0f0; border-bottom: 1px solid #ddd;">Service Price:</td>
                                <td width="50%" style="font-size: 14px; color: #555; border-bottom: 1px solid #ddd;">₹{{ number_format($service_price, 2) }}</td>
                            </tr>
                            <tr>
                                <td width="50%" style="font-size: 14px; font-weight: bold; color: #333; background-color: #f0f0f0; border-bottom: 1px solid #ddd;">Government Price:</td>
                                <td width="50%" style="font-size: 14px; color: #555; border-bottom: 1px solid #ddd;">₹{{ number_format($govt_price, 2) }}</td>
                            </tr>
                        </table>
                        <p style="font-size: 16px; text-align: center; line-height: 1.2; margin: 35px 0 0; color: #555;">
                            Thank you for choosing us! <br>
                        </p>
                        <p style="text-align: center; margin: 15px 0 0;">
                            Best regards,<br>
                            The Support Team
                        </p>
                    </td>
                </tr>
                <!-- Footer Section -->
                <tr>
                    <td style="background-color: #1c194d; text-align: center; padding: 15px; font-size: 12px; color: #ffffff;">
                        © 2025 Your Company Name. All rights reserved. 
                        <a href="https://yourcompany.com" style="color: #fff; text-decoration: none;">Visit Our Website</a>
                    </td>
                </tr>
            </table>
        </td>
    </tr>
</table>
