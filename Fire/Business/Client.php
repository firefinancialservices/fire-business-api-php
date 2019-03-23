<?php

namespace Fire\Business;

use Fire\Business\Exceptions\ConfigurationException;
use Fire\Business\Exceptions\FireException;
use Fire\Business\Model\LoggedInUser;
use Fire\Http\Client as HttpClient;
use Fire\Http\CurlClient;

class Client {
	protected $_accessToken;
	protected $_baseUrl;
	protected $_httpClient;
	protected $_permissions;
	protected $_businessId;
	protected $_api;

	public function __construct($baseUrl = "https://api.fire.com/business", $accessToken = null) {
		if ($accessToken) {
			$this->_accessToken = $accessToken;
		}

		$this->_baseUrl = $baseUrl;
		$this->_httpClient = new CurlClient();
	}

	protected function getApi() {
		if (!$this->_api) {
			$this->_api = new Api($this, $this->_baseUrl);
		}
		return $this->_api;
	}

	protected function getBusinessId() {
		return $this->_businessId;
	}

	protected function getPermissions() {
		return $this->_permissions;
	}

	protected function getAccounts() {
		return $this->api->accounts;
	}

	protected function contextAccount($accountId) {
		return $this->api->accounts($accountId);
	}

	protected function getCards() {
		return $this->api->cards;
	}

	protected function contextCard($cardId) {
		return $this->api->cards($cardId);
	}

	protected function contextServiceDetails($service) {
		return $this->api->serviceDetails($service);
	}

	protected function getPayees() {
		return $this->api->payees;
	}

	protected function contextPayee($payeeId) {
		return $this->api->payees($payeeId);
	}

	protected function getBatches() {
		return $this->api->batches;
	}

	protected function contextBatch($batchId) {
		return $this->api->batches($batchId);
	}

	protected function getUserDetails() {
		$response = $this->api->userDetails;
		return new LoggedInUser(
			$this->_api, 
			$response
		);
	}

	protected function contextInitialise($config) {
		$response = $this->api->initialise($config);
		$this->_accessToken = $response["accessToken"];
		$this->_permissions = $response["permissions"];
		$this->_businessId = $response["businessId"];

		return $this;
	}


        public function request($method, $uri, $params = array(), $data = array(),
                $headers = array(),
                $timeout = null) {

                $response = $this->_httpClient->request(
                    $method,
                    $uri,
                    $params,
                    $data,
                    $headers,
                    $this->_accessToken,
                    $timeout
                );

		return $response;
        }

	/**
     * Magic getter to lazy load domains
     *
     * @param string $name Domain to return
     * @return \Twilio\Domain The requested domain
     * @throws TwilioException For unknown domains
     */
    public function __get($name) {
	$method = 'get' . ucfirst($name);
	if (method_exists($this, $method)) {
	    return $this->$method();
	}
	throw new FireException('Unknown domain ' . $name);
    }
    /**
     * Magic call to lazy load contexts
     *
     * @param string $name Context to return
     * @param mixed[] $arguments Context to return
     * @return \Twilio\InstanceContext The requested context
     * @throws TwilioException For unknown contexts
     */
    public function __call($name, $arguments) {
	$method = 'context' . ucfirst($name);
	if (method_exists($this, $method)) {
	    return call_user_func_array(array($this, $method), $arguments);
	}
	throw new FireException('Unknown context ' . $name);
    }

}


