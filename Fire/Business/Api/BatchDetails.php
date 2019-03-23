<?php

namespace Fire\Business\Api;

use Fire\Business\Api;
use Fire\Business\InstanceResource;

class BatchDetails extends InstanceResource {
	
	public function __construct(Api $api, $batchId) {
		parent::__construct($api);

		$this->solution = array(
			'batchId' => $batchId,
		);
		$this->uri = "v1/batches/$batchId";
	}

	public function read() {
    		return $this->api->fetch("GET", $this->uri);
   	}

	public function cancel() {
    		return $this->api->fetch("DELETE", $this->uri);
   	}

	public function submit() {
    		return $this->api->fetch("PUT", $this->uri);
   	}

	public function addInternalTransfer($transfer) {
		$internalTransfer = new InternalTransferList($this->api, $this->solution['batchId']);
		return $internalTransfer->add($transfer); 
	}

	protected function getInternalTransfers() {
		return new InternalTransferList($this->api, $this->solution['batchId']);
	}

	protected function contextInternalTransfer($batchItemUuid) {
		return new InternalTransferDetails($this->api, $this->solution['batchId'], $batchItemUuid);
	}

	public function addBankTransfer($transfer) {
		$bankTransfer = new BankTransferList($this->api, $this->solution['batchId']);
		return $bankTransfer->add($transfer); 
	}

	protected function getBankTransfers() {
		return new BankTransferList($this->api, $this->solution['batchId']);
	}

	protected function contextBankTransfer($batchItemUuid) {
		return new BankTransferDetails($this->api, $this->solution['batchId'], $batchItemUuid);
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
