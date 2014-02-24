<?php

namespace LwPortalsList\Services;

class Autoloader
{

    public function __construct()
    {
        spl_autoload_register(array($this, 'loader'));
    }

    private function loader($className) 
    {
        if (strstr($className, 'LwPortalsList')) {
            $path = dirname(__FILE__).'/..';
            $filename = str_replace('LwPortalsList', $path, $className);
        }
        
        $filename = str_replace('\\', '/', $filename).'.php';
        if (is_file($filename)) {
            include_once($filename);
        }
    }
}