<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\ListResource;

class BankTransferList extends ListResource {
	
	public function __construct(Api $api, $batchId) {
		parent::__construct($api);

		$this->solution = array(
			'batchId' => $batchId,
		);
		$this->uri = "v1/batches/$batchId/banktransfers";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

	public function add($transfer) {
    		return $this->api->fetch("POST", $this->uri, null, $transfer);
	}


}
