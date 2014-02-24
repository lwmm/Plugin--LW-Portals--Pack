<?php

namespace LwPortalsCollector\Model\Portal\DataHandler;

class QueryHandler
{

    protected $db;

    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry("db");
    }

    public function loadPortalById($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);

        return $this->db->pselect1();
    }    
    
    public function loadAllPortals()
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals ORDER BY name ");
        return $this->db->pselect();
    }

}
