<?php

/**
 * Subscriber Details
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date December 10, 2016 01:31
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/subscriber/Subscriber.php";

require "../libs/tcpdf/config/tcpdf_config.php";
require "../libs/tcpdf/tcpdf.php";


$pageCode       = 'subscriber-detail';
$pageContent	= 'subscriber/detail';
$pageTitle 		= 'Subscriber Details';

if(!Auth::isAuthenticatedPage($pageCode)){
  Session::put('error', "You do not have permission to access '{$pageTitle}' page.");
  Utility::redirect(BASE_URL.'/login.php');
}

$subscriber = new Subscriber();

$id = (int) Input::get('id');
$data = $subscriber->getSubscriberDetials($id);

// Utility::pr($data); exit;

if(empty($data)){
  Session::put('error', "Subscriber information not found.");
  Utility::redirect('index.php');
}

class MYPDF extends TCPDF {
    public function Header() {
        // Logo
        //$image_file = K_PATH_IMAGES.'logo.jpg';
        //$this->Image($image_file, 10, 3, 40, 20, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);			
        // Set font
        $this->SetFont('helvetica', 'B', 12);
        // Title
        $this->SetY(15);
        $this->Cell(0, 15, 'Residential Internet Service - AITSO', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetY(20);
        $this->Cell(0, 15, 'Subscriber Details', 0, false, 'C', 0, '', 0, false, 'M', 'M');
        $this->SetY(22);
        $this->Cell(0, 15, '__________________________________________________', 0, false, 'C', 0, '', 0, false, 'M', 'M');
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', '', 6);
        // Page number
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().' of '.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}


$pdfPageFormat = 'LETTER';
$dateColWdth = 130;


// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, 'LETTER', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Residential Internet Service - AITSO');
$pdf->SetTitle('Residential Internet Service - AITSO');
$pdf->SetSubject('subscriber details');
$pdf->SetKeywords('subscriber details, subscriber details');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE.' 048', PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
	require_once(dirname(__FILE__).'/lang/eng.php');
	$pdf->setLanguageArray($l);
}

// ---------------------------------------------------------
// add a page
$pdf->AddPage(); // 'L'

$complementary_txt = $data['category']=='Complementary' ? 'Complementary Amount' : '';
$complementary_amt = $data['category']=='Complementary' ? Utility::commaSeperator($data['complementary_amount']).' Taka' : '';

$tbl = '<table cellspacing="0" cellpadding="5" border="0">
    <tr>
        <td width="20%" align="right"><b>Username</b></td>
        <td width="30%" align="left">'.$data['username'].'</td>
        <td width="20%" align="right"><b>Password</b></td>
        <td width="30%" align="left">'.$data['password'].'</td>
    </tr>
    <tr>
        <td width="20%" align="right"><b>BA No.</b></td>
        <td width="30%" align="left">'.$data['ba_no'].'</td>
        <td width="20%" align="right"><b>Rank</b></td>
        <td width="30%" align="left">'.$data['rank'].'</td>
    </tr>
    <tr>
        <td width="20%" align="right"><b>Full Name</b></td>
        <td width="30%" align="left">'.trim($data['firstname'].' '. $data['lastname']).'</td>
        <td width="20%" align="right"><b></b></td>
        <td width="30%" align="left"></td>
    </tr>
    <tr>
        <td width="20%" align="right"><b>Area</b></td>
        <td width="30%" align="left">'.$data['area'].'</td>
        <td width="20%" align="right"><b>Building</b></td>
        <td width="30%" align="left">'.$data['building'].'</td>
    </tr>
    <tr>
        <td width="20%" align="right"><b>House</b></td>
        <td width="30%" align="left">'.$data['house_no'].'</td>
        <td width="20%" align="right"><b>Remote IP</b></td>
        <td width="30%" align="left">'.$data['remote_ip'].'</td>
    </tr>
    <tr>
        <td width="20%" align="right"><b>Official Mobile</b></td>
        <td width="30%" align="left">'.$data['official_mobile'].'</td>
        <td width="20%" align="right"><b>Personal Mobile</b></td>
        <td width="30%" align="left">'.$data['personal_mobile'].'</td>
    </tr>
    <tr>
        <td width="20%" align="right"><b>Email</b></td>
        <td width="30%" align="left">'.$data['email'].'</td>
        <td width="20%" align="right"><b>Residential Phone</b></td>
        <td width="30%" align="left">'.$data['residential_phone'].'</td>
    </tr>
    <tr>
        <td width="20%" align="right"><b>Package</b></td>
        <td width="30%" align="left">'.$data['package_code'].'</td>
        <td width="20%" align="right"><b></b></td>
        <td width="30%" align="left"></td>
    </tr>
    <tr>
        <td width="20%" align="right"><b>Category</b></td>
        <td width="30%" align="left">'.$data['category'].'</td>
        <td width="20%" align="right"><b>'.$complementary_txt.'</b></td>
        <td width="30%" align="left">'.$complementary_amt.'</td>
    </tr>
    <tr>
        <td width="20%" align="right"><b>Current Balance</b></td>
        <td width="30%" align="left">'.$data['payment_balance'].'</td>
        <td width="20%" align="right"><b>Status</b></td>
        <td width="30%" align="left">'.Subscriber::getStatus($data['status_id']).'</td>
    </tr>
    <tr>
        <td width="20%" align="right"><b>Connection from</b></td>
        <td width="30%" align="left">'.Date::niceDateTime($data['connection_from']).'</td>
        <td width="20%" align="right"><b>Connection to</b></td>
        <td width="30%" align="left">'.Date::niceDateTime($data['connection_to']).'</td>
    </tr>
    <tr>
        <td width="20%" align="right"><b>Remarks</b></td>
        <td width="30%" align="left">'.$data['remarks'].'</td>
        <td width="20%" align="right"><b></b></td>
        <td width="30%" align="left"></td>
    </tr>

</table>';

$pdf->writeHTML($tbl, true, false, false, false, '');


//Close and output PDF document
$pdf->Output('subscriber_details_'.$data['ba_no'].'.pdf', 'D'); //D : Force to Download, I = Display Inline

