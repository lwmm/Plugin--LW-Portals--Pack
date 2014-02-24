<?php

namespace LwPortalsList\Model\Portal\CommandResolver;

class getPortalsCollection
{
    protected $request;
    protected $respone;
    protected $params;
    protected $data;

    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
        $this->respone = \LwPortalsList\Services\Response::getInstance();
    }
    
    public static function getInstance($params = false, $data = false)
    {
        return new getPortalsCollection($params, $data);
    }
    
    public function resolve()
    {
        $collection = array();

        $QH = new \LwPortalsList\Model\Portal\DataHandler\QueryHandler();
        $result = $QH->loadAllPortals();   
        
        foreach($result as $portal){
            $modulesAmount = $QH->getAmountOfInstalledPluginsAndPackagesByPortalId($portal["id"]);
            $portal["plugins"] = $modulesAmount["plugins"];
            $portal["packages"] = $modulesAmount["packages"];
            $entity = new \LwPortalsList\Model\Portal\Object\Portal($portal["id"]);
            $entity->setValues($portal);
            $collection[] = $entity;
        }
        
        $this->respone->setDataByKey("PortalEntitiesCollection", $collection);
        return $this->respone;
    }
}