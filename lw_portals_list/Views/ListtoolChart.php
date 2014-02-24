<?php

namespace LwPortalsList\Views;

class ListtoolChart
{

    protected $view;

    public function __construct()
    {
        $this->view = new \lw_view(dirname(__FILE__) . '/Templates/ListtoolChart.phtml');
    }

    public function setData($array)
    {
        $this->view->data = $array;
    }

    public function render()
    {
        return $this->view->render();
    }

}
