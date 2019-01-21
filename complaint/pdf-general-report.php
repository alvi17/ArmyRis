<?php

/* 
 * Exports PDF for General Report of Complaint
 * 
 * @author Md. Rafiqul Islam <rafiq.kuet@gmail.com>
 * @date February 08, 2017 04:38 am
 */

require "../core/config.php";
require "../core/init.php";
require "../modules/acl/Roles.php";
require "../modules/complaint/Complaint.php";
require "../modules/date_time_dropdown.php";

require "../libs/tcpdf/config/tcpdf_config.php";
require "../libs/tcpdf/tcpdf.php";


$limit = 500;


$search_txt = Input::request('search');
$area = Input::request('area');
$building = Input::request('building');
$status = Input::request('status');

//$ranks = Utility::listRanks();
//$areas = Utility::listServerAreas();
//$buildings = empty($area) ? Utility::listBuildings() : Utility::listBuildingsByAreaId($area);
//$problem_types = Complaint::listProblemTypes();
//$support_reasons = Complaint::listSupportReasons();

$rank = Input::request('rank');
$rank_opt = Input::request('rank_opt');

$date_from = Input::request('date_from');
$date_to = Input::request('date_to');
$problem_type = Input::request('problem_type');
$support_reason = Input::request('support_reason');
$page = Input::request('page');
if(empty($page)){$page = 1;}

if(empty($date_from)){
    $date_from = date('m/d/Y');
}  else {
    $date_from = date('m/d/Y', strtotime($date_from));
}
if(empty($date_to)){
    $date_to = date('m/d/Y');
}  else {
    $date_to = date('m/d/Y', strtotime($date_to));
}

$dt_date_from = !empty($date_from) ? date('Y-m-d 00:00:00', strtotime($date_from)) : '';
$dt_date_to = !empty($date_to) ? date('Y-m-d 23:59:59', strtotime($date_to)) : '';

$data = Complaint::listUserComplaints($search_txt, $area, $building, $status, $rank, $rank_opt, $dt_date_from, $dt_date_to, $problem_type, $support_reason, $page, $limit);


class MYPDF extends TCPDF {
    public function Header() {
        // Logo
        //$image_file = K_PATH_IMAGES.'logo.jpg';
        //$this->Image($image_file, 10, 3, 40, 20, 'JPG', '', 'T', false, 300, '', false, false, 0, false, false, false);			
        // Set font
        $this->SetFont('helvetica', 'B', 12);
        // Title
        $this->SetY(20);
        $this->Cell(0, 15, 'Residential Internet Service - AITSO', 0, false, 'C', 0, '', 0, false, 'M', 'M');
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


//$pdfPageFormat = 'A4';
//$dateColWdth = 100;
$pdfPageFormat = 'LETTER';
$dateColWdth = 130;


// create new PDF document
$pdf = new MYPDF('L', PDF_UNIT, 'LETTER', true, 'UTF-8', false);

// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Residential Internet Service - AITSO');
$pdf->SetTitle('Residential Internet Service - AITSO');
$pdf->SetSubject('Complaints');
$pdf->SetKeywords('complain, complaint');

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

// set font
//$pdf->SetFont('helvetica', 'B', 20);
//$pdf->Write(0, 'Heading', '', 0, 'L', true, 0, false, false, 0);
// -----------------------------------------------------------------------------
$tbl = '<table cellspacing="0" cellpadding="90%" border="1">
<thead> 
    <tr>
        <th width="30" align="center"><b>SL</b></th>
        <th width="60" align="center"><b>Username</b></th>
        <th width="65" align="center"><b>Rank</b></th>
        <th width="120" align="center"><b>Full Name</b></th>
        <th width="'.$dateColWdth.'" align="center"><b>Date</b></th>
        <th width="95" align="center"><b>Complain</b></th>
        <th width="115" align="center"><b>Support</b></th>
        <th width="180" align="center"><b>Support Details</b></th>
        <th width="85" align="center"><b>Assistant</b></th>
    </tr>
</thead>
<tbody>';

$i=0;
foreach($data as $d){
    $status = isset(Complaint::$complaintAdminStatuses[$d['id_status']]) ? Complaint::$complaintAdminStatuses[$d['id_status']] : '';
    $support_reason = isset(Complaint::$complaintAdminStatuses[$d['id_status']]) ? Complaint::$complaintAdminStatuses[$d['id_status']] : '';
     if(!empty($d['support_reason'])){
         $support_reason .= '<br>(<i>'.$d['support_reason'].'</i>)';
     }
    // Address
    // <td width="80">'.$d['house_no'].', '.$d['building_name'].', '.$d['area_name'].'</td>
	if(!empty($d['dtt_mod'])){
		$duration = '<br><small><b>'.Date::duration($d['dtt_mod'], $d['pb_since']) .'</b></small>';
	} else{
		$duration = '';
	}
$tbl .= '
    <tr>
        <td width="30">'.(++$i).'.</td>
        <td width="60">'.$d['username'].'</td>
        <td width="65">'.$d['rank'].'</td>
        <td width="120">'.trim($d['firstname']. ' '. $d['lastname']).'</td>
        <td width="'.$dateColWdth.'">
            <b>PS: </b>'.Date::niceDateTime5($d['pb_since']).'
            <br>
            <b>LT: </b>'.Date::niceDateTime5($d['dtt_mod']).$duration.'
        </td>
        <td width="95">'.$d['pb_title'].'</td>
        <td width="115">'.$support_reason.'</td>
        <td width="180">'.$d['support_details'].'</td>
        <td width="85">'.trim($d['assist_fisrtname'].' '.$d['assist_lastname']).'</td>
    </tr>';
}
$tbl .= '</tbody></table>';

$pdf->SetFont('helvetica', '', 8);
$pdf->writeHTML($tbl, true, false, false, false, '');

$txt = '<b>PS: </b>Problem Since
<br>
<b>LT: </b>Last Tried on';
$pdf->writeHTML($txt, true, false, false, false, '');

// Multicell test
//$pdf->MultiCell(55, 5, '[DEFAULT] '.$txt, 1, '', 0, 1, '', '', true);

// -----------------------------------------------------------------------------
//Close and output PDF document
$pdf->Output('complaints.pdf', 'D'); //D : Force to Download, I = Display Inline