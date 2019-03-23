<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\ListResource;

class BatchList extends ListResource {
	
	public function __construct(Api $api) {
		parent::__construct($api);

		$this->solution = array();
		$this->uri = "v1/batches";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

	public function create(array $batchDetails) {
		return $this->api->fetch("POST", $this->uri, null, $batchDetails);
	}
}
