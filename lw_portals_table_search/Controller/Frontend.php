<?php

namespace LwPortalsTableSearch\Controller;

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
            $cmd = "showTableSearchForm";
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

    protected function showTableSearchFormAction($results = false, $post = false)
    {
        if ($this->isAdmin()) {
            $view = new \LwPortalsTableSearch\Views\TableSearchForm();
            if ($this->request->getInt("id")) {
                $response = \LwPortalsTableSearch\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
                $entity = $response->getDataByKey("PortalEntity");
                $view->setEntity($entity);
            }
            
            $response = \LwPortalsTableSearch\Model\Table\CommandResolver\getTablesCollection::getInstance()->resolve();
            $tablesCollection = $response->getDataByKey("TableEntitiesCollection");

            $view->setTablesCollection($tablesCollection);
            $view->setResults($results);
            $view->setPost($post);
            return $view->render();
        }
    }
    
    protected function searchAction()
    {
        if ($this->isAdmin()) {
            $response = \LwPortalsTableSearch\Model\Portal\CommandResolver\searchTable::getInstance()->resolve();
            $results = $response->getDataByKey("SearchResults");
            $post = $response->getDataByKey("postArray");
            
            return $this->showTableSearchFormAction($results, $post);
        }
    }
    

}
