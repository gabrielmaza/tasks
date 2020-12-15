<?php
if (!isset($_SESSION)) { session_start(); }

define('FPDF_FONTPATH','./cmpfpdf17/font');
require('./cmpfpdf17/fpdf.php');

//###############################################################################
// NO MODIFICAR  NO MODIFICAR NO MODIFICAR NO MODIFICAR NO MODIFICAR NO MODIFICAR
//###############################################################################

class PDF extends FPDF
{
	var $B;
	var $I;
	var $U;
	var $HREF;

	function PDF($orientation='P', $unit='mm', $size='A4')
	{
		// Llama al constructor de la clase padre
		$this->FPDF($orientation,$unit,$size);
		// Iniciaci�n de variables
		$this->B = 0;
		$this->I = 0;
		$this->U = 0;
		$this->HREF = '';
	}

	function WriteHTML($html)
	{
		// Int�rprete de HTML
		$html = str_replace("\n",' ',$html);
		$a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
		foreach($a as $i=>$e)
		{
			if($i%2==0)
			{
				// Text
				if($this->HREF)
					$this->PutLink($this->HREF,$e);
				else
					$this->Write(5,$e);
			}
			else
			{
				// Etiqueta
				if($e[0]=='/')
					$this->CloseTag(strtoupper(substr($e,1)));
				else
				{
					// Extraer atributos
					$a2 = explode(' ',$e);
					$tag = strtoupper(array_shift($a2));
					$attr = array();
					foreach($a2 as $v)
					{
						if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
							$attr[strtoupper($a3[1])] = $a3[2];
					}
					$this->OpenTag($tag,$attr);
				}
			}
		}
	}

	function OpenTag($tag, $attr)
	{
		// Etiqueta de apertura
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,true);
		if($tag=='A')
			$this->HREF = $attr['HREF'];
		if($tag=='BR')
			$this->Ln(5);
	}

	function CloseTag($tag)
	{
		// Etiqueta de cierre
		if($tag=='B' || $tag=='I' || $tag=='U')
			$this->SetStyle($tag,false);
		if($tag=='A')
			$this->HREF = '';
	}

	function SetStyle($tag, $enable)
	{
		// Modificar estilo y escoger la fuente correspondiente
		$this->$tag += ($enable ? 1 : -1);
		$style = '';
		foreach(array('B', 'I', 'U') as $s)
		{
			if($this->$s>0)
				$style .= $s;
		}
		$this->SetFont('',$style);
	}

	function PutLink($URL, $txt)
	{
		// Escribir un hiper-enlace
		$this->SetTextColor(0,0,255);
		$this->SetStyle('U',true);
		$this->Write(5,$txt,$URL);
		$this->SetStyle('U',false);
		$this->SetTextColor(0);
	}
	
	var $angle=0;
	
	function Rotate($angle,$x=-1,$y=-1) {
	
		if($x==-1)
			$x=$this->x;
		if($y==-1)
			$y=$this->y;
		if($this->angle!=0)
			$this->_out('Q');
		$this->angle=$angle;
		if($angle!=0)
	
		{
			$angle*=M_PI/180;
			$c=cos($angle);
			$s=sin($angle);
			$cx=$x*$this->k;
			$cy=($this->h-$y)*$this->k;
	
			$this->_out(sprintf('q %.5f %.5f %.5f %.5f %.2f %.2f cm 1 0 0 1 %.2f %.2f cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
		}
	}	
	
	//barcode
	
	function TextWithRotation($x, $y, $txt, $txt_angle, $font_angle=0)
	{
		$font_angle+=90+$txt_angle;
		$txt_angle*=M_PI/180;
		$font_angle*=M_PI/180;
	
		$txt_dx=cos($txt_angle);
		$txt_dy=sin($txt_angle);
		$font_dx=cos($font_angle);
		$font_dy=sin($font_angle);
	
		$s=sprintf('BT %.2F %.2F %.2F %.2F %.2F %.2F Tm (%s) Tj ET',$txt_dx,$txt_dy,$font_dx,$font_dy,$x*$this->k,($this->h-$y)*$this->k,$this->_escape($txt));
		if ($this->ColorFlag)
			$s='q '.$this->TextColor.' '.$s.' Q';
		$this->_out($s);
	}
	
	//barcode
	
}

//###############################################################################
// NO MODIFICAR  NO MODIFICAR NO MODIFICAR NO MODIFICAR NO MODIFICAR NO MODIFICAR
//###############################################################################


//-------------------------------------------------------------------------------


//###############################################################################
//FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF
//###############################################################################


if ( isset($_GET["dni"]) && isset($_GET["apellido"]) && isset($_GET["nombre"]))
{
    
$pdf = new PDF();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);

if ( true )
{

    $datosCredencial = array();
    
    $datosCredencial["dni"] = $_GET["dni"];
    $datosCredencial["nombre"] = $_GET["nombre"];
    $datosCredencial["apellido"] = $_GET["apellido"];

    
	// marco frontal credencial
	$pdf->Rect(20, 20, 85, 65);
	
	// marco frontal foto
	$pdf->Rect(20, 20, 30, 50);
	
	//$pdf->Image('./fotos/logo.png',50,20,55,25,'PNG');
	
	// ------
	// NOMBRE
	
	$pdf->SetFont('Arial','B',14);
	
	$nombre = "Nombre: " . $datosCredencial["nombre"];
	
	//$x = ($pdf->w / 2) - ($pdf->GetStringWidth($nombre) / 2);
	
	$x = 52;
	
	$y = 50;
	
	$pdf->SetXY($x, $y);
	
	$pdf->WriteHTML($nombre);
	
	// NOMBRE
	// ------
	
	
	
	
	// --------
	// APELLIDO
	
	$pdf->SetFont('Arial','B',14);
	
	$apellido = "Apellido: " . $datosCredencial["apellido"];
	
	//$x = ($pdf->w / 2) - ($pdf->GetStringWidth($apellido) / 2);
	
	$x = 52;
	
	$y = 59;
	
	$pdf->SetXY($x, $y);
	
	$pdf->WriteHTML($apellido);
	
	// APELLIDO
	// --------




	// -----
	// SOCIO
	
	$pdf->SetFont('Arial','B',16);
	
	$apellido = "DNI " . str_pad( $datosCredencial["dni"] , 8, '0', STR_PAD_LEFT);
	
	//$x = ($pdf->w / 2) - ($pdf->GetStringWidth($apellido) / 2);
	
	$x = 21;
	
	$y = 73;
	
	$pdf->SetXY($x, $y);
	
	$pdf->WriteHTML($apellido);
	
	// SOCIO
	// -----
	
	
	
	

	// ----
	// FOTO
	/*
	if ( $datosCredencial["foto"] != '' )
	{
	    $fotosocio = "./fotos/" . $datosCredencial["foto"];
		$pdf->Image($fotosocio, 22, 22, 27, 40,'JPG');
		
	}
	*/
	// FOTO
	// ----

}
else 
{
	
	$pdf->Text(15, 15, "Error al generar la credencial.");
	
}

$pdf->Output();

//###############################################################################
//FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF FPDF
//###############################################################################
}
else
{
    echo "Error al generar la credencial";
}

?>