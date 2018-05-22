<?php

namespace Fire\Business;

class ListResource {
    protected $api;
    protected $solution = array();
    protected $uri;

    public function __construct(Api $api) {
        $this->api = $api;
    }

    public function __toString() {
        return '[ListResource]';
    }
}
