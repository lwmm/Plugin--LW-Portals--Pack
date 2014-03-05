<?php

namespace LwPortalsHttpsCheck\Model\Portal\CommandResolver;

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
        $this->respone = \LwPortalsHttpsCheck\Services\Response::getInstance();
    }
    
    public static function getInstance($params = false, $data = false)
    {
        return new getPortalsCollection($params, $data);
    }
    
    public function resolve()
    {
        $collection = array();

        $QH = new \LwPortalsHttpsCheck\Model\Portal\DataHandler\QueryHandler();
        $result = $QH->loadAllPortals();   
        
        foreach($result as $portal){
            $entity = new \LwPortalsHttpsCheck\Model\Portal\Object\Portal($portal["id"]);
            $entity->setValues($portal);
            $collection[] = $entity;
        }
        
        $this->respone->setDataByKey("PortalEntitiesCollection", $collection);
        return $this->respone;
    }
}