<?php

namespace Fire\Business\Api;

use OTPHP\TOTP;
use Fire\Business\Api;
use Fire\Business\ListResource;

class PayeeList extends ListResource {
	protected $totpSeed;
	protected $pinDigits = array();
	
	public function __construct(Api $api, array $pinDigits, $totpSeed) {
		parent::__construct($api);

		$this->pinDigits = $pinDigits;
		$this->totpSeed = $totpSeed;

		$this->solution = array();
		$this->uri = "v1/fundingsources";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

	
	public function newPayee($payee) {
		$pinGrid = str_split($this->api->pinGrid->read()['positions']);
		$totp = new TOTP("Fire Business Account", $this->totpSeed);
	
		$postData = array(
			"accountName" => $payee["accountName"], 
			"accountHolderName" => $payee["accountHolderName"], 
			"currency" => $payee["currency"],
			"iban" => @$payee["iban"],
			"nsc" => @$payee["nsc"],
			"accountNumber" => @$payee["accountNumber"],
			"authenticatorToken" => $totp->now(),
			"select0" => $this->pinDigits[$pinGrid[0]-1],
			"select1" => $this->pinDigits[$pinGrid[1]-1],
			"select2" => $this->pinDigits[$pinGrid[2]-1],
		);
    		return $this->api->fetch("POST", $this->uri, null, $postData);
   	}

}
