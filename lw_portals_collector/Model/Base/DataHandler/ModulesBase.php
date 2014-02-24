<?php

namespace LwPortalsCollector\Model\Base\DataHandler;

class ModulesBase
{
    
    protected function isModuleExisting($module, $type)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_modules WHERE name = :name AND type = :type ");
        $this->db->bindParameter("name", "s", trim($module));
        $this->db->bindParameter("type", "s", $type);

        $result = $this->db->pselect1();
        if (!empty($result)) {
            return true;
        }
        return false;
    }

    protected function addModule($module, $type)
    {
        $this->db->setStatement("INSERT INTO t:lw_info_modules (name, type) VALUES (:name, :type) ");
        $this->db->bindParameter("name", "s", trim($module));
        $this->db->bindParameter("type", "s", $type);

        return $this->db->pdbquery();
    }

    protected function getModuleIdByName($module)
    {
        $this->db->setStatement("SELECT id FROM t:lw_info_modules WHERE name = :name ");
        $this->db->bindParameter("name", "s", trim($module));
        $result = $this->db->pselect1();

        return $result["id"];
    }

    protected function addPortalModuleConnection($portalId, $moduleId)
    {
        $this->db->setStatement("INSERT INTO t:lw_info_portals_modules (pid, mid) VALUES (:pid, :mid) ");
        $this->db->bindParameter("pid", "i", $portalId);
        $this->db->bindParameter("mid", "i", $moduleId);

        return $this->db->pdbquery();
    }

    protected function deletePortalModuleConnections($portalId, $type)
    {
        $this->db->setStatement("SELECT id FROM t:lw_info_modules WHERE type = :type ");
        $this->db->bindParameter("type", "s", $type);
        $result = $this->db->pselect();
        
        if(!empty($result)){            
            $str = "";
            foreach($result as $module){
                $str.= " mid = " . $module["id"] . " OR";
            }

            $str = substr($str, 0, strlen($str) - 2);                
            $this->db->setStatement("DELETE FROM t:lw_info_portals_modules WHERE pid = :pid AND ( " . $str . "  ) ");
            $this->db->bindParameter("pid", "i", $portalId);

            return $this->db->pdbquery();
        }
    }

}
