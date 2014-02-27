<?php

namespace LwPortalsCollector\Controller;

class InfoCollector extends \LwPortalsCollector\Model\Base\DataHandler\InfoCollectorBase
{

    protected $db;
    protected $collection;
    protected $entity;

    public function __construct($db)
    {
        $this->db = $db;
        $this->collection = $this->entity = false;
        ini_set("max_execution_time", 600); #10 min        
    }

    public function setCollection($collection)
    {
        $this->collection = $collection;
    }

    public function setEntity($entity)
    {
        $this->entity = $entity;
    }

    public function execute()
    {
        $moduleFiles = scandir(dirname(__FILE__) . "/../Module");
        unset($moduleFiles[0]);
        unset($moduleFiles[1]);

        foreach ($moduleFiles as $fileName) {
            $module = str_replace(".php", "", $fileName);

            if ($this->entity) {
                if ($module == "Listtool") {
                    if ($this->isPackageLwListoolInstalledByPortalId($this->entity->getId())) {
                        $this->getInfo($this->entity, $module);
                    }
                } else {
                    $this->getInfo($this->entity, $module);
                }
            } elseif ($this->collection) {
                foreach ($this->collection as $entity) {
                    if ($module == "Listtool") {
                        if ($this->isPackageLwListoolInstalledByPortalId($entity->getId())) {
                            $this->getInfo($entity, $module);
                        }
                    } else {
                        $this->getInfo($entity, $module);
                    }
                }
            }
        }
        $this->cleanModuels();
        return true;
    }

    private function getInfo($entity, $module)
    {
        if (substr($entity->getValueByKey("url"), -1) == "/") {
            $url = $entity->getValueByKey("url");
        } else {
            $url = $entity->getValueByKey("url") . "/";
        }

        $json = file_get_contents($url . "index.php?getSystemInfo=1&cmd=" . ucfirst($module));
        $jsonDecodedArray = json_decode($json, true);

        if (is_array($jsonDecodedArray)) {

            $class = "\\LwPortalsCollector\\Module\\" . ucfirst($module);
            if (class_exists($class, true)) {
                $ModuleClass = new $class($this->db);
                $ModuleClass->execute($entity->getId(), $jsonDecodedArray);
            }
        }
    }

}
