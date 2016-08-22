<?php

namespace Fire\Business\Webhook;

use Fire\Business\Exceptions\ConfigurationException;
use Fire\Business\Model\Transaction;
use Fire\Business\JWT;

/**
 * A Handler for parsing the webhooks sent by Fire 
 *
 * @author Owen O Byrne <owen.obyrne@paywithfire.com>
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
        $p = JWT::decode($jwt, $this->mSecret, false);

	$t = new Transaction($p);

	$events = array();
        array_push($events, $t);
        
        return $events;
        
    }
}
