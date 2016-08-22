<?php

namespace Fire\Business\Model

class Transaction {
    protected $properties = array();
    
    public function__construct(array $rawTransaction) {

        $this->properties = array(
            'asdasd' => $rawTransaction['asdasd']
        );
        
    }

    /**
     * Provide a friendly representation
     * 
     * @return string friendly representation
     */
    public function __toString() {
        $context = array();
        foreach ($this->properties as $key => $value) {
            $context[] = "$key=$value";
        }
        return '[Fire.Business.Model.Transaction: ' . implode(' ', $context) . ']';
    }

}