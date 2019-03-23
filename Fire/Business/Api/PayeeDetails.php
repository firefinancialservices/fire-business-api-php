<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\InstanceResource;

class PayeeDetails extends InstanceResource {
	
	public function __construct(Api $api, $payeeId) {
		parent::__construct($api);

		$this->solution = array(
			'payeeId' => $payeeId,
		);
		$this->uri = "v1/fundingsources/$payeeId";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

        public function transactions($options = array()) {
                $tl = new TransactionList($this->api, "fundingsources", $this->solution['payeeId']);
                return $tl->read($options);
        }

}
