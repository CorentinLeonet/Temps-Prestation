<?php
	class Adresse
	{
		public $id;
		public $rue = "";
		public $numero = "";
		public $codePostal = "";
		public $ville = "";
		
		public function __construct($r, $n, $c, $v)
		{
			$this->rue = $r;
			$this->numero = $n;
			$this->codePostal = $c;
			$this->ville = $v;
		}
		
		public function toString()
		{
			return $this->rue.', '.$this->numero.', '.$this->codePostal.', '.$this->ville;
		}

	}