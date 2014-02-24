<?php

namespace LwPortalsTableSearch\Model\Portal\CommandResolver;

class searchTable
{

    protected $request;
    protected $respone;
    protected $params;
    protected $data;

    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
        $this->respone = \LwPortalsTableSearch\Services\Response::getInstance();
        $this->request = \lw_registry::getInstance()->getEntry("request");
    }

    public static function getInstance($params = false, $data = false)
    {
        return new searchTable($params, $data);
    }

    public function resolve()
    {
        $preparedSearchArray = $this->preparePostArray();

        if ($this->request->getInt("id") && $this->request->getInt("id") > 0) {
            $response = \LwPortalsTableSearch\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
            $entity = $response->getDataByKey("PortalEntity");
            $result[$entity->getValueByKey("name")] = $this->searchInPortalTable($entity, $preparedSearchArray["tablename"], $preparedSearchArray["searchFields"]);
        } else {
            $response = \LwPortalsTableSearch\Model\Portal\CommandResolver\getPortalsCollection::getInstance()->resolve();
            $collection = $response->getDataByKey("PortalEntitiesCollection");
            foreach ($collection as $entity) {
                $result[$entity->getValueByKey("name")] = $this->searchInPortalTable($entity, $preparedSearchArray["tablename"], $preparedSearchArray["searchFields"]);
            }
        } 
        
        $this->respone->setDataByKey("SearchResults", $result);
        $this->respone->setDataByKey("postArray", $this->request->getPostArray());
        return $this->respone;
    }

    protected function preparePostArray()
    {
        $array = array();

        $postArray = $this->request->getPostArray();
        $array["tablename"] = $postArray["tablename"];
        unset($postArray["tablename"]);
        
        foreach($postArray as $searchField => $searchValues){
            if($searchValues != ""){
                $array["searchFields"][$searchField] = $searchValues;
            }
        }
        
        return $array;
    }

    protected function searchInPortalTable($entity, $tablename, $searchFields)
    {
        $array = array();

        if (substr($entity->getValueByKey("url"), -1) == "/") {
            $url = $entity->getValueByKey("url");
        } else {
            $url = $entity->getValueByKey("url") . "/";
        }
        
        foreach ($searchFields as $sField => $sFieldValue) {            
            $json = file_get_contents($url . "index.php?getSystemInfo=1&cmd=TableSearch&tablename=" . $tablename . "&searchField=" . $sField . "&search=" . $sFieldValue);            
            $result = json_decode($json, true);
            if (is_array($result)) {
                $array["tablename"] = $tablename;
                $array[$sField]["searchQuery"] = $sFieldValue;
                foreach($result as $entry){
                    $array[$sField][] = $entry;
                }
            }
        }

        return $array;
    }

}
