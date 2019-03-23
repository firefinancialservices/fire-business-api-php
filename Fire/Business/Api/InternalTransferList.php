<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\ListResource;

class InternalTransferList extends ListResource {
	
	public function __construct(Api $api, $batchId) {
		parent::__construct($api);

		$this->solution = array(
			'batchId' => $batchId,
		);
		$this->uri = "v1/batches/$batchId/internaltransfers";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

	// TODO change to internal transfer batch
	public function add($transfer) {
    		return $this->api->fetch("POST", $this->uri, null, $transfer);
	}


}
