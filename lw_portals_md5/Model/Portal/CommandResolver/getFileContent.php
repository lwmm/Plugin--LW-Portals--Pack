<?php

namespace LwPortalsMd5\Model\Portal\CommandResolver;

class getFileContent
{

    protected $request;
    protected $respone;
    protected $params;
    protected $data;

    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
        $this->respone = \LwPortalsMd5\Services\Response::getInstance();
        $this->request = \lw_registry::getInstance()->getEntry("request");
        ini_set("max_execution_time", 600); #10 min
    }

    public static function getInstance($params = false, $data = false)
    {
        return new getFileContent($params, $data);
    }

    public function resolve()
    {
        $result = array();
        $filePath = urldecode($this->request->getRaw("filePath"));

        if ($this->request->getInt("id") && $this->request->getInt("id") > 0) {
            $response = \LwPortalsMd5\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
            $entity = $response->getDataByKey("PortalEntity");
            $result[$entity->getValueByKey("name")] = $this->getFileContent($entity, $filePath);
        } else {
            $response = \LwPortalsMd5\Model\Portal\CommandResolver\getPortalsCollection::getInstance()->resolve();
            $collection = $response->getDataByKey("PortalEntitiesCollection");
            foreach ($collection as $entity) {
                $result[$entity->getValueByKey("name")] = $this->getFileContent($entity, $filePath);
            }
        }

        $this->respone->setDataByKey("fileContentResult", $result);
        return $this->respone;
    }

    protected function getFileContent($entity, $filePath)
    {
        $array = array();

        if (substr($entity->getValueByKey("url"), -1) == "/") {
            $url = $entity->getValueByKey("url");
        } else {
            $url = $entity->getValueByKey("url") . "/";
        }
        if(substr(strtolower($url), 0, strlen("http://")) == "http://"){
            $url = "https://" . substr($url, strlen("http://"));
        }else if (substr(strtolower($url), 0, strlen("http://")) != "http://" && substr(strtolower($url), 0, strlen("https://")) != "https://"){
            $url = "https://". $url;
        }

        $json = file_get_contents($url . "index.php?getSystemInfo=1&cmd=GetFileContent&filePath=" . urlencode($filePath));
        $result = json_decode($json, true);
        if (is_array($result)) {
            $array[] = $result;
        }

        return $array;
    }

}
