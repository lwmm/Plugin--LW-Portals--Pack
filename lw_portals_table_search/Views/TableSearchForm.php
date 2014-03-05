<?php

namespace LwPortalsTableSearch\Views;

class TableSearchForm
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/TableSearchForm.phtml');
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
    
    public function setTablesCollection($tablesCollection)
    {
        $this->view->tablesCollection = $tablesCollection;
    }
    
    public function setCollection($collection)
    {
        $this->view->collection = $collection;
    }
    
    public function setPageIndexes($pageIndexes)
    {
        $this->view->pageIndexes = $pageIndexes;
    }

    public function render()
    {           
        $config = \lw_registry::getInstance()->getEntry("config");
        $this->view->mediaUrl = $config["url"]["media"];
        if($this->view->entity){
            $this->view->actionUrl = \LwPortalsTableSearch\Services\Page::getUrl(array("portalsPlugin" =>"LwPortalsTableSearch", "cmd" => "search", "id" => $this->view->entity->getId()));
        }else{
            $this->view->actionUrl = \LwPortalsTableSearch\Services\Page::getUrl(array("portalsPlugin" =>"LwPortalsTableSearch", "cmd" => "search"));
        }
        
        return $this->view->render();
    }

}
