
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Invoice</title>
</head>

<body style="margin:0; padding:0; background-color:#f4f4f4; font-family:Arial, sans-serif;">
    <div style="width:100%; margin:0; padding:0;">
        <div style="width:100%; background-color:#ffffff;">
            <div style="padding:20px;">

                <!-- Header: Logo Left, Address Right -->
                <div
                    style="display:flex; justify-content:space-between; align-items:center; border-bottom:2px solid #6c757d; padding-bottom:15px;">
                    <!-- Logo (Left) -->
                    <div style="flex:1; text-align:left;">
                        <img src="placeholder_logo.png" alt="Company Logo" style="width:100px; height:auto;" />
                    </div>
                    <!-- Address (Right) -->
                    <div style="flex:1; text-align:right;">
                        <p style="font-weight:bold; margin-bottom:0; color:#343a40; font-size:20px;">Company Name</p>
                        <p style="margin:0; color:#495057;">
                            123 Main Street, City, Country<br>
                            <strong>Legal Registration No:</strong> 123345
                        </p>
                        <p style="font-weight:bold; margin-bottom:0; color:#495057;">
                            email@example.com<br>
                            +1 (555) 123-4567
                        </p>
                    </div>
                </div>

                <!-- Customer & Invoice Info -->
                <div style="margin-top:20px; display:flex; justify-content:space-between; flex-wrap:wrap;">
                    <!-- Customer Info -->
                    <div style="flex:1; min-width:250px; margin-bottom:10px;">
                        <h3 style="margin:0 0 8px 0; color:#343a40;">Customer Information</h3>
                        <ul style="list-style:none; margin:0; padding:0; color:#495057;">
                            <li><strong>Name:</strong> John Doe</li>
                            <li><strong>Phone:</strong> +1 (555) 987-6543</li>
                            <li><strong>Email:</strong> john.doe@example.com</li>
                        </ul>
                    </div>

                    <!-- Invoice Info -->
                    <div style="flex:1; min-width:250px; text-align:right; margin-bottom:10px;">
                        <ul style="list-style:none; margin:0; padding:0; color:#495057;">
                            <li><strong>Invoice No.:</strong> #INV-2025-001</li>
                            <li><strong>Invoice Date:</strong> February 15, 2025</li>
                            <li><strong>Due Date:</strong> February 22, 2025</li>
                        </ul>
                    </div>
                </div>

                <!-- Table of Products -->
                <div style="margin-top:20px; width:100%; overflow-x:auto;">
                    <table style="width:100%; border:1px solid #dee2e6; border-collapse:collapse; color:#212529;">
                        <thead>
                            <tr style="background-color:#e9ecef;">
                                <th style="padding:10px; border:1px solid #dee2e6; text-align:left;">Product Description
                                </th>
                                <th style="padding:10px; border:1px solid #dee2e6; text-align:left;">Quantity</th>
                                <th style="padding:10px; border:1px solid #dee2e6; text-align:left;">Unit Price (GHS)
                                </th>
                                <th style="padding:10px; border:1px solid #dee2e6; text-align:left;">Amount (GHS)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Example Product 1 -->
                            <tr>
                                <td style="padding:10px; border:1px solid #dee2e6;">Product 1</td>
                                <td style="padding:10px; border:1px solid #dee2e6;">2</td>
                                <td style="padding:10px; border:1px solid #dee2e6;">50.00</td>
                                <td style="padding:10px; border:1px solid #dee2e6;">100.00</td>
                            </tr>
                            <!-- Example Product 2 -->
                            <tr>
                                <td style="padding:10px; border:1px solid #dee2e6;">Product 2</td>
                                <td style="padding:10px; border:1px solid #dee2e6;">1</td>
                                <td style="padding:10px; border:1px solid #dee2e6;">120.00</td>
                                <td style="padding:10px; border:1px solid #dee2e6;">120.00</td>
                            </tr>
                            <!-- Total Row -->
                            <tr>
                                <td colspan="2" style="border:1px solid #dee2e6;">&nbsp;</td>
                                <td style="padding:10px; border:1px solid #dee2e6; font-weight:bold;">Grand Total</td>
                                <td style="padding:10px; border:1px solid #dee2e6; font-weight:bold;">220.00</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Payment Info, Payment Terms & Thank You Note -->
                <div style="margin-top:20px; border-top:2px solid #6c757d; padding-top:10px;">
                    <h4 style="margin:0 0 8px 0; color:#343a40;">Payment Information</h4>
                    <p style="margin:0 0 10px 0; line-height:1.6; color:#495057; font-style:italic;">
                        <strong>Accepted Methods:</strong> Cash, Credit Card, Bank Transfer<br>
                        <strong>Bank Name:</strong> Example Bank<br>
                        <strong>Account Number:</strong> 123456789<br>
                        <strong>SWIFT Code:</strong> EXAMP123
                    </p>
                    <h4 style="margin:0 0 8px 0; color:#343a40;">Payment Terms</h4>
                    <p style="margin:0 0 10px 0; line-height:1.6; color:#495057; font-style:italic;">
                        Payment is due within 7 days of the invoice date. Late payments may incur additional fees as
                        outlined in our terms and conditions.
                    </p>
                    <h4 style="margin:0 0 8px 0; color:#343a40;">Thank You!</h4>
                    <p style="margin:0; line-height:1.6; color:#495057;">
                        We appreciate your business. If you have any questions, please contact us at
                        <strong>email@example.com</strong> or call <strong>+1 (555) 123-4567</strong>.
                    </p>
                </div>

            </div>
        </div>
    </div>
</body>

</html>
