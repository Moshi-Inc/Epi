<?php
class EpiSession
{
  const MEMCACHED = 'EpiSession_Memcached';
  const APC = 'EpiSession_Apc';
  const PHP = 'EpiSession_Php';

  // Name of session cookie
  const COOKIE = 'EpiSession';
  private static $instances;

  /*
   * @param  type  required
   * @params optional
   */
  public static function getInstance()
  {
    $params = func_get_args();
    $hash   = md5(implode('.', $params));
    if(isset(self::$instances[$hash]))
      return self::$instances[$hash];

    $type = array_shift($params);
    if(!file_exists($file = dirname(__FILE__) . "/{$type}.php"))
      echo $file;//throw new EpiCacheTypeDoesNotExistException("EpiCache type does not exist: ({$type}).  Tried loading {$file}", 404);

    require_once $file;
    self::$instances[$hash] = new $type($params);
    self::$instances[$hash]->hash = $hash;
    return self::$instances[$hash];
  }
}

interface EpiSessionInterface
{
  public function get($key = null);
  public function set($key = null, $value = null);
}