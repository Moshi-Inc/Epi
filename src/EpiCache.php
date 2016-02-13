<?php

class EpiCache {

    const MEMCACHED = 'EpiCache_Memcached';
    const APC = 'EpiCache_Apc';

    private static $instances, $employ;
    private $hash;

    private function __construct() {
	
    }

    public static function getInstance() {
	$params = func_get_args();
	$hash = md5(json_encode($params));
	if (isset(self::$instances[$hash])) {
	    return self::$instances[$hash];
	}
	$type = $params[0];
	if (!isset($params[1])) {
	    $params[1] = array();
	}
	self::$instances[$hash] = new $type($params[1]);
	self::$instances[$hash]->hash = $hash;
	return self::$instances[$hash];
    }

    public static function employ() {
	if (func_num_args() === 1) {
	    self::$employ = func_get_arg(0);
	} elseif (func_num_args() > 1) {
	    self::$employ = func_get_args();
	}

	return self::$employ;
    }

}

interface EpiCacheInterface {

    public function delete($key = null);

    public function get($key = null);

    public function set($key = null, $value = null, $expiry = null);
}

if (!function_exists('getCache')) {

    function getCache() {
	$employ = EpiCache::employ();
	$class = array_shift($employ);
	if ($employ && class_exists($class)) {
	    return EpiCache::getInstance($class, $employ);
	} elseif (class_exists(EpiCache::APC)) {
	    return EpiCache::getInstance(EpiCache::APC);
	} elseif (class_exists(EpiCache::MEMCACHED)) {
	    return EpiCache::getInstance(EpiCache::MEMCACHED);
	} else {
	    EpiException::raise(new EpiCacheTypeDoesNotExistException('Could not determine which cache handler to load', 404));
	}
    }

}