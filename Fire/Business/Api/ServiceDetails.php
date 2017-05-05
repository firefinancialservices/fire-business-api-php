<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\InstanceResource;

class ServiceDetails extends InstanceResource {
	
	public function __construct(Api $api, $service) {
		parent::__construct($api);

		$this->solution = array(
			'service' => $service,
		);
		$this->uri = "v1/services/$service";
	}

	public function read($accountId) {
    		return $this->api->fetch("GET", $this->uri, array("ican" => $accountId));
   	}

}
