<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\InstanceResource;

class BankTransferDetails extends InstanceResource {
	
	public function __construct(Api $api, $batchUuid, $batchItemUuid) {
		parent::__construct($api);

		$this->solution = array(
			'batchUuid' => $batchUuid,
			'batchItemUuid' => $batchItemUuid
		);
		$this->uri = "v1/batches/$batchUuid/banktransfers/$batchItemUuid";
	}

	public function delete() {
    		return $this->api->fetch("DELETE", $this->uri);
	}


}
