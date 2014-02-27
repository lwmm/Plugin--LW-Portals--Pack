<?php

namespace LwPortalsMd5\Controller;

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
            $cmd = "showMd5CheckForm";
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

    protected function showMd5CheckFormAction($results = false, $post = false)
    {
        if ($this->isAdmin()) {
            $view = new \LwPortalsMd5\Views\Md5CheckForm();
            if ($this->request->getInt("id")) {
                $response = \LwPortalsMd5\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
                $entity = $response->getDataByKey("PortalEntity");
                $view->setEntity($entity);
            }else{
                $response = \LwPortalsMd5\Model\Portal\CommandResolver\getPortalsCollection::getInstance()->resolve();
                $collection = $response->getDataByKey("PortalEntitiesCollection");
                $view->setCollection($collection);
            }
            $view->setResults($results);
            $view->setPost($post);
            $view->setPageIndexes($this->pageIndexes);
            return $view->render();
        }
    }
    
    protected function checkMd5Action()
    {
        #print_r($this->request);die();
        if ($this->isAdmin()) {
            $response = \LwPortalsMd5\Model\Portal\CommandResolver\checkMd5::getInstance()->resolve();
            $results = $response->getDataByKey("Md5Results");
            $post = $response->getDataByKey("postArray");
            
            
            if($this->request->getInt("ajax")){
                #print_r($results);die();
                die(json_encode($results));
            }else{
                return $this->showMd5CheckFormAction($results, $post);
            }
        }
    }
    

}
