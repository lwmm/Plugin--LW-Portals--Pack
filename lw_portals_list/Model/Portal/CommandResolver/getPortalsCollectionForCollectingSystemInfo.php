<?php

namespace LwPortalsList\Model\Portal\CommandResolver;

class getPortalsCollectionForCollectingSystemInfo
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
        return new getPortalsCollectionForCollectingSystemInfo($params, $data);
    }
    
    public function resolve()
    {
        $collection = array();

        $QH = new \LwPortalsList\Model\Portal\DataHandler\QueryHandler();
        $result = $QH->loadAllPortalsWhereScanIsAllowed();   
        
        foreach($result as $portal){
            $entity = new \LwPortalsList\Model\Portal\Object\Portal($portal["id"]);
            $entity->setValues($portal);
            $collection[] = $entity;
        }
        
        $this->respone->setDataByKey("PortalEntitiesCollection", $collection);
        return $this->respone;
    }
}