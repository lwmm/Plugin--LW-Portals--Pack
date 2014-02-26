<?php

namespace LwPortalsConfig\Model\Config\DataHandler;

class StorageHandler
{

    protected $db;

    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry("db");
    }

    public function addConfig($config)
    {
        $this->db->setStatement("INSERT INTO t:lw_master (lw_object) VALUES (:lw_object) ");
        $this->db->bindParameter("lw_object", "s", "lw_portals_config");
        
        $id = $this->db->pdbinsert($this->db->gt("lw_master"));
        $this->db->saveClob($this->db->gt('lw_master'), 'opt1clob', $this->db->quote($config), $id);
        
        return $id;
    }

    public function saveConfig($config, $id)
    {
        $this->db->setStatement("UPDATE t:lw_master SET lw_object = :lw_object WHERE id = :id ");
        $this->db->bindParameter("lw_object", "s", "lw_portals_config");
        $this->db->bindParameter("id", "i", $id);

        $this->db->pdbquery();
        
        $this->db->saveClob($this->db->gt('lw_master'), 'opt1clob', $this->db->quote($config), $id);
    }

}
