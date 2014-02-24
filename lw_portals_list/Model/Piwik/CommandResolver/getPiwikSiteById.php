<?php

namespace LwPortalsList\Model\Piwik\CommandResolver;

class getPiwikSiteById
{

    protected $request;
    protected $respone;
    protected $params;
    protected $data;

    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
        $this->respone = \LwPortalsList\Services\Response::getInstance();
    }

    public static function getInstance($params = false, $data = false)
    {
        return new getPiwikSiteById($params, $data);
    }

    public function resolve()
    {
        if($this->params["id"] > 0){        
            $config = \lw_registry::getInstance()->getEntry("config");

            $base = $config['piwik']['base'];

            $request = "&method=SitesManager.getSiteFromId" .
                    "&idSite=" . $this->params["id"] .
                    "&format=PHP" .
                    "&token_auth=" . $config['piwik']['token_auth'];

            $portal = unserialize(file_get_contents($base . $request));        
        }else{
            $portal = array( 0 => array("idsite" => 0, "name" => "nicht zugewiesen"));
        }
        $entity = new \LwPortalsList\Model\Piwik\Object\PiwikSite($portal["idsite"]);
        $entity->setValues($portal[0]);

        $this->respone->setDataByKey("PiwikSiteEntity", $entity);
        return $this->respone;
    }

}
