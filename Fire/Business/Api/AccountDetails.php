<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\InstanceResource;

class AccountDetails extends InstanceResource {
	
	public function __construct(Api $api, $accountId) {
		parent::__construct($api);

		$this->solution = array(
			'accountId' => $accountId,
		);
		$this->uri = "v1/accounts/$accountId";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

/*
	// This changes to batches
	public function bankTransfer($transfer) {
		$bt = new BankTransfer($this->api, $this->solution['accountId'], $this->pinDigits);
		return $bt->transfer($transfer);
	}
*/

	// TODO change to internal transfer batch
	public function fireTransfer($transfer) {
		$ft = new FireTransfer($this->api, $this->solution['accountId']);
		return $ft->transfer($transfer);
	}

	public function transactions($options = array()) {
		$tl = new TransactionList($this->api, "accounts", $this->solution['accountId']);
		return $tl->read($options);
	}

}
