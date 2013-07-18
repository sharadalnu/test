<?php

namespace KrowdByz\helpers;

class ClassLoader {
    const EXTENSION = '.php';
    const NAME_SPACE = 'KrowdByz'; 
    const SEPARATOR = '\\';
    
    private $include_path;

    public function __construct() {
      $this->include_path = dirname(__FILE__) . '/..';
    }

    public function register() {
        spl_autoload_register(array($this, 'loadClass'));
    }

    public function unregister() {
        spl_autoload_unregister(array($this, 'loadClass'));
    }

    public function loadClass($clazz) {
        $qualified_name = self::NAME_SPACE . self::SEPARATOR;
        if ($qualified_name === substr($clazz, 0, strlen($qualified_name))) {
            $fileName = '';
            $namespace = '';
            
            if (($lastNsPos = strripos($clazz, self::SEPARATOR)) !== false) {
                $namespace = substr($clazz, strlen(self::NAME_SPACE), $lastNsPos - strlen(self::NAME_SPACE)); // Do not include name space
                $clazz = substr($clazz, $lastNsPos + 1);
                $fileName = str_replace(self::SEPARATOR, DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
            }
            
            $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $clazz) . self::EXTENSION;

            require $this->include_path . $fileName;
        }
    }
}