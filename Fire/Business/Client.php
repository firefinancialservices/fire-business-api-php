<?php

namespace Fire\Business;

use Fire\Business\Exceptions\ConfigurationException;
use Fire\Business\Exceptions\FireException;
use Fire\Business\Model\LoggedInUser;
use Fire\Http\Client as HttpClient;
use Fire\Http\CurlClient;

class Client {
	protected $authorizationToken;
	protected $httpClient;
	protected $_api;

	public function __construct($authorizationToken = null) {
		if ($authorizationToken) {
			$this->authorizationToken = $authorizationToken;
		}

		$this->httpClient = new CurlClient();
	}

	protected function getApi() {
		if (!$this->_api) {
			$this->_api = new Api($this);
		}
		return $this->_api;
	}

	protected function getAccounts() {
		return $this->api->accounts;
	}

	protected function contextAccount($accountId) {
		return $this->api->accounts($accountId);
	}

	protected function getPinGrid() {
		return $this->api->pinGrid;
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

	protected function getUserDetails() {
		$response = $this->api->userDetails;
		return new LoggedInUser(
			$this->_api, 
			$response
		);
	}

	protected function contextLogin($businessId, $email, $password, $pinDigits, $totpSeed) {
		$response = $this->api->login($businessId, $email, $password, $pinDigits, $totpSeed);
		return new LoggedInUser(
			$this->_api, 
			$response
		);
	}


        public function request($method, $uri, $params = array(), $data = array(),
                $headers = array(),
                $timeout = null) {

                $response = $this->httpClient->request(
                    $method,
                    $uri,
                    $params,
                    $data,
                    $headers,
                    $this->authorizationToken,
                    $timeout
                );

		if (array_key_exists("at", $response->getHeaders())) {
			$this->authorizationToken = $response->getHeaders()["at"];
			file_put_contents(".fire.token.cache",$response->getHeaders()["at"]);
		}

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


