<?php
// Author : Emanuel Setio Dewo, Mario Iskandar
// Email  : setio.dewo@gmail.com, mario_iskandar@yahoo.com
// Start  : 23 Agustus 2008, 03 Februari 2011
//

if (file_exists("../fpdf.php")) require("../fpdf.php");
else require("fpdf.php");

class PDF extends FPDF {	
	//Current column
	var $col=0;
	//Ordinate of column start
	var $y0;

  function Header() {				
    $mrg = 35;
    $pjg = 140;   		

		$identitas = GetFields('identitas', "Kode", KodeID, '*');
    $logo = (file_exists("../img/logo.jpg"))? "../img/logo.jpg" : "img/logo.jpg";
    $this->Image($logo, 18, 8, 27);
    $this->SetFont("Times", '', 11);
    $this->Cell($mrg);
    $this->Cell($pjg, 6, $identitas['Yayasan'], 0, 1, 'C');
    $this->SetFont("Times", 'B', 16);
    $this->Cell($mrg);
    $this->Cell($pjg, 7, $identitas['Nama'], 0, 1, 'C');
    
    $this->SetFont("Times", 'I', 10);
    $this->Cell($mrg);
    $this->Cell($pjg, 5, $identitas['Alamat1'], 0, 1, 'C');
    $this->SetFont("Times", 'I', 7);
		$this->Cell($mrg);
    $this->Cell($pjg, 5, "Telp. ".$identitas['Telepon'].", Fax. ".$identitas['Fax'].", Website:".$identitas['Website'].", Email:".$identitas['Email'], 0, 1, 'C');
    $this->Cell(8);
    $this->Cell(180, 0, "", 1, 1);
    $this->Ln(2);		

		$jen = $_REQUEST['jen'];
		if ($jen == 3) {
			$blank = (file_exists("../img/blankwhite.jpg"))? "../img/blankwhite.jpg" : "img/blankwhite.jpg";
			$this->Image($blank, 18, 0, 250, 40);
		}
  }

	function JudulKolomnya($p) {
		// Judul tabel
		$t = 6;
		$p->SetFont('Helvetica', 'B', 6);
		$p->Cell(6, $t, '#', 1, 0, 'C');
		$p->Cell(12, $t, 'Kode MK', 1, 0, 'C');
		$p->Cell(60, $t, 'Nama Mata Kuliah', 1, 0, 'C');
		$p->Cell(8, $t, 'SKS', 1, 0, 'C');
		$p->Cell(8, $t, 'Nilai', 1, 0, 'C');
		//$p->Cell(15, $t, 'Bobot', 1, 0, 'C');
		//$p->Cell(15, $t, 'Mutu', 1, 0, 'C');
		$p->Ln($t); 
	}

	function SetCol($col) {
			//Set position at a given column
			$this->col=$col;
			$x=10+$col*98;
			$this->SetLeftMargin($x);
			$this->SetX($x);
	}

	/*function SetAutoPageBreak($auto, $margin=0) { // Intercepts Default Method in fpdf.php: modify $margin for page margin before it breaks		
		//Set auto page break mode and triggering margin
		$JmlData = $_REQUEST['JmlData'];		
		$this->AutoPageBreak=$auto;
		$this->bMargin=$margin;
		$this->PageBreakTrigger=$this->h-$margin;
	}

	function AcceptPageBreak() { // Intercepts Default Method in fpdf.php: accepts immediately when page breaks
			//Method accepting or not automatic page break
			if($this->col<1) { // Still one column then create one more column on the right					
					//Go to next column
					$this->SetCol($this->col+1);
					//Set ordinate to top
					$this->SetY($this->y0);										
					JudulKolomnya($this);
					$this->SetFont('Helvetica', '', 5);
					//Keep on page
					return false;
			}
			else { // Already 2 columns, reset it
					//Go back to first column
					$this->SetCol(0);					
					//Page break
					return true;
			}
	}*/

}

function BuatHeaderPDF($p, $x=10, $y=5, $w=190) {
  $p->Image("../img/header_image.gif", $x, $y, $w);
  $p->Ln(26);
}
function BuatHeaderPDF0($p, $x=10, $y=5, $w=190) {
  $p->Image("../img/header_image.gif", $x, $y, $w);
}

?>
