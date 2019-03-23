<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\ListResource;

class CardList extends ListResource {
	
	public function __construct(Api $api) {
		parent::__construct($api);

		$this->solution = array();
		$this->uri = "v1/cards";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

}
