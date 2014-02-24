<?php

namespace LwPortalsList\Model\Portal\CommandResolver;

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
        $this->respone = \LwPortalsList\Services\Response::getInstance();
    }
    
    public static function getInstance($params = false, $data = false)
    {
        return new getPortalEntityById($params, $data);
    }
    
    public function resolve()
    {
        $this->QH = new \LwPortalsList\Model\Portal\DataHandler\QueryHandler();
        $result = $this->QH->loadPortalById($this->params["id"]);
        
        $modules = $this->prepareModules();
        $result["plugins"] = $modules["plugins"];
        $result["packages"] = $modules["packages"];
        $result["piwik_name"] = $this->getPiwikSiteName($result["piwik_id"]);
        $result["piwik_usage"] = $this->preparePiwikUsage($result["piwik_id"]);
        $result["stats"] = $this->prepareStats();
        $result["listtoolStats"] = $this->prepareListtoolScans();
        
        $entity = new \LwPortalsList\Model\Portal\Object\Portal($this->params["id"]);
        $entity->setValues($result);        
        $this->respone->setDataByKey("PortalEntity", $entity);
        return $this->respone;
    }
    
    protected function prepareModules()
    {
        $array = array();
        $modules = $this->QH->loadPortalModules($this->params["id"]);
        $installationCount = $this->QH->getAmountOfPortalsWhereAModuleIsInstalled();

        foreach($modules as $module){
            if($module["type"] == "plugin"){
                $array["plugins"][] = array("id" => $module["id"], "name" => $module["name"], "installs" => $installationCount[$module["name"]]);
            }elseif($module["type"] == "package"){
                $array["packages"][] = array("id" => $module["id"], "name" => $module["name"], "installs" => $installationCount[$module["name"]]);
            }
        }
        foreach ($array["plugins"] as $nr => $plugin){
            $arr[$nr] = $plugin["name"];
        }
        array_multisort($arr, SORT_ASC, $array["plugins"]);
        foreach ($array["packages"] as $nr => $package){
            $arr2[$nr] = $package["name"];
        }
        array_multisort($arr2, SORT_ASC, $array["packages"]);
        
        return $array;
    }
    
    protected function preparePiwikUsage($piwik_id)
    {
        $array = array();
        
        if($piwik_id > 0){
            $result = $this->QH->getCountOfPortalByPiwikId($piwik_id);

            foreach($result as $key => $value){
                if($value["id"] == $this->params["id"]){
                    unset($result[$key]);
                }
            }

            foreach($result as $portal){
                $array[] = array("id" => $portal["id"], "name" => $portal["name"]);
            }
        }
        
        return $array;
    }


    protected function getPiwikSiteName($piwik_id)
    {
        if($piwik_id > 0){
            $response = \LwPortalsList\Model\Piwik\CommandResolver\getPiwikSiteById::getInstance(array("id" => $piwik_id))->resolve();
            $entity = $response->getDataByKey("PiwikSiteEntity");

            return $entity->getValueByKey("name");
        }
    }
    
    protected function prepareStats()
    {
        $array = array();
        
        $result = $this->QH->loadAcutalPortalStats($this->params["id"]);
        $result = $result[0];
        
        if(!empty($result)){
            unset($result["id"]);
            unset($result["portal_id"]);
            unset($result["lw_date"]);
            
            foreach ($result as $key => $value){
                $arr[] = $key;
            }
            array_multisort($arr, SORT_ASC, $result);            
            $array = $result;            
        }
        return $array;
    }
    
    protected function prepareListtoolScans()
    {
        $result = $this->QH->loadAcutalPrevious14ListtoolStats($this->params["id"]);
        
        if(!empty($result)){
            return $result;
        }
        return false;
    }
}