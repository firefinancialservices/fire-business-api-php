<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\InstanceResource;

class CardDetails extends InstanceResource {
	
	public function __construct(Api $api, $cardId) {
		parent::__construct($api);

		$this->solution = array(
			'cardId' => $cardId,
		);
		$this->uri = "v1/cards/$cardId";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

	public function transactions($options = array()) {
		$tl = new TransactionList($this->api, "cards", $this->solution['cardId']);
		return $tl->read($options);
	}

}
