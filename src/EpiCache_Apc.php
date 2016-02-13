<?php

class EpiCache_Apc implements EpiCacheInterface {

    private $expiry = null;

    public function __construct($params = array()) {
	$this->expiry = !empty($params[0]) ? $params[0] : 3600;
    }

    public function delete($key = null) {
	if (empty($key)) {
	    return null;
	}
	return apc_delete($key = null);
    }

    public function get($key = null) {
	if (empty($key)) {
	    return null;
	} else {
	    $value = apc_fetch($key);
	    return $value;
	}
    }

    public function set($key = null, $value = null, $expiry = null) {
	if (empty($expiry)) {
	    $expiry = $this->expiry;
	}
	if (empty($key) || $value === null) {
	    return false;
	}
	apc_store($key, $value, $expiry);
	return true;
    }

}