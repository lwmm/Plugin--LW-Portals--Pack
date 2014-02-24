<?php

namespace LwPortalsList\Model\Piwik\CommandResolver;

class getPiwikSitesCollection
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
        return new getPiwikSitesCollection($params, $data);
    }

    public function resolve()
    {
        $collection = array();
        $config = \lw_registry::getInstance()->getEntry("config");

        $base = $config['piwik']['base'];

        $request = "&method=SitesManager.getAllSites" .
                "&format=PHP" .
                "&token_auth=" . $config['piwik']['token_auth'];

        $portals = unserialize(file_get_contents($base . $request));

        foreach ($portals as $nr => $page) {
            $name[$nr] = $page['name'];
        }
        array_multisort($name, SORT_ASC, $portals);

        foreach($portals as $portal){
            $entity = new \LwPortalsList\Model\Piwik\Object\PiwikSite($portal["idsite"]);
            $entity->setValues($portal);
            $collection[] = $entity;
        }

        $this->respone->setDataByKey("PiwikSitesEntitiesCollection", $collection);
        return $this->respone;
    }

}
