<?php

namespace Fire\Business\Api;

use RobThree\Auth\TwoFactorAuth; 
use Fire\Business\Api;
use Fire\Business\ListResource;

class PayeeList extends ListResource {
	
	public function __construct(Api $api) {
		parent::__construct($api);

		$this->solution = array();
		$this->uri = "v1/fundingsources";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

	
/*
	// This isn't used in v1 of the PHP library - use payment batches instead.
	public function newPayee($payee) {
		$pinGrid = str_split($this->api->pinGrid->read()['positions']);
		$totp = new TwoFactorAuth("Fire Business Account");
	
		$postData = array(
			"accountName" => $payee["accountName"], 
			"accountHolderName" => $payee["accountHolderName"], 
			"currency" => $payee["currency"],
			"iban" => @$payee["iban"],
			"nsc" => @$payee["nsc"],
			"accountNumber" => @$payee["accountNumber"],
			"authenticatorToken" => $totp->getCode($this->totpSeed),
			"select0" => $this->pinDigits[$pinGrid[0]-1],
			"select1" => $this->pinDigits[$pinGrid[1]-1],
			"select2" => $this->pinDigits[$pinGrid[2]-1],
		);
    		return $this->api->fetch("POST", $this->uri, null, $postData);
   	}
*/
}
