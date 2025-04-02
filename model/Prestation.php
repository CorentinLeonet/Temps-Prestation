<?php
	require_once("../lib/fpdf/fpdf.php");

	class Prestation
	{
		public $id;
		public $datePrestation;
		public $tempsPrestation;
		public $description;
		public $tarif;
		public $urgent;
		public $total;
		public $tva;
		
		public $entreprise;
		
		public $client;
		
		
		public function __construct()
		{
		}
		
		public function setValues($date, $temps, $desc, $tarif, $tva)
		{
			$this->datePrestation = $date;
			$this->tempsPrestation = $temps;
			$this->description = $desc;
			$this->tarif = $tarif;
			$this->total = $tarif * ($temps/3600);
			$this->tva = $tva;
		}
		
		public function createFacture()
		{
			ob_end_clean();

			$pdf = new FPDF('P','mm','A4'); //facture pdf
			$pdf->AddPage();
			$pdf->SetFont('Arial','B',20);
			$pdf->Cell(80,10,'Facture : ',2);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->SetFont('Arial','I',11);
			$pdf->Cell(140);
			$pdf->Cell(80,10,'Numero de facture :'.$this->id,2);
			$pdf->Ln();
			$pdf->Cell(80,10,'Entreprise : '.utf8_decode($this->entreprise->nom),2);
			$pdf->Cell(60);
			$pdf->Cell(80,10,'Date : '.date('Y-m-d',strtotime($this->datePrestation)),2);
			$pdf->Ln();
			$pdf->Cell(80,10,'Adresse : '.utf8_decode($this->entreprise->adresse->toString()),2);
			$pdf->Cell(60);
			$pdf->Cell(80,10,'Heure : '.date('H:i:s', strtotime($this->datePrestation)),2);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Cell(140, 10, 'Client : '.utf8_decode($this->client->nom.' '.$this->client->prenom));
			$pdf->Ln();
			$pdf->Cell(80,10,'Adresse : '.utf8_decode($this->client->adresse->toString()),2);
			$pdf->Ln();
			$pdf->Ln();
			$pdf->Cell(90,10,'Temps de prestation : ',1);
			$pdf->Cell(90,10,gmdate("H:i:s",$this->tempsPrestation),1);
			$pdf->Ln();

			$pdf->Cell(90,10,'Description de la prestation : ',1);
			$pdf->Cell(90,10,utf8_decode($this->description),1);
			$pdf->Ln();
			
			$pdf->Cell(90,10,'Tarif : ',1);
			$pdf->Cell(90,10,$this->tarif.chr(128),1);
			$pdf->Ln();
			$pdf->Cell(90,10,'Urgence : ',1);
			if($this->urgent){
				$pdf->Cell(90,10,'Oui',1);
			}
			else{
				$pdf->Cell(90,10,'Non',1);
			}
			$pdf->Ln();
			$pdf->Ln();
			$pdf->SetFont('Arial','B',12);
			$pdf->Cell(90,10,'Total HT: ',1);
			$pdf->Cell(90,10, number_format($this->total,2).chr(128) ,1);
			$pdf->Ln();
			$pdf->Cell(90,10,'Total TVA: ',1);
			$pdf->Cell(90,10, number_format(($this->total * $this->tva)- $this->total,2).chr(128) ,1);
			$pdf->Ln();
			$pdf->Cell(90,10,'Total TTC: ',1);
			$pdf->Cell(90,10, number_format($this->total * $this->tva, 2).chr(128) ,1);

			$pdf->Output('D', 'facture.pdf', true);
		
		}
	}