<?php

namespace Fire\Business\Model;

use Fire\Business\InstanceResource;
use Fire\Business\Api;
use Fire\Business\Exceptions\FireException;

class Session extends InstanceResource {
    protected $properties = array();
    
    public function __construct(Api $api, array $rawData) {
	parent::__construct($api);

        $this->properties = array(
            'businessId' => $rawData['businessId'],
            'apiApplicationId' => $rawData['apiApplicationId'],
	    'expiry' => $rawData['expiry'],
	    'accessToken' => $rawData['accessToken'],
            'permissions' => $rawData['permissions']
        );
        
    }


    /**
     * Magic getter to access properties
     * 
     * @param string $name Property to access
     * @return mixed The requested property
     * @throws TwilioException For unknown properties
     */
    public function __get($name) {
        if (array_key_exists($name, $this->properties)) {
            return $this->properties[$name];
        }
        if (property_exists($this, '_' . $name)) {
            $method = 'get' . ucfirst($name);
            return $this->$method();
        }
        throw new FireException('Unknown property: ' . $name);
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
        return '[ Fire.Business.Model.Session: ' . implode(' ', $context) . ' ]';
    }

}
