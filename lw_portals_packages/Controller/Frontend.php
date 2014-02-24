<?php

namespace LwPortalsPackages\Controller;

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
            $cmd = "showList";
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

    protected function showListAction()
    {
        $response = \LwPortalsPackages\Model\Package\CommandResolver\getPackageCollection::getInstance()->resolve();
        $collection = $response->getDataByKey("PackagesEntitiesCollection");
        $view = new \LwPortalsPackages\Views\PackageList();
        $view->setCollection($collection);
        return $view->render();
    }
    
    protected function showDetailAction()
    {
        $response = \LwPortalsPackages\Model\Package\CommandResolver\getPackageEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
        $entity = $response->getDataByKey("PackageEntity");
        $view = new \LwPortalsPackages\Views\PackageDetail();
        $view->setEntity($entity);
        $view->setPageIndexes($this->pageIndexes);
        return $view->render();
    }
    
}
