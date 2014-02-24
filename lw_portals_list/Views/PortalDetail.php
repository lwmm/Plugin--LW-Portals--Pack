<?php

namespace LwPortalsList\Views;

class PortalDetail
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/PortalDetail.phtml');
    }

    public function setEntity($entity)
    {
        $this->view->entity = $entity;
    }

    public function setIsAdmin($bool)
    {
        $this->view->isAdmin = $bool;
    }

    public function setPageIndexes($pageIndexes)
    {
        $this->view->pageIndexes = $pageIndexes;
    }

    public function setResponse($response)
    {
        $this->view->response = $response;
    }

    public function render()
    {
        $config = \lw_registry::getInstance()->getEntry("config");

        $this->view->mediaUrl = $config["url"]["media"];
        
        $request = $config['piwik']['base'] .
                "&method=VisitsSummary.get" .
                "&idSite=" . $this->view->entity->getValueByKey("piwik_id") .
                "&period=day" .
                "&date=last30" .
                "&format=php" .
                "&token_auth=" . $config['piwik']['token_auth'];

        $this->view->piwikPhPVisitArray = unserialize(file_get_contents($request));
        #print_r($this->view->piwikPhPVisitArray);die();

        return $this->view->render();
    }

}
