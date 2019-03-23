<?php

namespace Fire\Business;

use Fire\Business\Api\Login;
use Fire\Business\Api\AccountList;
use Fire\Business\Api\AccountDetails;
use Fire\Business\Api\CardList;
use Fire\Business\Api\CardDetails;
use Fire\Business\Api\PayeeList;
use Fire\Business\Api\PayeeDetails;
use Fire\Business\Api\BatchList;
use Fire\Business\Api\BatchDetails;
use Fire\Business\Api\UserDetails;
use Fire\Business\Api\PinGrid;
use Fire\Business\Api\ServiceDetails;
use Fire\Business\Exceptions\RestException;
use Fire\Business\Exceptions\FireException;

class Api {
	protected $client;
	protected $baseUrl;

	// cache the results of these lists
	protected $_userDetails = null;
	protected $_accounts = null;
	protected $_cards = null;
	protected $_payees = null;

	public function __construct(Client $client, $baseUrl) {
		$this->client = $client;
		$this->baseUrl = $baseUrl;
	}

	protected function contextInitialise($config) {
        	$login = new Login($this);
        	return $login->initialise($config);
    	}

	protected function getAccounts($useCache = true) {
        	if (!$this->_accounts || !$useCache) {
        		$this->_accounts = new AccountList($this);
        	}
        	return $this->_accounts;
    	}

	protected function contextAccounts($accountId) {
		return new AccountDetails($this, $accountId);
	}

	protected function getCards($useCache = true) {
        	if (!$this->_cards || !$useCache) {
        		$this->_cards = new CardList($this);
        	}
        	return $this->_cards;
    	}

	protected function contextCards($cardId) {
		return new CardDetails($this, $cardId);
	}

	protected function getPayees($useCache = true) {
        	if (!$this->_payees || !$useCache) {
        		$this->_payees = new PayeeList($this);
        	}
        	return $this->_payees;
    	}

	protected function contextPayees($payeeId) {
		return new PayeeDetails($this, $payeeId);
	}

	protected function getBatches() {
        	return new BatchList($this);
    	}

	protected function contextBatches($batchId) {
		return new BatchDetails($this, $batchId);
	}

	protected function contextServiceDetails($service) {
        	return new ServiceDetails($this, $service);
    	}


	protected function getUserDetails() {
        	if (!$this->_userDetails) {
        		$this->_userDetails = new UserDetails($this);
        	}
        	return $this->_userDetails;
    	}


	public function fetch($method, $uri, $params = array(), $data = array(), 
		$headers = array()) {

		$response = $this->request(
		    $method,
		    $uri,
		    $params,
		    $data,
		    $headers,
		   15 
		);
		if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
		    throw $this->exception($response, 'Unable to fetch record');
		}
		return $response->getContent();

	}

	public function request($method, $uri, $params = array(), $data = array(),
                $headers = array(),
                $timeout = null) {
        	
		$url = $this->baseUrl . "/" . $uri;
		return $this->client->request(
		    $method,
		    $url,
		    $params,
		    $data,
		    $headers,
		    $timeout
		);
	}


        protected function exception($response, $header) {
                $content = $response->getContent();
                if (is_array($content)) {
                        $message = isset($content['errors'][0]['message']) ? $content['errors'][0]['message'] : '';
                        $code = isset($content['errors'][0]['code']) ? $content['errors'][0]['code'] : $response->getStatusCode();
                        return new RestException($message, $code, $response->getStatusCode());
                } else {
                        return new RestException($header, $response->getStatusCode(), $response->getStatusCode());
                }
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
