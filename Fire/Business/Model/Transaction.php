<?php

namespace Fire\Business\Model;

use Fire\Deserialize;

class Transaction {
    protected $properties = array();
    
    public function __construct(\stdClass $rawTransaction) {

        if (is_string(@$rawTransaction->{'type'})) {
            // This is probably a test webhook - stupid bug
            $type = $rawTransaction->{'type'};
        } else {
            $type = $rawTransaction->{'txnType'}->{'type'};
        }

        $this->properties = array(
            'type' => $type,
            'amountBeforeCharges' => $rawTransaction->{'amountBeforeCharges'},
            'currency' => $rawTransaction->{'currency'}->{'code'},
            'feeAmount' => $rawTransaction->{'feeAmount'},
            'amountAfterCharges' => $rawTransaction->{'amountAfterCharges'},
            'balance' => $rawTransaction->{'balance'},
            'description' => property_exists($rawTransaction, 'myRef') ? $rawTransaction->{'myRef'} : null,
            'date' => Deserialize::iso8601DateTime($rawTransaction->{'date'})
        );
        
        if ($rawTransaction->{'from'} instanceof \stdClass) {
            $type = $rawTransaction->{'from'}->{'type'};
            
            if ($type == "WITHDRAWAL_ACCOUNT" || $type == "EXTERNAL_ACCOUNT") {
                $this->properties["from"] = new ExternalAccount($rawTransaction->{'from'}->{'account'});
            } else if ($type == "FIRE_ACCOUNT") {
                $this->properties["from"] = new FireAccount($rawTransaction->{'from'}->{'account'});
            } else if ($type == "USER") {
                $this->properties["from"] = new User($rawTransaction->{'from'}->{'user'});
            }
        }
        
        if ($rawTransaction->{'to'} instanceof \stdClass) {
            $type = $rawTransaction->{'to'}->{'type'};
            
            if ($type == "WITHDRAWAL_ACCOUNT" || $type == "EXTERNAL_ACCOUNT") {
                $this->properties["to"] = new ExternalAccount($rawTransaction->{'to'}->{'account'});
            } else if ($type == "FIRE_ACCOUNT") {
                $this->properties["to"] = new FireAccount($rawTransaction->{'to'}->{'account'});
            } else if ($type == "USER") {
                $this->properties["to"] = new User($rawTransaction->{'to'}->{'user'});
            }
        }
    }

    public function getType() {
        return $this->properties["type"];
    }
  
    public function get($key) {
        return $this->properties[$key];
    }

    /**
     * Provide a friendly representation
     * 
     * @return string friendly representation
     */
    public function __toString() {
        $context = array();
        foreach ($this->properties as $key => $value) {
            if ($value instanceof \DateTime) {
                $context[] = "$key=".$value->format('Y-m-d\TH:i:s');
            } else { 
                $context[] = "$key=$value";
            }
        }
        return '[ Fire.Business.Model.Transaction: ' . implode(' ', $context) . ' ]';
    }

}
