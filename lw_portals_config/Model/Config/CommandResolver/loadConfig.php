<?php

namespace LwPortalsConfig\Model\Config\CommandResolver;

class loadConfig
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
        return new loadConfig($params, $data);
    }

    public function resolve()
    {
        $QH = new \LwPortalsConfig\Model\Config\DataHandler\QueryHandler();
        $result = $QH->loadPortalsConfig();       
                
        $this->respone->setDataByKey('PortalsConfig', unserialize($result["opt1clob"]));
        return $this->respone;
    }

}
