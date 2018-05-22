<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\InstanceResource;

#amount : "1000" currency : "EUR" feeRuleId : 278 fundingSourceId : 15996 myRef : "My Ref" select0 : "1" select1 : "7" select2 : "8" yourRef : "Their Ref"

class BankTransfer extends InstanceResource {
	protected $pinDigits = array();
	
	public function __construct(Api $api, $accountId, array $pinDigits) {
		parent::__construct($api);

		$this->pinDigits = $pinDigits;

		$this->solution = array(
			'accountId' => $accountId,
		);
		$this->uri = "v1/accounts/$accountId/withdrawal";
	}

	public function transfer($transfer) {
		$feeRules = $this->api->serviceDetails("WITHDRAWAL")->read($this->solution['accountId']);
		$pinGrid = str_split($this->api->pinGrid->read()['positions']);
	
		$postData = array(
			"amount" => $transfer["amount"], 
			"currency" => $transfer["currency"],
			"feeRuleId" => $feeRules["feeRule"]["feeRuleId"],
			"fundingSourceId" => $transfer["payeeId"],
			"myRef" => $transfer["myRef"],
			"yourRef" => $transfer["theirRef"],
			"select0" => $this->pinDigits[$pinGrid[0]-1],
			"select1" => $this->pinDigits[$pinGrid[1]-1],
			"select2" => $this->pinDigits[$pinGrid[2]-1],
		);
    		return $this->api->fetch("POST", $this->uri, null, $postData);
   	}

}
