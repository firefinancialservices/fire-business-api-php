<?php

namespace Fire\Business;

class InstanceResource {
    protected $api;
    protected $context = null;
    protected $properties = array();
    protected $solution = array();

    public function __construct(Api $api) {
        $this->api = $api;
    }

    public function toArray() {
        return $this->properties;
    }

    public function __toString() {
        return '[InstanceResource]';
    }
}
