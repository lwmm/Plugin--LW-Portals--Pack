<?php

namespace LwPortalsCollector\Module;

class Listtool extends \LwPortalsCollector\Model\Base\DataHandler\ListtoolBase
{

    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function execute($id, $stats)
    {
        $this->addScan($id, $stats);
    }

}
