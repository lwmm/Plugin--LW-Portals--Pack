<?php

namespace LwPortalsConfig\Model\Config\DataHandler;

class QueryHandler
{

    protected $db;

    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry("db");
    }
    
    public function loadPortalsConfig()
    {
        $this->db->setStatement("SELECT * FROM t:lw_master WHERE lw_object = :lw_object ");
        $this->db->bindParameter("lw_object", "s", "lw_portals_config");

        $result = $this->db->pselect1();
        
        if(empty($result)){
            return false;
        }
        return $result;
    }
}
