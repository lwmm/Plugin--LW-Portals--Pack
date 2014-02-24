<?php

namespace LwPortalsList\Views;

class PortalForm
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/PortalForm.phtml');
    }

    public function setEntity($entity)
    {
        $this->view->entity = $entity;
    }

    public function setErrors($errors)
    {
        $this->view->errors = $errors;
    }
    
    public function setPiwikSites($sites)
    {
        $this->view->piwikSites = $sites;
    }
    
    public function render()
    {
        if ($this->view->entity->getId() < 1) {
            $this->view->actionUrl = \LwPortalsList\Services\Page::getUrl(array("portalsPlugin" =>"LwPortalsList", "cmd" => "addEntry"));
            $this->view->formtype = "new";
        } else {
            $this->view->actionUrl = \LwPortalsList\Services\Page::getUrl(array("portalsPlugin" =>"LwPortalsList", "cmd" => "saveEntry", "id" => $this->view->entity->getId()));
            $this->view->formtype = "edit";
        }

        return $this->view->render();
    }

}
