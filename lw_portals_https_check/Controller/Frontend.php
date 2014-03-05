<?php

namespace LwPortalsHttpsCheck\Controller;

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
            $cmd = "showCheckForm";
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
    
    protected function showCheckFormAction()
    {
        if ($this->isAdmin()) {
            $view = new \LwPortalsHttpsCheck\Views\HttpsCheckForm();
            if ($this->request->getInt("id")) {
                $response = \LwPortalsHttpsCheck\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
                $entity = $response->getDataByKey("PortalEntity");
                $view->setEntity($entity);
            } else {
                $response = \LwPortalsHttpsCheck\Model\Portal\CommandResolver\getPortalsCollection::getInstance()->resolve();
                $collection = $response->getDataByKey("PortalEntitiesCollection");
                $view->setCollection($collection);
            }
            $view->setPageIndexes($this->pageIndexes);
            return $view->render();
        }
    }
    
    protected function checkHttpsAction()
    {
        if ($this->isAdmin()) {
            $response = \LwPortalsHttpsCheck\Model\Portal\CommandResolver\checkHttps::getInstance()->resolve();
            $result = $response->getDataByKey("httpsResult");
            die(json_encode($result));
        }
    }

}
