<?php

namespace LwPortalsPlugins\Model\Plugin\CommandResolver;

class getPluginCollection
{

    protected $request;
    protected $respone;
    protected $params;
    protected $data;

    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
        $this->respone = \LwPortalsPlugins\Services\Response::getInstance();
    }

    public static function getInstance($params = false, $data = false)
    {
        return new getPluginCollection($params, $data);
    }

    public function resolve()
    {
        $collection = array();
        
        $QH = new \LwPortalsPlugins\Model\Plugin\DataHandler\QueryHandler();
        $installationCount = $QH->getAmountOfPortalsWhereAPluginIsInstalled();
        $result = $QH->loadAllPlugins();
        
        foreach($result as $plugin){
            $plugin["installs"] = $installationCount[$plugin["name"]];
            $entity = new \LwPortalsPlugins\Model\Plugin\Object\Plugin($plugin["id"]);
            $entity->setValues($plugin);
            $collection[] = $entity;
        }
        
        $this->respone->setDataByKey("PluginsEntitiesCollection", $collection);
        return $this->respone;
    }

}
