<?php
$path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
require_once($path . 'wp-load.php');

// Add this function in your theme's functions.php or a custom plugin  

function generate_pdf() {  
    // Check if the form is submitted
    if (isset($_GET['docno'])) 
    {
        $docNo = $_GET['docno'];
        global $wpdb;

        // Query to fetch the invoice numbers within the specified date range for the given branch
        $query = $wpdb->prepare("
            select zu.*, i.order_item_name from zatcadocumentunit zu, wp_woocommerce_order_items i
            where zu.itemNo=i.order_item_id and zu.documentNo = %d", $docNo);
        $results = $wpdb->get_results($query);

        //get results length
        $resultsLength = count($results);

        // foreach loop to print query $results
        $discount = 0;
        $vatAmount = 0;
        $price = 0;
        $amountWithVAT = 0;
        foreach ($results as $result) {
            $discount += $result->discount;
            $vatAmount += $result->vatAmount;
            $price += $result->price;
            $amountWithVAT += $result->amountWithVAT;
            }

        // Include the TCPDF class  
        if ( ! class_exists('TCPDF') ) {  
            require_once('../tcpdf/tcpdf.php'); // Update the path accordingly  
        } 

        // Create new PDF document  
        $pdf = new TCPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', true);

        // Set document information  
        $pdf->SetCreator(PDF_CREATOR);  
        $pdf->SetAuthor('Zatca');  
        $pdf->SetTitle('80mm PDF Document');  


        // Set default monospaced font  
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);  

        // Set margins  
        $pdf->SetMargins(10, 10, 10, true); // left, top, right  

        // Set auto page breaks  
        $pdf->SetAutoPageBreak(TRUE, 5);

        // Set image scale factor  
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);  

        // Add a page with 80mm width  
        $current_height = $pdf->getPageHeight(); // get the current Y position
        $pdf->AddPage('P', array(80, $current_height + ($current_height * 0.4))); // 'P' for portrait, 80mm width, auto height  

        // Set font  
        $pdf->SetFont('aealarabiya', '', 12);
        // how to add image in top center in pdf
        $image = 'https://media.licdn.com/dms/image/C561BAQE52X7uKKfD0Q/company-background_10000/0/1584643971105/al_rajhi_saudi_group_cover?e=2147483647&v=beta&t=IooJKeXEtj4E72ZD4ZmFMZNoxeqE-lhTJp4H76vpajM';
        $pdf->Image(
            $image,  // file name or URL of the image
            10,      // x-coordinate of the image (from the left)
            10,      // y-coordinate of the image (from the top)
            60,      // width of the image in the PDF
            30,      // height of the image in the PDF
            'JPG',   // image format (JPEG, PNG, GIF, etc.)
            '',      // link URL (optional)
            '',      // alt text (optional)
            false,   // http file (true if the image is a URL)
            300,     // DPI (dots per inch) for the image
            '',      // image filter (optional)
            false,   // resize (true to resize the image)
            false,   // isMask (true if the image is a mask)
            0,       // maskImg (image mask, 0 = none)
            false,   // isPngAlpha (true if the image has alpha channel)
            false,   // isImgMask (true if the image is a mask)
            false    // tmp (temporary file, true to delete the image after use)
        );

        // Add text cell
        $pdf->SetXY(10, 40); // set X Y coordinates
        $pdf->Cell(0, 10, 'مؤسسة الراجحي', 0, 1, 'C'); // 0 = no border, 1 = new line, 'C'

        // Add text cell
        $pdf->SetXY(10, 45); // set X Y coordinates
        $pdf->Cell(0, 10, 'Al-Rajhi Est', 0, 1, 'C'); // 0 = no border, 1 = new line, 'C'

        // Add text cell
        $pdf->SetXY(10, 53); // set X Y coordinates
        $pdf->Cell(0, 10, 'هاتف: 0123456789 س.ت.: 225111491', 0, 1, 'C');

        // Add text cell
        $pdf->SetXY(10, 60); // set X Y coordinates
        $pdf->Cell(0, 10, 'الرقم الضريبي: 31042489670000', 0, 1, 'C');

        // Add text cell
        $pdf->SetXY(10, 65); // set X Y coordinates
        $pdf->Cell(0, 10, 'نص أسفل الرأس -عربي', 0, 1, 'C');
        // Add text cell
        $pdf->SetXY(10, 70); // set X Y coordinates
        $pdf->Cell(0, 10, 'نص أسفل الرأس -انجليزي', 0, 1, 'C');

        // Add text cell
        $pdf->SetXY(10, 78); // set X Y coordinates
        $pdf->Cell(0, 10, 'فاتورة ضريبية مبسطة Simplified Tax Invoice', 0, 1, 'C');

        // Add text cell
        $pdf->SetXY(10, 85); // set X Y coordinates
        $pdf->Cell(0, 10, 'فاتورة نقاط البيع', 0, 1, 'C');

        // Add text cell
        $pdf->SetXY(10, 90); // set X Y coordinates
        $pdf->Cell(0, 10, 'الفرع: فرع رئيسي', 0, 1, 'C');

        // Set font  
        $pdf->SetFont('aealarabiya', '', 10);

        // Add text left and center and right in one cell
        $pdf->SetXY(5, 98); // set X Y coordinates
        $pdf->Cell(0, 10, 'Invoice Number:', 0, 1, 'LRC');
        // Add text left and center and right in one cell
        $pdf->SetXY(20, 98); // set X Y coordinates
        $pdf->Cell(0, 10, $docNo, 0, 1, 'C');
        // Add text left and center and right in one cell
        $pdf->SetXY(60, 98); // set X Y coordinates
        $pdf->Cell(0, 10, 'رقم الفاتورة:', 0, 1, 'RLC');

        // Add text left and center and right in one cell
        $pdf->SetXY(5, 105); // set X Y coordinates
        $pdf->Cell(0, 10, 'Invoice Issue Date:', 0, 1, 'LRC');
        
        // Add text left and center and right in one cell
        $pdf->SetXY(52, 105); // set X Y coordinates
        $pdf->Cell(0, 10, 'تاريخ اصدار الفاتورة:', 0, 1, 'RLC');

        // Add text cell
        $pdf->SetXY(10, 110); // set X Y coordinates
        $pdf->Cell(0, 10, '2023-05-14 23:33', 0, 1, 'C');

        // Add text left and center and right in one cell
        $pdf->SetXY(5, 117); // set X Y coordinates
        $pdf->Cell(0, 10, '#2', 0, 1, 'LRC');
        
        // Add text left and center and right in one cell
        $pdf->SetXY(60, 117); // set X Y coordinates
        $pdf->Cell(0, 10, 'الموظف: 1', 0, 1, 'RLC');

         // Add text left and center and right in one cell
         $pdf->SetXY(48, 125); // set X Y coordinates
         $pdf->Cell(0, 10, 'الاسم: محمد على كلاى', 0, 1, 'RLC');

         // Set font  
        $pdf->SetFont('aealarabiya', '', 8);

         // Add text left and center and right in one cell
         $pdf->SetXY(40, 135); // set X Y coordinates
         $pdf->Cell(0, 10, 'Nature of goods or services', 0, 1, 'RLC');

         // Add text left and center and right in one cell
         $pdf->SetXY(50, 140); // set X Y coordinates
         $pdf->Cell(0, 10, 'تفاصيل السلع أو الخدمات', 0, 1, 'RLC');

         // Set the line style for a dashed line  
        $style = array(  
            'width' => 0.3, // Line width  
            'cap' => 'round', // Line cap  
            'join' => 'round', // Line join  
            'dash' => '3,3', // Dash pattern (3 mm on, 3 mm off)  
            'color' => array(0, 0, 0) // Line color (RGB)  
        );  

         

        $pdf->setLineStyle($style);  

        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(5, 150, 75, 150);

        // Set font  
        $pdf->SetFont('aealarabiya', '', 6);
        // Define column header titles  

        $pdf->Cell(15, 2, 'Item Subtotal (Including VAT)', 0, 0, 'C'); // 0 for no borders
        $pdf->Cell(24, 2, 'VAT', 0, 0, 'C'); // 0 for no borders
        $pdf->Cell(10, 2, 'Quantity', 0, 0, 'C'); // 0 for no borders
        $pdf->Cell(25, 2, 'Unit price', 0, 0, 'C'); // 0 for no borders
        
        $pdf->Ln(); // Line break to start the data rows

        $pdf->Cell(15, 2, 'المجموع -شامل ضريبة القيمة المضافة', 0, 0, 'C'); // 0 for no borders
        $pdf->Cell(24, 2, 'الضريبة', 0, 0, 'C'); // 0 for no borders
        $pdf->Cell(10, 2, 'الكمية', 0, 0, 'C'); // 0 for no borders
        $pdf->Cell(25, 2, 'سعر الوحدة', 0, 0, 'C'); // 0 for no borders

        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(5, 156, 75, 156);
        
        $pdf->Ln(); // Line break to start the data rows

        // foreach loop to print query $results
        foreach ($results as $result) {
            $pdf->Cell(110, 8, $result->order_item_name, 0, 0, 'C');
            $pdf->Ln(); // Line break to start the data rows
              
            $pdf->Cell(15, 4, $result->amountWithVAT, 0, 0, 'C');
            $pdf->Cell(24, 4, $result->vatAmount, 0, 0, 'C');
            $pdf->Cell(10, 4, $result->quantity, 0, 0, 'C');  
            $pdf->Cell(25, 4, $result->price, 0, 0, 'C'); 

            $pdf->Ln(); // Line break after each row 
            }
         
        $y = $pdf->GetY(); // get the current Y position

        // Set the line style for a solid line  
        $solidStyle  = array(  
            'width' => 0.3, // Line width  
            'cap' => 'round', // Line cap  
            'join' => 'round', // Line join  
            'dash' => '0,0', // Dash pattern (3 mm on, 3 mm off)
            'color' => array(0, 0, 0) // Line color (RGB)  
        );

        $pdf->setLineStyle($solidStyle ); 

        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(5, $y + 5, 75, $y + 5);

        $y1 = $pdf->GetY(); // get the current Y position

        // Add text left and center and right in one cell
        $pdf->SetXY(10, $y1 + 5); // set X Y coordinates
        $pdf->Cell(0, 10, '1.00', 0, 1, 'LRC');

        // Add text left and center and right in one cell
        $pdf->SetXY(20, $y1 + 5); // set X Y coordinates
        $pdf->Cell(0, 10, 'م. القطع', 0, 1, 'LRC');

        // Add text left and center and right in one cell
        $pdf->SetXY(45, $y1 + 5); // set X Y coordinates
        $pdf->Cell(0, 10, 'م. الخصم', 0, 1, 'LRC');
        
        // Add text left and center and right in one cell
        $pdf->SetXY(55, $y1 + 5); // set X Y coordinates
        $pdf->Cell(0, 10, $discount.' SAR', 0, 1, 'LRC');

        $y2 = $pdf->GetY(); // get the current Y position

        $pdf->setLineStyle($style); 

        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(5, $y2, 75, $y2);

        $y3 = $pdf->GetY(); // get the current Y position

        // Add text left and center and right in one cell
        $pdf->SetXY(10, $y3); // set X Y coordinates
        $pdf->Cell(0, 10, 'الإجمالي الخاضع للضريبة - غير شاملة ضريبة القيمة المضافة', 0, 1, 'LRC');
        // Add text left and center and right in one cell
        $pdf->SetXY(15, $y3+3); // set X Y coordinates
        $pdf->Cell(0, 10, 'Total Taxable Amount - Excluding VAT', 0, 1, 'LRC');

        // Add text left and center and right in one cell
        $pdf->SetXY(55, $y3+1); // set X Y coordinates
        $pdf->Cell(0, 10, $price.' SAR', 0, 1, 'LRC');

        $y4 = $pdf->GetY(); // get the current Y position

        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(5, $y4, 75, $y4);

        // Add text left and center and right in one cell
        $pdf->SetXY(30, $y4); // set X Y coordinates
        $pdf->Cell(0, 10, 'مجموع ضريبة القيمة المضافة', 0, 1, 'LRC');
        // Add text left and center and right in one cell
        $pdf->SetXY(40, $y4+3); // set X Y coordinates
        $pdf->Cell(0, 10, 'Total VAT', 0, 1, 'LRC');

        // Add text left and center and right in one cell
        $pdf->SetXY(55, $y4+1); // set X Y coordinates
        $pdf->Cell(0, 10, $vatAmount.' SAR', 0, 1, 'LRC');


        $y5 = $pdf->GetY(); // get the current Y position

        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(5, $y5, 75, $y5);

        // Add text left and center and right in one cell
        $pdf->SetXY(27, $y5); // set X Y coordinates
        $pdf->Cell(0, 10, 'إجمالي المبلغ المستحق بعد الضريبة', 0, 1, 'LRC');
        // Add text left and center and right in one cell
        $pdf->SetXY(33, $y5+3); // set X Y coordinates
        $pdf->Cell(0, 10, 'Total Amount Due', 0, 1, 'LRC');

        // Add text left and center and right in one cell
        $pdf->SetXY(55, $y5+1); // set X Y coordinates
        $pdf->Cell(0, 10, $amountWithVAT.' SAR', 0, 1, 'LRC');

        $y6 = $pdf->GetY(); // get the current Y position

        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(5, $y6, 75, $y6);

        // Add text left and center and right in one cell
        $pdf->SetXY(10, $y6); // set X Y coordinates
        $pdf->Cell(0, 10, 'Paid 0.00 المدفوع', 0, 1, 'C');

        $y7 = $pdf->GetY(); // get the current Y position

        $pdf->setLineStyle($solidStyle);
        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(30, $y7, 75, $y7);

        // Add text left and center and right in one cell
        $pdf->SetXY(10, $y7); // set X Y coordinates
        $pdf->Cell(0, 10, 'Left 6.45 المتبقي', 0, 1, 'C');

        $y8 = $pdf->GetY(); // get the current Y position
        $pdf->Line(30, $y8, 75, $y8);

        // Add text left and center and right in one cell
        $pdf->SetXY(10, $y8); // set X Y coordinates
        $pdf->Cell(0, 10, 'تصميم مؤسسة روائع الابتكار للبرمجة', 0, 1, 'C');
        // Add text left and center and right in one cell
        $pdf->SetXY(10, $y8+5); // set X Y coordinates
        $pdf->Cell(0, 10, 'Wasta - برنامج الواسطة', 0, 1, 'C');
        // Add text left and center and right in one cell
        $pdf->SetXY(10, $y8+10); // set X Y coordinates
        $pdf->Cell(0, 10, 'https://www.AppyInnovate.com', 0, 1, 'C');

        // Add text left and center and right in one cell
        $pdf->SetXY(50, $y8 + 20); // set X Y coordinates
        $pdf->Cell(0, 10, 'طرق الدفع: , الرصيد الدائن', 0, 1, 'RLC');

        // Add text left and center and right in one cell
        $pdf->SetXY(50, $y8 + 25); // set X Y coordinates
        $pdf->Cell(0, 10, 'نص أسفل البيانات -عربي', 0, 1, 'RLC');

        $y9 = $pdf->GetY(); // get the current Y position
        $pdf->SetXY(10, $y9); // set X Y coordinates

        // Set the QR code content  
        $qrcodeContent = 'https://www.example.com'; // Example QR code content 

        // Add the QR code to the PDF  
        $pdf->write2DBarcode($qrcodeContent, 'QRCODE,L', '', '', 60, 50, null, 'N');

        // Output PDF document  
        $pdf->Output('zatca_80mm.pdf', 'I'); // D This will trigger a download of the PDF
    }
        
}  

generate_pdf();
