<?php

namespace LwPortalsPackages\Model\Package\CommandResolver;

class getPackageCollection
{

    protected $request;
    protected $respone;
    protected $params;
    protected $data;

    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
        $this->respone = \LwPortalsPackages\Services\Response::getInstance();
    }

    public static function getInstance($params = false, $data = false)
    {
        return new getPackageCollection($params, $data);
    }

    public function resolve()
    {
        $collection = array();
        
        $QH = new \LwPortalsPackages\Model\Package\DataHandler\QueryHandler();
        $installationCount = $QH->getAmountOfPortalsWhereAPluginIsInstalled();
        $result = $QH->loadAllPlugins();
        
        foreach($result as $package){
            $package["installs"] = $installationCount[$package["name"]];
            $entity = new \LwPortalsPackages\Model\Package\Object\Package($package["id"]);
            $entity->setValues($package);
            $collection[] = $entity;
        }
        
        $this->respone->setDataByKey("PackagesEntitiesCollection", $collection);
        return $this->respone;
    }

}
