<?php

namespace LwPortalsPlugins\Model\Plugin\CommandResolver;

class getPluginEntityById
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
        return new getPluginEntityById($params, $data);
    }

    public function resolve()
    {
        $QH = new \LwPortalsPlugins\Model\Plugin\DataHandler\QueryHandler();
        $result = $QH->loadPluginById($this->params["id"]);
        $entity = new \LwPortalsPlugins\Model\Plugin\Object\Plugin($this->params["id"]);
        $entity->setValues($this->prepareModules($result["id"], $result["name"]));
        $this->respone->setDataByKey("PluginEntity", $entity);
        return $this->respone;
    }

    protected function prepareModules($mid, $pluginName)
    {
        $array = array("mid" => $mid, "pluginname" => $pluginName);

        $QH = new \LwPortalsPlugins\Model\Plugin\DataHandler\QueryHandler();
        $result = $QH->loadPortalsWherePluginIsInstalled($this->params["id"]);

        foreach ($result as $portal) {
            $array["portals"][] = array("id" => $portal["pid"], "name" => $portal["name"]);
        }

        return $array;
    }

}
