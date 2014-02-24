<?php

namespace LwPortalsPackages\Views;

class PackageDetail
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/PackageDetail.phtml');
    }

    public function setEntity($entity)
    {
        $this->view->entity = $entity;
    }
    
    public function setPageIndexes($pageIndexes)
    {
        $this->view->pageIndexes = $pageIndexes;
    }

    public function render()
    {
        $config = \lw_registry::getInstance()->getEntry("config");
        $this->view->mediaUrl = $config["url"]["media"];
        return $this->view->render();
    }

}
