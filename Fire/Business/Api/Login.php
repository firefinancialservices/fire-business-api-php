<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\ListResource;

class Login extends ListResource {
	
	public function __construct(Api $api) {
		parent::__construct($api);

		$this->solution = array();
		$this->uri = "login";
	}

	public function login($businessId, $email, $password) {
		$postData = array(
			"businessClientId" => $businessId,
			"emailAddress" => $email,
			"password" => $password
		);
    		return $this->api->fetch("POST", $this->uri, null, $postData);
   	}

}
