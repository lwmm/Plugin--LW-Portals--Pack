<?php

namespace LwPortalsList\Model\Portal\DataHandler;

class QueryHandler
{

    protected $db;

    public function __construct()
    {
        $this->db = \lw_registry::getInstance()->getEntry("db");
    }

    public function loadAllPortals()
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals ORDER BY name ");
        return $this->db->pselect();
    }
    
    public function loadAllPortalsWhereScanIsAllowed()
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals WHERE scan_exclude = 0 ORDER BY name ");
        return $this->db->pselect();
    }

    public function loadPortalById($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals WHERE id = :id ");
        $this->db->bindParameter("id", "i", $id);

        return $this->db->pselect1();
    }

    public function loadPortalModules($id)
    {
        $this->db->setStatement("SELECT m.* FROM t:lw_info_portals_modules pm, t:lw_info_modules m WHERE pm.pid = :pid AND pm.mid = m.id ");
        $this->db->bindParameter("pid", "i", $id);

        return $this->db->pselect();
    }

    public function getAmountOfPortalsWhereAModuleIsInstalled()
    {
        $this->db->setStatement("SELECT pm.mid, m.name, COUNT(pm.pid) as portals  FROM t:lw_info_portals_modules pm, t:lw_info_modules m WHERE pm.mid = m.id  GROUP BY pm.mid, m.name ");
        $result = $this->db->pselect();

        $array = array();
        foreach ($result as $module) {
            $array[$module["name"]] = $module["portals"];
        }

        return $array;
    }

    public function getAmountOfInstalledPluginsAndPackagesByPortalId($id)
    {
        $this->db->setStatement("SELECT COUNT(*) as amount, m.type  FROM t:lw_info_portals_modules pm, t:lw_info_modules m WHERE pm.pid = :portalId AND pm.mid = m.id  GROUP BY m.type ");
        $this->db->bindParameter("portalId", "i", $id);

        $result = $this->db->pselect();

        $array = array();
        foreach ($result as $moduleTypeAmount) {
            if ($moduleTypeAmount["type"] == "plugin") {
                $array["plugins"] = $moduleTypeAmount["amount"];
            } else {
                $array["packages"] = $moduleTypeAmount["amount"];
            }
        }

        return $array;
    }

    public function getCountOfPortalByPiwikId($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals WHERE piwik_id = :id ");
        $this->db->bindParameter("id", "i", $id);

        return $this->db->pselect();
    }

    public function loadAcutalPortalStats($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals_stats WHERE portal_id = :id ORDER BY lw_date DESC ");
        $this->db->bindParameter("id", "i", $id);

        return $this->db->pselect(0, 1);
    }
    
    public function loadAcutalPrevious14ListtoolStats($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals_listtool WHERE portal_id = :id ORDER BY lw_date DESC ");
        $this->db->bindParameter("id", "i", $id);

        return $this->db->pselect(0, 14);
    }

}
