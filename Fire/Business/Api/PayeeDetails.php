<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\InstanceResource;

class PayeeDetails extends InstanceResource {
	protected $pinDigits = array();
	
	public function __construct(Api $api, $payeeId, array $pinDigits) {
		parent::__construct($api);

		$this->pinDigits = $pinDigits;

		$this->solution = array(
			'payeeId' => $payeeId,
		);
		$this->uri = "v1/fundingsources/$payeeId";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

	public function archive() {
    		return $this->api->fetch("PUT", $this->uri . "/archive");
   	}

        public function transactions() {
                $tl = new TransactionList($this->api, "fundingsources", $this->solution['payeeId']);
                return $tl->read();
        }

}
