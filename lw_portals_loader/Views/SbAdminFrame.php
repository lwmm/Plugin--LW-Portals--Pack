<?php

namespace LwPortalsLoader\Views;

class SbAdminFrame
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/SbAdminFrame.phtml');
    }

    public function setContent($content)
    {
        $this->view->content = $content;
    }
    
    public function setIsAdmin($bool)
    {
        $this->view->isAdmin = $bool;
    }
    
    public function setPortalsPlugin($portalsPlugin)
    {
        $this->view->portalsPlugin = $portalsPlugin;
    }

    public function render()
    {
        $config = \lw_registry::getInstance()->getEntry("config");
        $this->view->mediaUrl = $config["url"]["media"];
        return $this->view->render();
    }

}
