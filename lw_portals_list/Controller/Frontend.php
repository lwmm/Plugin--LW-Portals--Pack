<?php

namespace LwPortalsList\Controller;

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
        $response = \LwPortalsList\Model\Portal\CommandResolver\getPortalsCollection::getInstance()->resolve();
        $collection = $response->getDataByKey("PortalEntitiesCollection");
        $view = new \LwPortalsList\Views\PortalList();
        $view->setCollection($collection);
        $view->setIsAdmin($this->isAdmin());
        $view->setResponse($this->request->getInt("response"));
        return $view->render();
    }

    protected function showAddFormAction($errors = false)
    {
        if ($this->isAdmin()) {
            $response = \LwPortalsList\Model\Piwik\CommandResolver\getPiwikSitesCollection::getInstance()->resolve();
            $piwikSites = $response->getDataByKey("PiwikSitesEntitiesCollection");
            $response = \LwPortalsList\Model\Portal\CommandResolver\getPortalEntityFromPostArray::getInstance(array(), array("postArray" => $this->request->getPostArray()))->resolve();
            $entity = $response->getDataByKey("PortalEntity");
            $view = new \LwPortalsList\Views\PortalForm();
            $view->setEntity($entity);
            $view->setPiwikSites($piwikSites);
            $view->setErrors($errors);
            return $view->render();
        }
    }

    protected function addEntryAction()
    {
        if ($this->isAdmin()) {
            $response = \LwPortalsList\Model\Portal\CommandResolver\add::getInstance(array(), array("postArray" => $this->request->getPostArray()))->resolve();
            if ($response->getParameterByKey("error")) {
                return $this->showAddFormAction($response->getDataByKey("error"));
            }
            \LwPortalsList\Services\Page::reload(\LwPortalsList\Services\Page::getUrl(array("portalsPlugin" =>"LwPortalsList", "cmd" => "showDetail", "id" => $response->getParameterByKey("id"), "response" => 2)));
        }
    }

    protected function showEditFormAction($errors = false)
    {
        if ($this->isAdmin()) {
            if ($errors) {
                $response = \LwPortalsList\Model\Portal\CommandResolver\getPortalEntityFromPostArray::getInstance(array("id" => $this->request->getInt("id")), array("postArray" => $this->request->getPostArray()))->resolve();
            } else {
                $response = \LwPortalsList\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
            }
            $entity = $response->getDataByKey("PortalEntity");
            $response = \LwPortalsList\Model\Piwik\CommandResolver\getPiwikSitesCollection::getInstance()->resolve();
            $piwikSites = $response->getDataByKey("PiwikSitesEntitiesCollection");
            $view = new \LwPortalsList\Views\PortalForm();
            $view->setEntity($entity);
            $view->setPiwikSites($piwikSites);
            $view->setErrors($errors);
            return $view->render();
        }
    }

    protected function saveEntryAction()
    {
        if ($this->isAdmin()) {
            $response = \LwPortalsList\Model\Portal\CommandResolver\save::getInstance(array("id" => $this->request->getInt("id")), array("postArray" => $this->request->getPostArray()))->resolve();
            if ($response->getParameterByKey("error")) {
                return $this->showEditFormAction($response->getDataByKey("error"));
            }
            \LwPortalsList\Services\Page::reload(\LwPortalsList\Services\Page::getUrl(array("portalsPlugin" =>"LwPortalsList", "cmd" => "showDetail", "id" => $this->request->getInt("id"), "response" => 3)));
        }
    }

    protected function showDetailAction()
    {
        $response = \LwPortalsList\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
        $entity = $response->getDataByKey("PortalEntity");
        $view = new \LwPortalsList\Views\PortalDetail();
        $view->setEntity($entity);
        $view->setIsAdmin($this->isAdmin());
        $view->setPageIndexes($this->pageIndexes);
        $view->setResponse($this->request->getInt("response"));
        return $view->render();
    }

    protected function deleteEntryAction()
    {
        if ($this->isAdmin()) {
            $response = \LwPortalsList\Model\Portal\CommandResolver\delete::getInstance(array("id" => $this->request->getInt("id")))->resolve();
            \LwPortalsList\Services\Page::reload(\LwPortalsList\Services\Page::getUrl(array("portalsPlugin" =>"LwPortalsList", "cmd" => "showList")));
        }
    }

}
