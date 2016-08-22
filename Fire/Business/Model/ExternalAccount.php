<?php

namespace Fire\Business\Model;

class ExternalAccount {
    protected $properties = array();
    
    public function __construct(\stdClass $rawData) {

        $this->properties = array(
            'id' => $rawData->{'id'},
            'alias' => $rawData->{'alias'},
            'nsc' => $rawData->{'nsc'},
            'accountNumber' => $rawData->{'accountNumber'},
            'bic' => $rawData->{'bic'},
            'iban' => $rawData->{'iban'}                
        );
        
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
        return '[ Fire.Business.Model.ExternalAccount: ' . implode(' ', $context) . ' ]';
    }

}
