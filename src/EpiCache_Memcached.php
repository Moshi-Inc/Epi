<?php

class EpiCache_Memcached implements EpiCacheInterface {

    private static $connected = false;
    private $memcached = null;
    private $host = null;
    private $port = null;
    private $compress = null;
    private $expiry = null;

    public function __construct($params = array()) {
	$this->host = !empty($params[0]) ? $params[0] : 'localhost';
	$this->port = !empty($params[1]) ? $params[1] : 11211;
	$this->compress = isset($params[2]) ? $params[2] : 0;
	$this->expiry = isset($params[3]) ? $params[3] : 3600;
    }

    public function delete($key = null) {
	if (!$this->connect() || empty($key))
	    return false;
	return $this->memcached->delete($key);
    }

    public function get($key = null) {
	if (!$this->connect() || empty($key)) {
	    return null;
	} else {
	    $value = $this->memcached->get($key);
	    return $value;
	}
    }

    public function set($key = null, $value = null, $ttl = null) {
	if (!$this->connect() || empty($key) || $value === null)
	    return false;
	$expiry = $ttl === null ? $this->expiry : $ttl;
	$this->memcached->set($key, $value, $expiry);
	return true;
    }

    private function connect() {
	if (self::$connected === true)
	    return true;
	if (class_exists('Memcached')) {
	    $this->memcached = new Memcached;
	    if ($this->memcached->addServer($this->host, $this->port))
		return self::$connected = true;
	    else
		EpiException::raise(new EpiCacheMemcacheConnectException('Could not connect to memcache server'));
	}
	EpiException::raise(new EpiCacheMemcacheClientDneException('No memcache client exists'));
    }

}
