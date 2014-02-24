<?php

namespace LwPortalsCollector\Model\Portal\CommandResolver;

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
        $this->respone = \LwPortalsCollector\Services\Response::getInstance();
    }
    
    public static function getInstance($params = false, $data = false)
    {
        return new getPortalsCollection($params, $data);
    }
    
    public function resolve()
    {
        $collection = array();

        $QH = new \LwPortalsCollector\Model\Portal\DataHandler\QueryHandler();
        $result = $QH->loadAllPortals();   
        
        foreach($result as $portal){
            $entity = new \LwPortalsCollector\Model\Portal\Object\Portal($portal["id"]);
            $entity->setValues($portal);
            $collection[] = $entity;
        }
        
        $this->respone->setDataByKey("PortalEntitiesCollection", $collection);
        return $this->respone;
    }
}