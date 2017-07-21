<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\ListResource;

class AccountList extends ListResource {
	
	public function __construct(Api $api) {
		parent::__construct($api);

		$this->solution = array();
		$this->uri = "v1/accounts";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

	public function newAccount($account) {
                $feeRules = $this->api->serviceDetails("ADD_ACCOUNT")->read();

                $postData = array(
                        "accountName" => $account["accountName"],
                        "colour" => "ORANGE",
                        "feeRuleId" => $feeRules["feeRule"]["feeRuleId"],
                        "currency" => $account["currency"],
                );
                return $this->api->fetch("POST", $this->uri, null, $postData);
        }

}
