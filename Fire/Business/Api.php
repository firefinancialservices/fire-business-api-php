<?php

namespace Fire\Business;

use Fire\Business\Api\AccountList;
use Fire\Business\Exceptions\RestException;
use Fire\Business\Exceptions\FireException;

class Api {
	protected $client;
	protected $baseUrl;

	protected $_accounts = null;

	public function __construct(Client $client) {
		$this->client = $client;
		$this->baseUrl = "https://api.fire.com/bupa/v1";
	}

	protected function getAccounts() {
        	if (!$this->_accounts) {
        		$this->_accounts = new AccountList($this);
        	}
        	return $this->_accounts;
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
		$message = '[HTTP ' . $response->getStatusCode() . '] ' . $header;
		$content = $response->getContent();
		if (is_array($content)) {
			$message .= isset($content['message']) ? ': ' . $content['message'] : '';
		   	$code = isset($content['code']) ? $content['code'] : $response->getStatusCode();
		    	return new RestException($message, $code, $response->getStatusCode());
		} else {
		    	return new RestException($message, $response->getStatusCode(), $response->getStatusCode());
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
