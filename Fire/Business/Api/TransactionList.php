<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\ListResource;

class TransactionList extends ListResource {
	
	public function __construct(Api $api, $type, $id) {
		parent::__construct($api);

		$this->solution = array(
			'type' => $type, 
			'id' => $id,
		);
		$this->uri = "v1/$type/$id/transactions";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

}
