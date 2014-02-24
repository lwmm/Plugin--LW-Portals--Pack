<?php

namespace LwPortalsList\Views;

class PortalList
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/PortalList.phtml');
    }

    public function setCollection($collection)
    {
        $this->view->collection = $collection;
    }

    public function setIsAdmin($bool)
    {
        $this->view->isAdmin = $bool;
    }

    public function setResponse($response)
    {
        $this->view->response = $response;
    }

    public function render()
    {
        $config = \lw_registry::getInstance()->getEntry("config");
        $this->view->mediaUrl = $config["url"]["media"];
        return $this->view->render();
    }

}
