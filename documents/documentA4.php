<?php

$path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
require_once($path . 'wp-load.php');

// Add this function in your theme's functions.php or a custom plugin 

function generate_A4pdf() 
{
    // Check if the form is submitted
    if (isset($_GET['docno'])) 
    {
        $docNo = $_GET['docno'];
        global $wpdb;

        // Query to fetch the invoice numbers within the specified date range for the given branch
        $query = $wpdb->prepare("
            select zu.*, i.order_item_name from zatcaDocumentUnit zu, wp_woocommerce_order_items i
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
        $pdf = new TCPDF('p', 'mm', array(210, 297), true, 'UTF-8', false);  

        // Set document information  
        // Set document information  
        $pdf->SetCreator(PDF_CREATOR);  
        $pdf->SetAuthor('Zatca');  
        $pdf->SetTitle('A4 PDF Document');  
  

        // Set default monospaced font  
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);  

        // Set margins  
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);  

        // Set auto page breaks  
        $pdf->SetAutoPageBreak(TRUE, 10); 
        
        // Set image scale factor  
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO); 

        // Add a page  
        $pdf->AddPage();  
        /**  
         * Custom function to rotate text  
         */  
        function RotatedText($pdf, $x, $y, $txt, $angle) {  
            // Save the current state  
            $pdf->StartTransform();  
            $pdf->Rotate($angle, $x, $y);  
            // Output the text at the specified position  
            $pdf->Text($x, $y, $txt);  
            // Restore the state  
            $pdf->StopTransform();  
        }  

        // Set the watermark text  
        $watermark = 'نسخة غير نهائية';  
        $pdf->SetFont('aealarabiya', 'B', 100);  
        //$pdf->SetTextColor(255, 192, 203); // Light pink color 
        $pdf->SetAlpha(0.3); // Set transparency for the watermark 
        RotatedText($pdf, 45, 200, $watermark, 55); // Add rotated text  

        $pdf->SetAlpha(1); // Set transparency for the watermark
        // Set font  
        $pdf->SetFont('aealarabiya', '', 12);

        // Add text cell
        $pdf->SetXY(15, 10); // set X Y coordinates
        $pdf->Cell(0, 10, 'Nasaem Nada Trading Est', 0, 1, 'L'); // 0 = no border, 1 = new line, 'C'

        $pdf->SetXY(15, 15);
        $pdf->Cell(0,10, 'الرقم الضريبي: 30012345678901', 0, 1, 'L');

        $pdf->Setxy(0, 10);
        $pdf->Cell(0, 10, 'مؤسسة نسائم الندى', 0, 1, 'R');

        $pdf->SetXY(0, 15);
        $pdf->Cell(0,10,'هاتف: 0552491111 س.ت.:11',0,1,'R');

        // left top side
        $pdf->SetXY(15, 30);
        $pdf->Cell(0,10,'#4',0,1,'L');

        $pdf->SetXY(15, 40);
        $pdf->Cell(0,1,'1نص أسفل الرأس -انجليزي',0,1,'L');

        $pdf->SetXY(15, 50);
        $pdf->Cell(0,1,'المخزن: 1 - مباني المؤسسة1',0,1,'L');

        $pdf->SetXY(15, 60);
        $pdf->Cell(0,1,'رقم العميل الضريبي: 12345678',0,1,'L');

        $pdf->SetXY(15, 70);
        $pdf->Cell(0,1,'80',0,1,'L');

        // right top side

        $pdf->SetXY(0, 30);
        $pdf->Cell(0,1,'الفرع: 1 - مباني المؤسسة1',0,1,'R');

        $pdf->SetXY(0, 40);
        $pdf->Cell(0,1,'Egypt',0,1,'R');
        $pdf->SetXY(0, 45);
        $pdf->Cell(0,1,'نص أسفل الرأس -عربي1',0,1,'R');

        $pdf->SetXY(0, 55);
        $pdf->Cell(0,1,'رقم الفاتورة:', 0, 1, 'R');

        $pdf->SetXY($pdf->GetPageWidth() - 200, 55);
        $pdf->Cell(150,1,'452',0,1,'R');

        $pdf->SetXY(115, 55);
        $pdf->Cell(0,1,'Invoice Number:',0,1,'L');

        $pdf->SetXY(177, 65);
        $pdf->Cell(0,1,'الاسم:',0,1,'R');

        $pdf->SetXY($pdf->GetPageWidth() - 200, 65);
        $pdf->Cell(150,1,'testclientnw',0,1,'R');

        $pdf->SetXY(0, 75);
        $pdf->Cell(0,1,'تاريخ اصدار الفاتورة:',0,1,'R');

        $pdf->SetXY($pdf->GetPageWidth() - 200, 75);
        $pdf->Cell(155,1,'2024-05-29 21:01',0,1,'R');

        $pdf->SetXY(90, 75);
        $pdf->Cell(0,1,'Invoice Issue Date:',0,1,'L');

        $pdf->SetXY(0, 85);
        $pdf->Cell(0,1,'فاتورة نقاط بيع',0,1,'C');

        $pdf->SetXY(0, 85);
        $pdf->Cell(0,1,'Tax Invoice ضريبية فاتورة',0,1,'R');

        $pdf->Ln();
        //table
        // Set font  
        $pdf->SetFont('aealarabiya', '', 6);

        // Calculate table width: 50% of the page width  
        $pageWidth = $pdf->getPageWidth();  
        $tableWidth = $pageWidth * 0.42;  

        // Header  
        $pdf->Cell($tableWidth * 0.5, 5, 'Seller', 1,0, 'L');  
        $pdf->Cell($tableWidth * 0.5, 5, 'البائع',1,0,'R');  
        $pdf->Ln(); // Move to the next line

        // Data  
        $pdf->Cell($tableWidth * 0.1, 5, 'Name', 1,0,'L');  
        $pdf->Cell($tableWidth * 0.4, 5, 'Nasaem Nada Trading Est', 1,0,'L');
        $pdf->Cell($tableWidth * 0.4, 5, 'مؤسسة نسائم الندى', 1,0,'R');
        $pdf->Cell($tableWidth * 0.1, 5, 'الاسم', 1,0,'R');
        $pdf->Ln(); // Move to the next line
        
        $pdf->Cell($tableWidth * 0.2, 5, 'Building No', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, '7426000000', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, '7426000000', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'رقم المبنى', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.2, 5, 'Street Name', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, 'street', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, 'شارع الستين', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'اسم الشارع', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.1, 5, 'District', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, 'shehabia222', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, 'الشهابية', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.1, 5, 'الحي', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.1, 5, 'City', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, 'ALHASA 23', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, 'الاحساء', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.1, 5, 'المدينة', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.1, 5 , 'Country', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5 , 'Kingdom Of Saudia Arabia23', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5 , 'المملكة العربية السعودية', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.1, 5 , 'البلد', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.25, 5, 'Postal Code', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, '123450000', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, '123450000', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.25, 5, 'الرمز البريدي', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.2, 5, 'Additional No.', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, '234730062000032323', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, '234730062000032323', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'الرقم الاضافي للعنوان', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.2, 5, 'VAT Number', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, '300123456789012', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, '300123456789012', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'رقم تسجيل الضريبى', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.25, 5, 'Other Seller ID', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, '123523000000', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, '123523000000', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.25, 5, 'معرف آخر', 1, 0, 'R');
        $pdf->Ln();



        // Header  
        $pdf->SetXY(105, 95.5);
        $pdf->Cell($tableWidth * 0.5, 5, 'Buyer', 1,0, 'L');  
        $pdf->Cell($tableWidth * 0.5, 5, 'العميل',1,0,'R');  
        $pdf->Ln(); // Move to the next line

        // Data 
        $pdf->SetXY(105, 100.5); 
        $pdf->Cell($tableWidth * 0.1, 5, 'Name', 1,0,'L');  
        $pdf->Cell($tableWidth * 0.4, 5, 'testclientnwen', 1,0,'L');
        $pdf->Cell($tableWidth * 0.4, 5, 'testclientnw', 1,0,'R');
        $pdf->Cell($tableWidth * 0.1, 5, 'الاسم', 1,0,'R');
        $pdf->Ln(); // Move to the next line

        $pdf->SetXY(105, 105.5);
        $pdf->Cell($tableWidth * 0.2, 5, 'Building No', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, '7426000000', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, '7426000000', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'رقم المبنى', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 110.5);
        $pdf->Cell($tableWidth * 0.2, 5, 'Street Name', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, 'street', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, 'شارع الستين', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'اسم الشارع', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 115.5);
        $pdf->Cell($tableWidth * 0.1, 5, 'District', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, 'shehabia222', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, 'الشهابية', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.1, 5, 'الحي', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 120.5);
        $pdf->Cell($tableWidth * 0.1, 5, 'City', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, 'ALHASA 23', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, 'الاحساء', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.1, 5, 'المدينة', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 125.5);
        $pdf->Cell($tableWidth * 0.1, 5 , 'Country', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5 , 'Kingdom Of Saudia Arabia23', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5 , 'المملكة العربية السعودية', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.1, 5 , 'البلد', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 130.5);
        $pdf->Cell($tableWidth * 0.25, 5, 'Postal Code', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, '123450000', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, '123450000', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.25, 5, 'الرمز البريدي', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 135.5);
        $pdf->Cell($tableWidth * 0.2, 5, 'Additional No.', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, '234730062000032323', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, '234730062000032323', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'الرقم الاضافي للعنوان', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 140.5);
        $pdf->Cell($tableWidth * 0.2, 5, 'VAT Number', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, '12345678', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, '12345678', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'رقم تسجيل الضريبى', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 145.5);
        $pdf->Cell($tableWidth * 0.25, 5, 'Other Seller ID', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, '123523000000', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, '123523000000', 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.25, 5, 'معرف آخر', 1, 0, 'R');
        $pdf->Ln();
        $pdf->Ln();

        // Set font  
        $pdf->SetFont('aealarabiya', '', 10);
        
        $pdf->Cell(0,1,'توصيف السلعة او الخدمة',0,1,'R');
        $pdf->SetXY($pdf->GetPageWidth() - 200, 155.5);
        $pdf->Cell(140,1,'Line Items',0,1,'R');



        // Header  
        // Set the table header data  
        $header = array('Item Subtotal-Including VAT المجموع - شامل ضريبة القيمة المضافة',
        'Tax Amount مبلغ الضريبة',
        'Tax Rate نسبة الضريبة',
        'Taxable Amount المبلغ الخاضع للضريبة',
        'Discount الخصومات',
        'Quantity الكمية',
        'Unit price سعر الوحدة',
        'UOM الوحدة',
        'Nature of goods or services تفاصيل السلع أو الخدمات',
        '#');
        // Width for each column  
        $w = array(28, 15,15,18,15,15,15,13, 40, 5);

        
        // Create the table header  
        foreach ($header as $i => $col) {  
           
            // Set custom height to align the header correctly  
            $pdf->MultiCell($w[$i], 18, $col, 1, 'C', 0, 0, '', '', true);  
        }  
        $pdf->Ln(); // Move to the next line after the header 


        // Sample data  
        $data = [  
            ['1,021.200', '133.2', '15%', '888.00','0', '1', '888', 'حبة', 'شاشة', '1']  
            // More rows...  
        ]; 

        // Output the data  
        foreach ($data as $row) {  
            foreach ($row as $i => $col) {  
                $pdf->MultiCell($w[$i], 5, $col, 1, 'C', 0, 0, '', '', true);  
            }  
            $pdf->Ln(); // Move to the next line after each data row  
        }

        // Set the line style for a solid line  
        /*$solidStyle  = array(  
            'width' => 0.3, // Line width  
            'cap' => 'round', // Line cap  
            'join' => 'round', // Line join  
            'dash' => '0,0', // Dash pattern (3 mm on, 3 mm off)
            'color' => array(0, 0, 0) // Line color (RGB)  
        );

        $pdf->setLineStyle($solidStyle );
        */

        $y1 = $pdf->GetY(); // get the current Y position

        // Add text left and center and right in one cell
        $pdf->SetXY(15, $y1 + 5); // set X Y coordinates
        $pdf->Cell(0, 5, '1,021.20', 0, 1, 'LRC');

        // Add text left and center and right in one cell
        $pdf->SetXY(35, $y1 + 5); // set X Y coordinates
        $pdf->Cell(0, 5, 'Paid - المدفوع', 0, 1, 'LRC');

        $y = $pdf->GetY(); // get the current Y position

        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(15, $y, 70, $y);

        // Add text left and center and right in one cell
        $pdf->SetXY(15, $y); // set X Y coordinates
        $pdf->Cell(0, 5, '0.00', 0, 1, 'LRC');

        // Add text left and center and right in one cell
        $pdf->SetXY(35, $y); // set X Y coordinates
        $pdf->Cell(0, 5, 'Left Amount - المتبقى', 0, 1, 'LRC');

        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(15, $y+5, 70, $y+5);

        // Add text left and center and right in one cell
        $pdf->SetXY(15, $y+15); // set X Y coordinates
        $pdf->Cell(0, 5, '1.00', 0, 1, 'LRC');

        // Add text left and center and right in one cell
        $pdf->SetXY(35, $y+15); // set X Y coordinates
        $pdf->Cell(0, 5, 'Quantity -  السلع/الخدمات', 0, 1, 'LRC');

        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(15, $y+20, 70, $y+20);

        // Add text left and center and right in one cell
        $pdf->SetXY(0, $y-10); // set X Y coordinates
        $pdf->Cell(0, 5, 'Total amounts :  المبالغ اجمالي', 0, 1, 'R');

        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(150, $y-5, 195, $y-5);

        $y2 = $pdf->GetY();

        // Add text left and center and right in one cell
        $pdf->SetXY(0, $y2); // set X Y coordinates
        $pdf->Cell(0, 5, '888.00 SAR', 0, 1, 'R');
        // Draw a dashed line from point (10, 50) to (70, 50)  
        $pdf->Line(175, $y2+5, 195, $y2+5);

        // Add text left and center and right in one cell
        $pdf->SetXY(85, $y2); // set X Y coordinates
        $pdf->Cell(0, 5, 'Total - Excluding VAT-', 0, 1, 'L');
        $pdf->SetXY(120, $y2); // set X Y coordinates
        $pdf->Cell(0, 5, 'الاجمالي - غير شاملة ضريبة القيمة المضافة ', 0, 1, 'L');

        $pdf->Line(85, $y2+5, 172, $y2+5);

        $y3 = $pdf->GetY();
        $pdf->SetXY(0, $y3);
        $pdf->Cell(0,5,'0.00 SAR',0,1,'R');
        $pdf->Line(175, $y3+5, 195, $y3+5);

        $pdf->SetXY(132, $y3);
        $pdf->Cell(0,5,'Discount - ',0,1,'L');
        $pdf->SetXY(150, $y3);
        $pdf->Cell(0,5,'مجموع الخصومات',0,1,'L');
        $pdf->Line(85, $y3+5, 172, $y3+5);

        $y4 = $pdf->GetY();
        $pdf->SetXY(0, $y4+1);
        $pdf->Cell(0,5,'888.00 SAR',0,1,'R');
        $pdf->Line(175, $y4+6, 195, $y4+6);

        $pdf->SetXY(101, $y4+1);
        $pdf->Cell(0,1,'الإجمالي الخاضع للضريبة - غير شاملة ضريبة القيمة المضافة',0,1,'L');

        $pdf->SetXY(40, $y4+1);
        $pdf->Cell(0,1,'Total Taxable Amount - Excluding VAT',0,1,'L');
        $pdf->Line(40, $y4+6, 172, $y4+6);

        $y5 = $pdf->GetY() + 1;
        $pdf->SetXY(0, $y5);
        $pdf->Cell(0,5,'133.20 SAR',0,1,'R');
        $pdf->Line(175, $y5+5, 195, $y5+5);

        $pdf->SetXY(137, $y5);
        $pdf->Cell(0,1,'مجموع ضريبة القيمة المضافة',0,1,'L');
        $pdf->SetXY(100, $y5);
        $pdf->Cell(0,1,'Total VAT - 15%',0,1,'L');
        $pdf->Line(78, $y5+5, 172, $y5+5);

        $y6 = $pdf->GetY() + 1;
        $pdf->SetXY(0,$y6);
        $pdf->Cell(0,1,'1,021.20 SAR',0,1,'R');
        $pdf->Line(175, $y6+5, 195, $y6+5);

        $pdf->SetXY(130, $y6);
        $pdf->Cell(0,1,'إجمالي المبلغ المستحق بعد الضريبة',0,1,'L');  
        $pdf->SetXY(78, $y6);
        $pdf->Cell(0,1,'Total Amount Due with VAT 15%',0,1,'L');  
        $pdf->Line(78, $y6+5, 172, $y6+5);

        $y7 = $pdf->GetY() + 1;
        $pdf->SetXY(0, $y7);
        $pdf->Cell(0,10,'ألف و واحد و عشرون ريال - و عشرون هللة',0,1,'R');


        // Set the QR code content  
        $qrcodeContent = 'https://www.example.com'; // Example QR code content 

        // Add the QR code to the PDF  
        $pdf->SetX(90);
        $pdf->write2DBarcode($qrcodeContent, 'QRCODE,L', '', '', 30, 30, null, 'N');

        $y8 = $pdf->GetY();
        $pdf->SetXY(0, $y8);
        $pdf->Cell(0,1,'طرق الدفع: , نقد',0,1,'R');
        $pdf->SetXY(0, $y8+5);
        $pdf->Cell(0,1,'رقم بطاقة الاحوال: 12345',0,1,'R');
        $pdf->SetXY(0, $y8+10);
        $pdf->Cell(0,1,'نص أسفل البيانات -عربي',0,1,'R');
        $pdf->SetXY(0, $y8+15);
        $pdf->Cell(0,1,'الشحن الى:11',0,1,'R');


        function Footer($pdf) {
            $pdf->SetY(-20);
            $pdf->Cell(0, 10, 'Wasta - برنامج الواسطة', 0, false, 'L', 0, '', 0, false, 'T', 'M');
            $pdf->Cell(0, 10, 'تصميم مؤسسة روائع الابتكار للبرمجة', 0, false, 'R', 0, '', 0, false, 'T', 'M');

            $pdf->SetX($pdf->GetPageWidth() - 182);
            $pdf->Cell(150, 10, 'https://www.AppyInnovate.com', 0, 1, 'C');

        }

        Footer($pdf);
                // Close and output PDF document  
        $pdf->Output('zatca_a4.pdf', 'I');
    }
}

generate_A4pdf();