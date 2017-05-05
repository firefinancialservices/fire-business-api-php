<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\InstanceResource;

class AccountDetails extends InstanceResource {
	protected $pinDigits = array();
	
	public function __construct(Api $api, $accountId, array $pinDigits) {
		parent::__construct($api);
		$this->pinDigits = $pinDigits;

		$this->solution = array(
			'accountId' => $accountId,
		);
		$this->uri = "v1/accounts/$accountId";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

	public function banktransfer($transfer) {
		$bt = new BankTransfer($this->api, $this->solution['accountId'], $this->pinDigits);
		return $bt->transfer($transfer);
	}

	public function firetransfer($transfer) {
		$bt = new FireTransfer($this->api, $this->solution['accountId']);
		return $bt->transfer($transfer);
	}

	public function transactions() {
		$tl = new TransactionList($this->api, "accounts", $this->solution['accountId']);
		return $tl->read();
	}

}
