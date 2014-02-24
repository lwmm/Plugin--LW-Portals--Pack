<?php

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

$config = parse_ini_file("/var/www/c38/lw_configs/conf.inc.php", true);

class Autoloader
{

    public function __construct($config)
    {
        $this->config = $config;
        spl_autoload_register(array($this, 'loader'));
    }

    private function loader($className)
    {
        if (strstr($className, 'LwPortalsCollector')) {
            $path = $this->config["plugin_path"]["lw"] . "lw_portals_collector";
            $filename = str_replace('LwPortalsCollector', $path, $className);
        }

        if (strstr($className, 'lw_')) {
            $path = $this->config["path"]["framework"]."lw/";
            $filename = $path . $className.".class";
        }

        $filename = str_replace('\\', '/', $filename) . '.php';
        
        if (is_file($filename)) {
            include_once($filename);
        }
    }

}

$autoloader = new Autoloader($config);
if ($config["lwdb"]["type"] == "mysql" || $config["lwdb"]["type"] == "mysqli") {
    include_once($config["path"]["framework"] . "lw/lw_db_mysqli.class.php");
    $db = new \lw_db_mysqli($config["lwdb"]["user"], $config["lwdb"]["pass"], $config["lwdb"]["host"], $config["lwdb"]["name"]);
    $db->connect();
} elseif ($config["lwdb"]["type"] == "oracle") {
    include_once($config["path"]["framework"] . "lw/lw_db_oracle.class.php");
    $db = new \lw_db_oracle($config["lwdb"]["user"], $config["lwdb"]["pass"], $config["lwdb"]["host"], $config["lwdb"]["name"]);
    $db->connect();
}

$db->setStatement("SELECT * FROM t:lw_info_portals WHERE scan_exclude = 0 ORDER BY name ");
$result = $db->pselect();

$collection = array();
foreach($result as $portal){
    $entity = new \LwPortalsCollector\Model\Portal\Object\Portal($portal["id"]);
    $entity->setValues($portal);
    $collection[] = $entity;
}

$collector = new \LwPortalsCollector\Controller\InfoCollector($db);
$collector->setCollection($collection);
$collector->execute();