<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\ListResource;

class Login extends ListResource {
	
	public function __construct(Api $api) {
		parent::__construct($api);

		$this->solution = array();
		$this->uri = "v1/apps/accesstokens";
	}

	public function initialise($config) {

		$nonce = time();

		$clientSecret = hash("sha256", $nonce . $config["clientKey"], false);

		$postData = array(
			"clientId" => $config["clientId"],
			"refreshToken" => $config["refreshToken"],
			"clientSecret" => $clientSecret,
			"grantType" => "AccessToken",
			"nonce" => $nonce
		);
    		return $this->api->fetch("POST", $this->uri, null, $postData);
   	}

}
