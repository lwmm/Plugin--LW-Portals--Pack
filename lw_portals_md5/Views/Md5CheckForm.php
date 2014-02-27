<?php

namespace LwPortalsMd5\Views;

class Md5CheckForm
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/Md5CheckForm.phtml');
    }

    public function setEntity($entity)
    {
        $this->view->entity = $entity;
    }

    public function setResults($results)
    {
        $this->view->results = $results;
    }
    
    public function setPost($post)
    {
        $this->view->post = $post;
    }
    
    public function setPageIndexes($pageIndexes)
    {
        $this->view->pageIndexes = $pageIndexes;
    }
    
    public function setCollection($collection)
    {
        $this->view->collection = $collection;
    }

    public function render()
    {
        $config = \lw_registry::getInstance()->getEntry("config"); 
        $this->view->mediaUrl = $config["url"]["media"];
        
        foreach ($config["path"] as $key => $path){
            $arr[] = $key;
        }
        array_multisort($arr, SORT_ASC, $config["path"]);
        $this->view->paths = $config["path"];
        
        
        if($this->view->entity){
            $this->view->actionUrl = \LwPortalsMd5\Services\Page::getUrl(array("portalsPlugin" =>"LwPortalsMd5", "cmd" => "checkMd5", "id" => $this->view->entity->getId()));
        }else{
            $this->view->actionUrl = \LwPortalsMd5\Services\Page::getUrl(array("portalsPlugin" =>"LwPortalsMd5", "cmd" => "checkMd5"));
        }
        
        return $this->view->render();
    }

}
