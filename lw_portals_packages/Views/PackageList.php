<?php

namespace LwPortalsPackages\Views;

class PackageList
{
    protected $view;
    
    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/PackageList.phtml');
    }
        
    public function setCollection($collection)
    {
        $this->view->collection = $collection;
    }
    
    public function render()
    {
        $config = \lw_registry::getInstance()->getEntry("config");
        $this->view->mediaUrl = $config["url"]["media"];
        return $this->view->render();
    }
}