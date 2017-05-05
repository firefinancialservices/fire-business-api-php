<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\InstanceResource;

class PayeeDetails extends InstanceResource {
	protected $pinDigits = array();
	protected $totpSeed;
	
	public function __construct(Api $api, $payeeId, array $pinDigits, $totpSeed) {
		parent::__construct($api);

		$this->pinDigits = $pinDigits;
		$this->totpSeed = $totpSeed;

		$this->solution = array(
			'payeeId' => $payeeId,
		);
		$this->uri = "v1/fundingsources/$payeeId";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

        public function transactions() {
                $tl = new TransactionList($this->api, "fundingsources", $this->solution['payeeId']);
                return $tl->read();
        }

}
