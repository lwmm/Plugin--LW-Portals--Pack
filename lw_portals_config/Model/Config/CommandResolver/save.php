<?php

namespace LwPortalsConfig\Model\Config\CommandResolver;

class save
{

    protected $respone;
    protected $params;
    protected $data;

    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
        $this->respone = \LwPortalsConfig\Services\Response::getInstance();
    }

    public static function getInstance($params = false, $data = false)
    {
        return new save($params, $data);
    }

    public function resolve()
    {
        $portalsConfig = $this->data["PortalsConfig"];
        $portalsConfigString = serialize($portalsConfig);
        
        $QH = new \LwPortalsConfig\Model\Config\DataHandler\QueryHandler();
        $SH = new \LwPortalsConfig\Model\Config\DataHandler\StorageHandler();
        
        $result = $QH->loadPortalsConfig();
        
        if(!$result){
            $SH->addConfig($portalsConfigString);
        }else{
            $SH->saveConfig($portalsConfigString, $result["id"]);
        }
        
        $this->respone->setParameterByKey('save', true);
        return $this->respone;
    }

}
