<?php

namespace LwPortalsConfig\Views;

class ConfigForm
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/ConfigForm.phtml');
    }

    public function setPortalsConfig($config)
    {
        $this->view->portalsConfig = $config;
    }

    public function render()
    {        
        return $this->view->render();
    }

}
