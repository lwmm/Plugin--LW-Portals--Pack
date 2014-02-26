<?php

namespace LwPortalsConfig\Controller;

class Frontend
{

    protected $request;
    protected $admin;
    protected $pageIndexes;

    public function __construct()
    {
        $this->request = \lw_registry::getInstance()->getEntry("request");
    }

    public function execute()
    {
        if ($this->request->getAlnum("cmd")) {
            $cmd = $this->request->getAlnum("cmd");
        } else {
            $cmd = "showConfigForm";
        }

        $method = $cmd . "Action";
        if (method_exists($this, $method)) {
            return $this->$method();
        } else {
            die("command " . $method . " doesn't exist");
        }
    }

    public function setAdmin($bool)
    {
        if ($bool === true) {
            $this->admin = true;
        } else {
            $this->admin = false;
        }
    }

    public function setPageIndexes($pageIndexes)
    {
        $this->pageIndexes = $pageIndexes;
    }

    protected function isAdmin()
    {
        return $this->admin;
    }

    protected function showConfigFormAction()
    {
        if($this->isAdmin()){
            $response = \LwPortalsConfig\Model\Config\CommandResolver\loadConfig::getInstance()->resolve();
            $config = $response->getDataByKey("PortalsConfig");

            $view = new \LwPortalsConfig\Views\ConfigForm();
            $view->setPortalsConfig($config);
            return $view->render();
        }
    }

    protected function saveAction()
    {
        if($this->isAdmin()){
            $response = \LwPortalsConfig\Model\Config\CommandResolver\save::getInstance(array(), array("PortalsConfig" => $this->request->getPostArray()))->resolve();
            \LwPortalsConfig\Services\Page::reload(\LwPortalsConfig\Services\Page::getUrl(array("portalsPlugin" =>"LwPortalsConfig", "cmd" => "showConfigForm")));
        }
    }

}
