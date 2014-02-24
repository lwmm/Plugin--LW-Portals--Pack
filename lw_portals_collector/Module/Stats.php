<?php

namespace LwPortalsCollector\Module;

class Stats extends \LwPortalsCollector\Model\Base\DataHandler\StatsBase
{

    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function execute($id, $stats)
    {
        $this->addStats($id, $stats);
    }

}
