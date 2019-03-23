<?php

namespace Fire\Business\Model;

use Fire\Business\InstanceResource;
use Fire\Business\Api;
use Fire\Business\Exceptions\FireException;

class LoggedInUser extends InstanceResource {
    protected $properties = array();
    
    public function __construct(Api $api, array $rawData) {
	parent::__construct($api);

        $this->properties = array(
            'businessProfile' => $rawData['businessProfile'],
            'userProfile' => $rawData['userProfile'],
            'permissions' => $rawData['permissions']
        );
    }


#{"businessProfile":{"businessClientId":"owenobyrne","businessName":"Owen O Byrne","businessType":"SOLE_TRADER_PARTNERSHIP","businessCountry":"IE","businessAddress":{"address1":"7 Beaverbrook","city":"Donabate","county":"Dublin","country":"IE"},"businessDetailsSubmitted":true},"userProfile":{"userId":3138,"userEmail":"owen.obyrne@gmail.com","firstName":"Owen","lastName":"O Byrne","mobileNumber":"+353876394593","cvl":"FULL","lastLogin":"2017-05-04T14:44:16.953Z","pinSubmitted":true},"permissions":["PERM_BUSINESS_DELETE_CONNECTION","PERM_BUSINESS_DELETE_WEBHOOK","PERM_BUSINESS_GET_ACCOUNT","PERM_BUSINESS_GET_ACCOUNT_TRANSACTIONS","PERM_BUSINESS_GET_ACCOUNT_TRANSACTIONS_FILTERED","PERM_BUSINESS_GET_ACCOUNTS","PERM_BUSINESS_GET_APP_PERMISSIONS","PERM_BUSINESS_GET_APPS","PERM_BUSINESS_GET_APPS_PERMISSIONS","PERM_BUSINESS_GET_CARDS","PERM_BUSINESS_GET_COLOURS","PERM_BUSINESS_GET_CONNECTION","PERM_BUSINESS_GET_CONNECTIONS","PERM_BUSINESS_GET_FUNDING_SOURCE","PERM_BUSINESS_GET_FUNDING_SOURCE_TRANSACTIONS","PERM_BUSINESS_GET_FUNDING_SOURCES","PERM_BUSINESS_GET_FX_RATE","PERM_BUSINESS_GET_LIMITS","PERM_BUSINESS_GET_ME","PERM_BUSINESS_GET_MY_AUTHENTICATORSECRET","PERM_BUSINESS_GET_MY_CARD_PIN","PERM_BUSINESS_GET_MY_CARD_TRANSACTIONS","PERM_BUSINESS_GET_MY_CARD_TRANSACTIONS_FILTERED","PERM_BUSINESS_GET_MY_PINGRID","PERM_BUSINESS_GET_OPERATING_COUNTRIES","PERM_BUSINESS_GET_PAYMENT_REQUEST_REPORTS","PERM_BUSINESS_GET_PAYMENT_REQUEST_TRANSACTIONS","PERM_BUSINESS_GET_PAYMENT_REQUESTS","PERM_BUSINESS_GET_PUBLIC_PAYMENT_REQUEST","PERM_BUSINESS_GET_RATES","PERM_BUSINESS_GET_SERVICES","PERM_BUSINESS_GET_USER","PERM_BUSINESS_GET_USERS","PERM_BUSINESS_GET_WEBHOOK_EVENT_TEST","PERM_BUSINESS_GET_WEBHOOK_TOKENS","PERM_BUSINESS_GET_WEBHOOKS","PERM_BUSINESS_POST_ACCOUNT_WITHDRAWAL","PERM_BUSINESS_POST_ACCOUNTS","PERM_BUSINESS_POST_ACCOUNTS_TRANSFER","PERM_BUSINESS_POST_APPS","PERM_BUSINESS_POST_CARDS","PERM_BUSINESS_POST_CONNECTION_EXTERNAL_ACCOUNT_PAY","PERM_BUSINESS_POST_CONNECTION_PAY","PERM_BUSINESS_POST_CONNECTION_PAYMENT_REQUESTS","PERM_BUSINESS_POST_CONNECTIONS","PERM_BUSINESS_POST_FUNDING_SOURCES","PERM_BUSINESS_POST_FX_TRANSFER","PERM_BUSINESS_POST_MY_CARD_ACTIVATE","PERM_BUSINESS_POST_MY_CARD_BLOCK","PERM_BUSINESS_POST_MY_CARD_UNBLOCK","PERM_BUSINESS_POST_MY_PIN_RESET","PERM_BUSINESS_POST_MY_PIN_RESET_INITIATE","PERM_BUSINESS_POST_PAYMENT_REQUEST","PERM_BUSINESS_POST_PIN","PERM_BUSINESS_POST_USERS","PERM_BUSINESS_POST_WEBHOOKS","PERM_BUSINESS_PUT_ACCOUNT","PERM_BUSINESS_PUT_APP","PERM_BUSINESS_PUT_CONNECTION","PERM_BUSINESS_PUT_FUNDING_SOURCE_ARCHIVE","PERM_BUSINESS_PUT_MY_AUTHENTICATORSECRET","PERM_BUSINESS_PUT_PAYMENT_REQUEST_STATUS","PERM_BUSINESS_PUT_USER_DISABLE","PERM_BUSINESS_PUT_USER_RESEND_EMAIL"]}


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
        return '[ Fire.Business.Model.LoggedInUser: ' . implode(' ', $context) . ' ]';
    }

}
