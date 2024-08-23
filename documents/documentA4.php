<?php

$path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
require_once($path . 'wp-load.php');

// require plugin file
require_once('../zatca.php');

// Add this function in your theme's functions.php or a custom plugin 

function generate_A4pdf() 
{
    // Log the download xml:
    $user_login = wp_get_current_user()->user_login;
    $user_id = wp_get_current_user()->ID;
    log_download_doc_xml($user_login, $user_id);
    
    // Check if the form is submitted
    if (isset($_GET['docno'])) 
    {
        $docNo = $_GET['docno'];
        global $wpdb;

        
        // get zatcaSuccessResponse code from zatcaDocument table using getVar
        $zatcaSuccessResponse = $wpdb->get_var("SELECT zatcaSuccessResponse FROM zatcaDocument WHERE documentNo = '$docNo'");

        $zatcaQrCode = $wpdb->get_var("SELECT qrCode FROM zatcaDocumentxml WHERE documentNo = '$docNo'");

        $zatcaDocumentData = $wpdb->get_results(
            $wpdb->prepare("select * from zatcaDocument where documentNo = '$docNo'")
        );

        $zatcaCompanyData = $wpdb->get_results(
            $wpdb->prepare("select * from zatcaCompany")
        );

        // company info
        foreach ($zatcaCompanyData as $company)
        {
            $companyName = $company->companyName;
            $CompanyVATID = $company->VATID;
            $country_Eng = $company->country_Eng;
        }

        // seller and buyer data and header invoice data
        foreach ($zatcaDocumentData as $doc)
        {
            // header invoice data
            $dateG = $doc->dateG;
            $amountCalculatedPayed = number_format($doc->amountCalculatedPayed , 2, '.', '');


            // seller information
            $seller_aName = $doc->seller_aName;
            $seller_eName = $doc->seller_eName;
            $seller_apartmentNum = $doc->seller_apartmentNum;
            $seller_street_Arb = $doc->seller_street_Arb;
            $seller_street_Eng = $doc->seller_street_Eng;
            $seller_district_Arb = $doc->seller_district_Arb;
            $seller_district_Eng = $doc->seller_district_Eng;
            $seller_city_Arb = $doc->seller_city_Arb;
            $seller_city_Eng = $doc->seller_city_Eng;
            $seller_PostalCode = $doc->seller_PostalCode;
            $seller_POBoxAdditionalNum = $doc->seller_POBoxAdditionalNum;
            $seller_country_Arb = $doc->seller_country_Arb;
            $seller_country_Eng = $doc->seller_country_Eng;
            $seller_VAT = $doc->seller_VAT;
            $seller_secondBusinessID = $doc->seller_secondBusinessID;

            // buyer information
            $buyer_aName = $doc->buyer_aName;
            $buyer_eName = $doc->buyer_eName;
            $buyer_apartmentNum = $doc->buyer_apartmentNum;
            $buyer_street_Arb = $doc->buyer_street_Arb;
            $buyer_street_Eng = $doc->buyer_street_Eng;
            $buyer_district_Arb = $doc->buyer_district_Arb;
            $buyer_district_Eng = $doc->buyer_district_Eng;
            $buyer_city_Arb = $doc->buyer_city_Arb;
            $buyer_city_Eng = $doc->buyer_city_Eng;
            $buyer_PostalCode = $doc->buyer_PostalCode;
            $buyer_country_Arb = $doc->buyer_country_Arb;
            $buyer_country_Eng = $doc->buyer_country_Eng;
            $buyer_POBoxAdditionalNum = $doc->buyer_POBoxAdditionalNum;
            $buyer_VAT = $doc->buyer_VAT;
            $buyer_secondBusinessID = $doc->buyer_secondBusinessID;
        }

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
        $totalQuantity = 0;
        foreach ($results as $result) {
            $discount += $result->discount;
            $vatAmount += $result->vatAmount;
            $price += $result->price;
            $amountWithVAT += $result->amountWithVAT;
            $totalQuantity += $result->quantity;
            }
            $discount = number_format($discount , 2, '.', '');
            $vatAmount = number_format($vatAmount , 2, '.', '');
            $price = number_format($price , 2, '.', '');
            $amountWithVAT = number_format($amountWithVAT , 2, '.', '');
            $totalQuantity = number_format($totalQuantity , 2, '.', '');

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

        if($zatcaSuccessResponse == 0)
        {
            // Set the watermark text  
            $watermark = 'نسخة غير نهائية';  
            $pdf->SetFont('aealarabiya', 'B', 100);  
            //$pdf->SetTextColor(255, 192, 203); // Light pink color 
            $pdf->SetAlpha(0.3); // Set transparency for the watermark 
            RotatedText($pdf, 45, 200, $watermark, 55); // Add rotated text
        }  

        $pdf->SetAlpha(1); // Set transparency for the watermark
        // Set font  
        $pdf->SetFont('aealarabiya', '', 12);

        // Add text cell
        $pdf->SetXY(15, 10); // set X Y coordinates
        $pdf->Cell(0, 10, $companyName, 0, 1, 'L'); // 0 = no border, 1 = new line, 'C'

        $pdf->SetXY(15, 15);
        $pdf->Cell(0,10, 'الرقم الضريبي: ' . $CompanyVATID, 0, 1, 'L');

        $pdf->Setxy(0, 10);
        $pdf->Cell(0, 10, $companyName, 0, 1, 'R');

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
        $pdf->Cell(0,1,'رقم العميل الضريبي: ' . $buyer_VAT,0,1,'L');

        $pdf->SetXY(15, 70);
        $pdf->Cell(0,1,'80',0,1,'L');

        // right top side

        $pdf->SetXY(0, 30);
        $pdf->Cell(0,1,'الفرع: 1 - مباني المؤسسة1',0,1,'R');

        $pdf->SetXY(0, 40);
        $pdf->Cell(0,1,$country_Eng,0,1,'R');
        $pdf->SetXY(0, 45);
        $pdf->Cell(0,1,'نص أسفل الرأس -عربي1',0,1,'R');

        $pdf->SetXY(0, 55);
        $pdf->Cell(0,1,'رقم الفاتورة:', 0, 1, 'R');

        $pdf->SetXY($pdf->GetPageWidth() - 200, 55);
        $pdf->Cell(157,1, $docNo,0,1,'R');

        $pdf->SetXY(115, 55);
        $pdf->Cell(0,1,'Invoice Number:',0,1,'L');

        $pdf->SetXY(177, 65);
        $pdf->Cell(0,1,'الاسم:',0,1,'R');

        $pdf->SetXY($pdf->GetPageWidth() - 200, 65);
        $pdf->Cell(150,1, $buyer_aName,0,1,'R');

        $pdf->SetXY(0, 75);
        $pdf->Cell(0,1,'تاريخ اصدار الفاتورة:',0,1,'R');

        $pdf->SetXY($pdf->GetPageWidth() - 200, 75);
        $pdf->Cell(150,1, $dateG,0,1,'R');

        $pdf->SetXY(80, 75);
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
        $pdf->Cell($tableWidth * 0.4, 5, $seller_eName, 1,0,'L');
        $pdf->Cell($tableWidth * 0.4, 5, $seller_aName, 1,0,'R');
        $pdf->Cell($tableWidth * 0.1, 5, 'الاسم', 1,0,'R');
        $pdf->Ln(); // Move to the next line
        
        $pdf->Cell($tableWidth * 0.2, 5, 'Building No', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $seller_apartmentNum, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $seller_apartmentNum, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'رقم المبنى', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.2, 5, 'Street Name', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $seller_street_Eng, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $seller_street_Arb, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'اسم الشارع', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.1, 5, 'District', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, $seller_district_Eng, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, $seller_district_Arb, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.1, 5, 'الحي', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.1, 5, 'City', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, $seller_city_Eng, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, $seller_city_Arb, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.1, 5, 'المدينة', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.1, 5 , 'Country', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5 , $seller_country_Eng, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5 , $seller_country_Arb, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.1, 5 , 'البلد', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.25, 5, 'Postal Code', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, $seller_PostalCode, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, $seller_PostalCode, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.25, 5, 'الرمز البريدي', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.2, 5, 'Additional No.', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $seller_POBoxAdditionalNum, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $seller_POBoxAdditionalNum, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'الرقم الاضافي للعنوان', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.2, 5, 'VAT Number', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $seller_VAT, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $seller_VAT, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'رقم تسجيل الضريبى', 1, 0, 'R');
        $pdf->Ln();

        $pdf->Cell($tableWidth * 0.25, 5, 'Other Seller ID', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, $seller_secondBusinessID, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, $seller_secondBusinessID, 1, 0, 'R');
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
        $pdf->Cell($tableWidth * 0.4, 5, $buyer_eName, 1,0,'L');
        $pdf->Cell($tableWidth * 0.4, 5, $buyer_aName, 1,0,'R');
        $pdf->Cell($tableWidth * 0.1, 5, 'الاسم', 1,0,'R');
        $pdf->Ln(); // Move to the next line

        $pdf->SetXY(105, 105.5);
        $pdf->Cell($tableWidth * 0.2, 5, 'Building No', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $buyer_apartmentNum, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $buyer_apartmentNum, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'رقم المبنى', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 110.5);
        $pdf->Cell($tableWidth * 0.2, 5, 'Street Name', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $buyer_street_Eng, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $buyer_street_Arb, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'اسم الشارع', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 115.5);
        $pdf->Cell($tableWidth * 0.1, 5, 'District', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, $buyer_district_Eng, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, $buyer_district_Arb, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.1, 5, 'الحي', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 120.5);
        $pdf->Cell($tableWidth * 0.1, 5, 'City', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, $buyer_city_Eng, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5, $buyer_city_Arb, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.1, 5, 'المدينة', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 125.5);
        $pdf->Cell($tableWidth * 0.1, 5 , 'Country', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5 , $buyer_country_Eng, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.4, 5 , $buyer_country_Arb, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.1, 5 , 'البلد', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 130.5);
        $pdf->Cell($tableWidth * 0.25, 5, 'Postal Code', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, $buyer_PostalCode, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, $buyer_PostalCode, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.25, 5, 'الرمز البريدي', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 135.5);
        $pdf->Cell($tableWidth * 0.2, 5, 'Additional No.', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $buyer_POBoxAdditionalNum, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $buyer_POBoxAdditionalNum, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'الرقم الاضافي للعنوان', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 140.5);
        $pdf->Cell($tableWidth * 0.2, 5, 'VAT Number', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $buyer_VAT, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.3, 5, $buyer_VAT, 1, 0, 'R');
        $pdf->Cell($tableWidth * 0.2, 5, 'رقم تسجيل الضريبى', 1, 0, 'R');
        $pdf->Ln();

        $pdf->SetXY(105, 145.5);
        $pdf->Cell($tableWidth * 0.25, 5, 'Other Buyer ID', 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, $buyer_secondBusinessID, 1, 0, 'L');
        $pdf->Cell($tableWidth * 0.25, 5, $buyer_secondBusinessID, 1, 0, 'R');
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
        /*foreach ($data as $row) {  
            foreach ($row as $i => $col) {  
                $pdf->MultiCell($w[$i], 5, $col, 1, 'C', 0, 0, '', '', true);  
            }  
            $pdf->Ln(); // Move to the next line after each data row  
        }*/

        $i = 1;
        foreach ($results as $result)
        {
            $pdf->MultiCell(28, 5, $result->amountWithVAT, 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(15, 5, $result->vatAmount, 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(15, 5, $result->vatRate . '%', 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(18, 5, $result->netAmount, 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(15, 5, $result->discount, 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(15, 5, $result->quantity, 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(15, 5, $result->price, 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(13, 5, $result->unitNo, 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(40, 5, $result->order_item_name, 1, 'C', 0, 0, '', '', true);
            $pdf->MultiCell(5, 5, $i, 1, 'C', 0, 0, '', '', true);
            $pdf->Ln(); // Move to the next line after each data row

            $i = $i + 1;
        }


        $y1 = $pdf->GetY(); // get the current Y position

        // Add text left and center and right in one cell
        $pdf->SetXY(15, $y1 + 5); // set X Y coordinates
        $pdf->Cell(0, 5, $amountCalculatedPayed, 0, 1, 'LRC');

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
        $pdf->Cell(0, 5, $totalQuantity, 0, 1, 'LRC');

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
        $pdf->Cell(0, 5, $price . ' SAR', 0, 1, 'R');
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
        $pdf->Cell(0,5, $discount . ' SAR',0,1,'R');
        $pdf->Line(175, $y3+5, 195, $y3+5);

        $pdf->SetXY(132, $y3);
        $pdf->Cell(0,5,'Discount - ',0,1,'L');
        $pdf->SetXY(150, $y3);
        $pdf->Cell(0,5,'مجموع الخصومات',0,1,'L');
        $pdf->Line(85, $y3+5, 172, $y3+5);

        $y4 = $pdf->GetY();
        $pdf->SetXY(0, $y4+1);
        $pdf->Cell(0,5, $vatAmount . ' SAR',0,1,'R');
        $pdf->Line(175, $y4+6, 195, $y4+6);

        $pdf->SetXY(101, $y4+1);
        $pdf->Cell(0,1,'الإجمالي الخاضع للضريبة - غير شاملة ضريبة القيمة المضافة',0,1,'L');

        $pdf->SetXY(40, $y4+1);
        $pdf->Cell(0,1,'Total Taxable Amount - Excluding VAT',0,1,'L');
        $pdf->Line(40, $y4+6, 172, $y4+6);

        $y5 = $pdf->GetY() + 1;
        $pdf->SetXY(0, $y5);
        $pdf->Cell(0,5, $vatAmount . ' SAR',0,1,'R');
        $pdf->Line(175, $y5+5, 195, $y5+5);

        $pdf->SetXY(137, $y5);
        $pdf->Cell(0,1,'مجموع ضريبة القيمة المضافة',0,1,'L');
        $pdf->SetXY(100, $y5);
        $pdf->Cell(0,1,'Total VAT - 15%',0,1,'L');
        $pdf->Line(78, $y5+5, 172, $y5+5);

        $y6 = $pdf->GetY() + 1;
        $pdf->SetXY(0,$y6);
        $pdf->Cell(0,1, $amountWithVAT . ' SAR',0,1,'R');
        $pdf->Line(175, $y6+5, 195, $y6+5);

        $pdf->SetXY(130, $y6);
        $pdf->Cell(0,1,'إجمالي المبلغ المستحق بعد الضريبة',0,1,'L');  
        $pdf->SetXY(78, $y6);
        $pdf->Cell(0,1,'Total Amount Due with VAT 15%',0,1,'L');  
        $pdf->Line(78, $y6+5, 172, $y6+5);


        function convertNumberToWords($number) {  
            $ones = [  
                0 => 'صفر', 1 => 'واحد', 2 => 'اثنان', 3 => 'ثلاثة', 4 => 'أربعة',  
                5 => 'خمسة', 6 => 'ستة', 7 => 'سبعة', 8 => 'ثمانية', 9 => 'تسعة'  
            ];  
        
            $teens = [  
                10 => 'عشرة', 11 => 'أحد عشر', 12 => 'اثنا عشر',  
                13 => 'ثلاثة عشر', 14 => 'أربعة عشر', 15 => 'خمسة عشر',  
                16 => 'ستة عشر', 17 => 'سبعة عشر', 18 => 'ثمانية عشر', 19 => 'تسعة عشر'  
            ];  
            
            $tens = [  
                2 => 'عشرون', 3 => 'ثلاثون', 4 => 'أربعون', 5 => 'خمسون',  
                6 => 'ستون', 7 => 'سبعون', 8 => 'ثمانون', 9 => 'تسعون'  
            ];  
            
            $hundreds = [  
                1 => 'مئة', 2 => 'مئتان', 3 => 'ثلاثمئة', 4 => 'أربعمئة',  
                5 => 'خمسمئة', 6 => 'ستمئة', 7 => 'سبعمئة', 8 => 'ثمانمئة', 9 => 'تسعمئة'  
            ];  
        
            $thousands = [  
                1 => 'ألف', 2 => 'ألفان', 3 => 'ثلاثة آلاف', 4 => 'أربعة آلاف',  
                5 => 'خمسة آلاف', 6 => 'ستة آلاف', 7 => 'سبعة آلاف', 8 => 'ثمانية آلاف', 9 => 'تسعة آلاف'  
            ];  
        
            if ($number < 0) {  
                return 'سالب ' . convertNumberToWords(-$number);  
            }  
            if ($number == 0) {  
                return $ones[0];  
            }  
        
            $result = '';  
        
            // Handle thousands  
            $thousandPart = intval($number / 1000);  
            if ($thousandPart > 0) {  
                $result .= $thousands[$thousandPart] . ' و ';  
            }  
            
            // Handle hundreds  
            $hundredPart = intval(($number % 1000) / 100);  
            if ($hundredPart > 0) {  
                $result .= $hundreds[$hundredPart] . ' و ';  
            }  
        
            // Handle tens  
            $tenPart = intval(($number % 100) / 10);  
            if ($tenPart == 1 && ($number % 10) > 0) {  
                // For numbers between 11 and 19  
                $result .= $teens[$number % 100] . ' ';  
            } elseif ($tenPart > 1) {  
                $result .= $tens[$tenPart] . ' ';  
            }  
        
            // Handle ones  
            $onePart = $number % 10;  
            if ($tenPart != 1) { // if tenPart is not one, append ones  
                if ($onePart > 0) {  
                    $result .= $ones[$onePart] . ' ';  
                }  
            }  
        
            return trim($result);  
        }

        function formatPriceToArabic($total) {  
            $total = round($total, 2);  
            $parts = explode('.', number_format($total, 2, '.', ''));  
            $riyals = intval($parts[0]);  
            $halalas = intval($parts[1]);  
        
            $riyalsInWords = convertNumberToWords($riyals) . ' ريال';  
            $halalasInWords = convertNumberToWords($halalas) . ' هللة';  
        
            return $riyalsInWords . ' و ' . $halalasInWords;  
        } 

        $arabic_price = formatPriceToArabic($amountWithVAT);
        $y7 = $pdf->GetY() + 1;
        $pdf->SetXY(0, $y7);
        $pdf->Cell(0,10, $arabic_price,0,1,'R');


        // Set the QR code content  
        $qrcodeContent = $zatcaQrCode; // Example QR code content 

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
