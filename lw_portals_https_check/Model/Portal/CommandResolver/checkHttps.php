<?php

namespace LwPortalsHttpsCheck\Model\Portal\CommandResolver;

class checkHttps
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
        $this->request = \lw_registry::getInstance()->getEntry("request");
        ini_set("max_execution_time", 600); #10 min
    }

    public static function getInstance($params = false, $data = false)
    {
        return new checkHttps($params, $data);
    }

    public function resolve()
    {
        $result = array();

        if ($this->request->getInt("id") && $this->request->getInt("id") > 0) {
            $response = \LwPortalsHttpsCheck\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
            $entity = $response->getDataByKey("PortalEntity");
            $result[$entity->getValueByKey("name")] = $this->httpsConTest($entity);
        } else {
            $response = \LwPortalsHttpsCheck\Model\Portal\CommandResolver\getPortalsCollection::getInstance()->resolve();
            $collection = $response->getDataByKey("PortalEntitiesCollection");
            foreach ($collection as $entity) {
                $result[$entity->getValueByKey("name")] = $this->httpsConTest($entity);
            }
        }

        $this->respone->setDataByKey("httpsResult", $result);
        return $this->respone;
    }

    protected function httpsConTest($entity)
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

        $json = file_get_contents($url . "index.php?getSystemInfo=1&cmd=HttpsCheck");
        $result = json_decode($json, true);
        if (is_array($result)) {
            $array[] = $result;
        } else {
            $array[] = array("https" => false);
        }

        return $array;
    }

}
