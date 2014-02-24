<?php

class lw_portals_plugins extends lw_plugin
{

    protected $request;
    
    public function __construct()
    {
        parent::__construct();
        include_once(dirname(__FILE__) . '/Services/Autoloader.php');
        $autoloader = new \LwPortalsPlugins\Services\Autoloader();
    }

    public function buildPageOutput()
    {
        $this->response->useJQuery();
        if (isset($this->params["admin"]) && $this->params["admin"] == 1) {
            $admin = true;
        } else {
            $admin = false;
        }
               
        $pageIndexes = false;
        foreach($this->params as $key => $value){
            if($key != "admin"){
                $pageIndexes[$key] = $value;
            }
        }

        $controller = new \LwPortalsPlugins\Controller\Frontend();
        $controller->setAdmin($admin);
        $controller->setPageIndexes($pageIndexes);
        return $controller->execute();

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
