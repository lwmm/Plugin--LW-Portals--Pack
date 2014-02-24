<?php

namespace LwPortalsPackages\Model\Package\CommandResolver;

class getPackageEntityById
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
        return new getPackageEntityById($params, $data);
    }

    public function resolve()
    {
        $QH = new \LwPortalsPackages\Model\Package\DataHandler\QueryHandler();
        $result = $QH->loadPluginById($this->params["id"]);
        $entity = new \LwPortalsPackages\Model\Package\Object\Package($this->params["id"]);
        $entity->setValues($this->prepareModules($result["id"], $result["name"]));
        $this->respone->setDataByKey("PackageEntity", $entity);
        return $this->respone;
    }

    protected function prepareModules($mid, $packageName)
    {
        $array = array("mid" => $mid, "packagename" => $packageName);

        $QH = new \LwPortalsPackages\Model\Package\DataHandler\QueryHandler();
        $result = $QH->loadPortalsWherePluginIsInstalled($this->params["id"]);

        foreach ($result as $portal) {
            $array["portals"][] = array("id" => $portal["pid"], "name" => $portal["name"]);
        }

        return $array;
    }

}
