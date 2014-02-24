<?php

class lw_portals_loader extends lw_plugin
{

    protected $request;
    
    public function __construct()
    {
        parent::__construct();
        include_once(dirname(__FILE__) . '/Services/Autoloader.php');
        $autoloader = new \LwPortalsLoader\Services\Autoloader();
    }

    public function buildPageOutput()
    {
        $this->response->useJQuery();
        if (isset($this->params["admin"]) && $this->params["admin"] == 1) {
            $admin = true;
        } else {
            $admin = false;
        }

        if($this->request->getAlnum("portalsPlugin")){
            $portalsPlugin = $this->request->getAlnum("portalsPlugin");
        }else{
            $portalsPlugin = "LwPortalsList";
        }
        
        $controllerNamespace = "\\".$portalsPlugin."\\Controller\\Frontend";
        if(!class_exists($controllerNamespace)){
            $controllerNamespace = "\\LwPortalsList\\Controller\\Frontend";
        }
            
        $controller = new $controllerNamespace();
        $controller->setAdmin($admin);

        $view = new \LwPortalsLoader\Views\SbAdminFrame();
        $view->setContent($controller->execute());
        $view->setIsAdmin($admin);
        $view->setPortalsPlugin($portalsPlugin);
        return $view->render();
    }

    public function getOutput()
    {
        return "";
    }

    public function deleteEntry()
    {
        return true;
    }

}
