<?php

namespace LwPortalsHttpsCheck\Views;

class HttpsCheckForm
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/HttpsCheckForm.phtml');
    }

    public function setEntity($entity)
    {
        $this->view->entity = $entity;
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
        
        if($this->view->entity){
            $this->view->actionUrl = \LwPortalsMd5\Services\Page::getUrl(array("portalsPlugin" =>"LwPortalsHttpsCheck", "cmd" => "checkHttps", "id" => $this->view->entity->getId()));
        }else{
            $this->view->actionUrl = \LwPortalsMd5\Services\Page::getUrl(array("portalsPlugin" =>"LwPortalsHttpsCheck", "cmd" => "checkHttps"));
        }
        
        return $this->view->render();
    }

}
