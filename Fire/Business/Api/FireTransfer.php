<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\InstanceResource;

class FireTransfer extends InstanceResource {
	
	public function __construct(Api $api, $accountId) {
		parent::__construct($api);

		$this->solution = array(
			'accountId' => $accountId,
		);
		$this->uri = "v1/accounts/transfer";
	}

	public function transfer($transfer) {
		$postData = array(
			"amount" => $transfer["amount"], 
			"currency" => $transfer["currency"],
			"icanFrom" => $this->solution['accountId'],
			"icanTo" => $transfer["destinationAccountId"],
			"ref" => $transfer["myRef"],
		);
print_r($postData);
    		return $this->api->fetch("POST", $this->uri, null, $postData);
   	}

}
