<?php

namespace LwPortalsPlugins\Model\Plugin\DataHandler;

class QueryHandler
{

    protected $db;

    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry("db");
    }
    
    public function loadAllPlugins()
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_modules WHERE type = :type ORDER BY name");
        $this->db->bindParameter("type", "s", "plugin");
        
        return $this->db->pselect();
    }
    
    public function loadPluginById($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_modules WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);
        
        return $this->db->pselect1();
    }
    
    public function loadPortalsWherePluginIsInstalled($id)
    {
        $this->db->setStatement("SELECT pm.*, p.name FROM t:lw_info_portals_modules pm, t:lw_info_portals p WHERE pm.mid = :id AND p.id = pm.pid ");
        $this->db->bindParameter("id", "i", $id);
        
        return $this->db->pselect();
    }
    
    public function getAmountOfPortalsWhereAPluginIsInstalled()
    {
        $this->db->setStatement("SELECT pm.mid, m.name, COUNT(pm.pid) as portals FROM t:lw_info_portals_modules pm, t:lw_info_modules m WHERE pm.mid = m.id AND m.type = :type GROUP BY pm.mid, m.name ");
        $this->db->bindParameter("type", "s", "plugin");
        
        $result = $this->db->pselect();
        
        $array = array();
        foreach($result as $module){
            $array[$module["name"]] = $module["portals"];
        }
        
        return $array;
    }
    
}
