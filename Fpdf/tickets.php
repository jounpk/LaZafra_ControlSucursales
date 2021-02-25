<?php
require('fpdf.php');

class newPDF extends FPDF
{


	function Header()
	{
		$image = $_SESSION['logoEncabezado'];
		unset($_SESSION['logoEncabezado']);
		$nSuc = $_SESSION['nSuc'];
		unset($_SESSION['nSuc']);
		$dirSuc = $_SESSION['dirSuc'];
		unset($_SESSION['dirSuc']);
		$idEmpresa = $_SESSION['idEmpresa'];
		unset($_SESSION['idEmpresa']);
		if ($idEmpresa == 3) {
			$dX = 15;
			$dY = 5;
			$dZ = 50;
			$ln = 14;
		} elseif ($idEmpresa == 2) {
			$dX = 26;
			$dY = 2;
			$dZ = 28;
			$ln = 17;
		}else{
			$dX = 26;
			$dY = 2;
			$dZ = 25;
			$ln = 21;
		}

				$this->Ln(5);
				$this->Image($image,$dX,$dY,$dZ,'C'); # Image(URL,separación a la izquierda, separación arriba, tamaño, alineacion)
				$this->SetFont('Arial','B',12);
				$this->SetY(8);
				$this->Ln($ln);
				#$this->SetTextColor(17,51,75);
				#$this->MultiCell(0,5,utf8_decode("Formato de Cotización"),0,'C',0);
				#$this->Ln(2);
				$this->MultiCell(0,5,utf8_decode($nSuc),0,'C',0);
				$this->Ln(1);
				#$this->Cell(35,5,'',0,0,'C');
				$this->SetFont('Arial','B',9);
				$this->MultiCell(0,3,utf8_decode($dirSuc),0,'C',0);

				$this->Ln(3);

	}

	function Footer(){
	#	$this->SetY(-20);
	#	$this->Cell(0,8,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
	}
	################################################################################################
	#     Comienza el codigo para marca de Agua
	####################################################################################################
#/*
	var $angle=0;

	function Rotate($angle,$x=-1,$y=-1)
	{
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
					$this->_out(sprintf('q %.5F %.5F %.5F %.5F %.2F %.2F cm 1 0 0 1 %.2F %.2F cm',$c,$s,-$s,$c,$cx,$cy,-$cx,-$cy));
			}
	}

	function _endpage()
	{
			if($this->angle!=0)
			{
					$this->angle=0;
					$this->_out('Q');
			}
			parent::_endpage();
	}

	function RotatedText($x,$y,$txt,$angle)
	{
		//Text rotated around its origin
		$this->Rotate($angle,$x,$y);
		$this->Text($x,$y,$txt);
		$this->Rotate(0);
	}

	function RotatedImage($file,$x,$y,$w,$h,$angle)
	{
		//Image rotated around its upper-left corner
		$this->Rotate($angle,$x,$y);
		$this->Image($file,$x,$y,$w,$h);
		$this->Rotate(0);
	}
#*/
################################################################################################
#     Comienza el codigo para los MultiCell (para ajustar el texto)
####################################################################################################

var $widths;
var $aligns;

function SetWidths($w)
{
	//Set the array of column widths
	$this->widths=$w;
}

function SetAligns($a)
{
	//Set the array of column alignments
	$this->aligns=$a;
}

function WriteHTML($html)
{
    // Intérprete de HTML
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

function Row($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border
		$this->Rect($x,$y,$w,$h);
		//Print the text
		$this->MultiCell($w,5,$data[$i],0,$a);
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function Row2($data)
{
	//Calculate the height of the row
	$nb=0;
	for($i=0;$i<count($data);$i++)
		$nb=max($nb,$this->NbLines($this->widths[$i],$data[$i]));
	$h=5*$nb;
	//Issue a page break first if needed
	$this->CheckPageBreak($h);
	//Draw the cells of the row
	for($i=0;$i<count($data);$i++)
	{
		$w=$this->widths[$i];
		$a=isset($this->aligns[$i]) ? $this->aligns[$i] : 'L';
		//Save the current position
		$x=$this->GetX();
		$y=$this->GetY();
		//Draw the border    Rect(x,y,w,h) ==> Rect(UbicaciónEnX,UbicaciónEny,LineasHorizontales,LineasVertical)
		$this->Rect($x,$y,0,0);
		//Print the text
		$this->MultiCell($w,5,$data[$i],0,$a);
		//Put the position to the right of the cell
		$this->SetXY($x+$w,$y);
	}
	//Go to the next line
	$this->Ln($h);
}

function CheckPageBreak($h)
{
	//If the height h would cause an overflow, add a new page immediately
	if($this->GetY()+$h>$this->PageBreakTrigger)
		$this->AddPage($this->CurOrientation);
}

function NbLines($w,$txt)
{
	//Computes the number of lines a MultiCell of width w will take
	$cw=&$this->CurrentFont['cw'];
	if($w==0)
		$w=$this->w-$this->rMargin-$this->x;
	$wmax=($w-2*$this->cMargin)*1000/$this->FontSize;
	$s=str_replace("\r",'',$txt);
	$nb=strlen($s);
	if($nb>0 and $s[$nb-1]=="\n")
		$nb--;
	$sep=-1;
	$i=0;
	$j=0;
	$l=0;
	$nl=1;
	while($i<$nb)
	{
		$c=$s[$i];
		if($c=="\n")
		{
			$i++;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
			continue;
		}
		if($c==' ')
			$sep=$i;
		$l+=$cw[$c];
		if($l>$wmax)
		{
			if($sep==-1)
			{
				if($i==$j)
					$i++;
			}
			else
				$i=$sep+1;
			$sep=-1;
			$j=$i;
			$l=0;
			$nl++;
		}
		else
			$i++;
	}
	return $nl;
}
}
?>
