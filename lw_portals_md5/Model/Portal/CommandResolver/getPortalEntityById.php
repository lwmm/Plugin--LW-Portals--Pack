<?php

namespace LwPortalsMd5\Model\Portal\CommandResolver;

class getPortalEntityById
{
    protected $request;
    protected $respone;
    protected $params;
    protected $data;
    protected $QH;

    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
        $this->respone = \LwPortalsMd5\Services\Response::getInstance();
    }
    
    public static function getInstance($params = false, $data = false)
    {
        return new getPortalEntityById($params, $data);
    }
    
    public function resolve()
    {
        $this->QH = new \LwPortalsMd5\Model\Portal\DataHandler\QueryHandler();
        $result = $this->QH->loadPortalById($this->params["id"]);
        
        $entity = new \LwPortalsMd5\Model\Portal\Object\Portal($this->params["id"]);
        $entity->setValues($result);        
        $this->respone->setDataByKey("PortalEntity", $entity);
        return $this->respone;
    }
        
}