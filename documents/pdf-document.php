<?php
$path = preg_replace( '/wp-content(?!.*wp-content).*/', '', __DIR__ );
require_once($path . 'wp-load.php');

// Check if the form is submitted
if (isset($_GET['doc-no'])) 
{
    
    // Call the counter gap function
    require_once('../tcpdf/tcpdf.php');

    $pdf = new TCPDF('p', 'mm', array(80, 'auto'), true, 'UTF-8', false);
    $lg = Array();
    $lg['a_meta_charset'] = 'UTF-8';
    $lg['a_meta_dir'] = 'rtl';
    $lg['a_meta_language'] = 'ar';
    $pdf->setLanguageArray($lg);
    $pdf->setRTL(true);
    $pdf->SetFont('aealarabiya', '', 11);
    $pdf->SetDefaultMonospacedFont('aealarabiya');
    $pdf->SetMargins('50', '10', '50');
    $pdf->setPrintHeader(false);
    $pdf->setPrintFooter(false);
    $pdf->SetAutoPageBreak(true, '0');

    
    $content = '';
    
    $content .= '
        <div align="center">
            <img src="https://media.licdn.com/dms/image/C561BAQE52X7uKKfD0Q/company-background_10000/0/1584643971105/al_rajhi_saudi_group_cover?e=2147483647&v=beta&t=IooJKeXEtj4E72ZD4ZmFMZNoxeqE-lhTJp4H76vpajM"
            width="200" height="100">
        </div>
      	<h3 align="center" style="font-size:25px">مؤسسة الراجحى</h3>
        <h3 align="center" style="font-size:25px">Al-Rajhi Est</h3>
        <div align="center" style="font-size: 20px;">
             <span>هاتف: 0123456789</span> <span>س.ت: 2251114910</span><br><br>
             <span>الرقم الضريبى: 310424896700003</span>
        </div>

        <div align="center" style="font-size: 20px;">
            <span>نص أسف الرأس -عربى 1</span><br>
            <span>نص أسفل الرأس -انجليزى 1</span>
        </div>
        <br>
        <div align="center" style="font-size: 20px;">
            <span> Simplified Tax Invoice </span><span> فاتورة ضريبية مبسطة </span>
        </div>
        <br>
        <div align="center" style="font-size: 20px;">
            <span> فاتورة نقاط البيع </span>
        </div>
        
        <div align="center" style="font-size: 22px;">
            <span> الفرع: فرع رئيسى </span>
        </div>
        <br>

        <div align="center" style="width:100%;font-size: 17px;display:flex;justify-content: space-between">
            <span> رقم الفاتورة: </span>
            <span> 352 </span>
            <span> Invoice Number: </span>
        </div>

        <div align="center" style="width:100%;font-size: 17px;display:flex;justify-content: space-between">
            <span> تاريخ اصدار الفاتورة: </span>
            <span> Invoice Issue Date: </span>
        </div>
        <div align="center" style="width:100%;font-size: 17px;display:flex;justify-content: space-between">
            <span> 2023-05-14 23:33 </span>
        </div>

        <div align="center" style="width:100%;font-size: 17px;display:flex;justify-content: space-between">
            <span> الموظف: 1 </span>
            <span> 2 # </span>
        </div>

        <div style="width:100%;font-size: 17px;">
            <span> الاسـم: محمد على كلاى </span>
        </div>
        <br>

        <div align="center" style="width:100%;font-size: 13px;">
            <span> تفاصيل السلع او الخدمات: </span>
            <span> Nature of goods or services: </span><br>
            <span>-----------------------------------------------------------------------</span>
        </div>

        
        
      	';
			 
    //$content .= generatePdf();

    $pdf->AddPage();

    
    // Add the text content to the PDF
    $pdf->writeHTML($content);
    $pdf->Output('emps.pdf', 'I');

}


function generatePdf()
{
    $docNo = $_GET['doc-no'];

    $contents = '';
    global $wpdb;

    // Query to fetch the invoice numbers within the specified date range for the given branch
    $query = $wpdb->prepare("
        SELECT *
        FROM zatcadocument
        WHERE documentNo = %d", $docNo);

    $results = $wpdb->get_results($query);
    foreach ($results as $result) 
    {
        $contents .= "";
    }

    return $contents;
}