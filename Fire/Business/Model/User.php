<?php

namespace Fire\Business\Model;

class User {
    protected $properties = array();
    
    public function __construct(\stdClass $rawUser) {

        $this->properties = array(
            'alias' => $rawUser->{'alias'},
            'mobilePhoneNumber' => $rawUser->{'mobilePhoneNumber'},
            'imageUrl' => $rawUser->{'imageUrl'}
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
        return '[ Fire.Business.Model.User: ' . implode(' ', $context) . ' ]';
    }

}
