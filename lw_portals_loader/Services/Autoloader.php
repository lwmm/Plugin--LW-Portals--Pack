<?php

namespace LwPortalsLoader\Services;

class Autoloader
{

    public function __construct()
    {
        spl_autoload_register(array($this, 'loader'));
    }

    private function loader($className) 
    {
        $config = \lw_registry::getInstance()->getEntry("config");
        
        if (strstr($className, 'LwPortalsLoader')) {
            $path = dirname(__FILE__).'/..';
            $filename = str_replace('LwPortalsLoader', $path, $className);
        }
        if (strstr($className, 'LwPortalsList')) {
            $path = $config["plugin_path"]["lw"] . "lw_portals_list";
            $filename = str_replace('LwPortalsList', $path, $className);
        }
        if (strstr($className, 'LwPortalsPlugins')) {
            $path = $config["plugin_path"]["lw"] . "lw_portals_plugins";
            $filename = str_replace('LwPortalsPlugins', $path, $className);
        }
        if (strstr($className, 'LwPortalsPackages')) {
            $path = $config["plugin_path"]["lw"] . "lw_portals_packages";
            $filename = str_replace('LwPortalsPackages', $path, $className);
        }
        if (strstr($className, 'LwPortalsMd5')) {
            $path = $config["plugin_path"]["lw"] . "lw_portals_md5";
            $filename = str_replace('LwPortalsMd5', $path, $className);
        }
        if (strstr($className, 'LwPortalsCollector')) {
            $path = $config["plugin_path"]["lw"] . "lw_portals_collector";
            $filename = str_replace('LwPortalsCollector', $path, $className);
        }
        if (strstr($className, 'LwPortalsTableSearch')) {
            $path = $config["plugin_path"]["lw"] . "lw_portals_table_search";
            $filename = str_replace('LwPortalsTableSearch', $path, $className);
        }
        if (strstr($className, 'LwPortalsConfig')) {
            $path = $config["plugin_path"]["lw"] . "lw_portals_config";
            $filename = str_replace('LwPortalsConfig', $path, $className);
        }
        if (strstr($className, 'LwPortalsHttpsCheck')) {
            $path = $config["plugin_path"]["lw"] . "lw_portals_https_check";
            $filename = str_replace('LwPortalsHttpsCheck', $path, $className);
        }
        
        $filename = str_replace('\\', '/', $filename).'.php';
        if (is_file($filename)) {
            include_once($filename);
        }
    }
}