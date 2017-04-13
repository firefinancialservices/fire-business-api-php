<?php

namespace Fire\Business\Webhook;

use Fire\Business\Exceptions\ConfigurationException;
use Fire\Business\Model\Transaction;
use Fire\Business\JWT;

/**
 * A Handler for parsing the webhooks sent by Fire 
 *
 * @author Owen O Byrne <owen.obyrne@fire.com>
 *
 */
class Handler {
    protected $mKeyId;
    protected $mSecret;
    
    public function __construct($keyId = null, $secret = null) {
        if (!$keyId || !$secret) {
            throw new ConfigurationException("KeyId and Secret are required to create a Webhook Handler.");
        }
        
        $this->mKeyId = $keyId;
        $this->mSecret = $secret;
        
    }
    
    public function parse($jwt = null) {
        $jsonObj = JWT::decode($jwt, $this->mSecret, false);
	
	$events = array();

	if (is_array($jsonObj)) {
		foreach($jsonObj as $item) {
			$t = new Transaction($item);
        		array_push($events, $t);
		}
	} else {
		$t = new Transaction($jsonObj);
        	array_push($events, $t);
	}	
	
        return $events;
        
    }
}
