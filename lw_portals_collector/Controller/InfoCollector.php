<?php

namespace LwPortalsCollector\Controller;

class InfoCollector
{

    protected $db;
    protected $collection;
    protected $entity;

    public function __construct($db)
    {
        $this->db = $db;
        $this->collection = $this->entity = false;
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
                $this->getInfo($this->entity, $module);
            } elseif ($this->collection) {
                foreach ($this->collection as $entity) {
                    $this->getInfo($entity, $module);
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

    private function cleanModuels()
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_modules m WHERE m.id NOT IN (SELECT pm.mid FROM t:lw_info_portals_modules pm) ");
        $result = $this->db->pselect();

        foreach ($result as $module) {
            $this->db->setStatement("DELETE FROM t:lw_info_modules WHERE id = :id ");
            $this->db->bindParameter("id", "i", $module["id"]);

            $this->db->pdbquery();
        }
    }

}
