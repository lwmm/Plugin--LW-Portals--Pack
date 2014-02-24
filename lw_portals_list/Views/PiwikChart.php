<?php

namespace LwPortalsList\Views;

class PiwikChart
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/PiwikChart.phtml');
    }

    public function setData($array)
    {
        $this->view->piwikPhPVisitArray = $array;
    }

    public function render()
    {       
        return $this->view->render();
    }

}
