<?php

namespace LwPortalsCollector\Model\Base\DataHandler;

class InfoCollectorBase
{

    protected $lwListtool_id = 0;

    protected function cleanModuels()
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_modules m WHERE m.id NOT IN (SELECT pm.mid FROM t:lw_info_portals_modules pm) ");
        $result = $this->db->pselect();

        foreach ($result as $module) {
            $this->db->setStatement("DELETE FROM t:lw_info_modules WHERE id = :id ");
            $this->db->bindParameter("id", "i", $module["id"]);

            $this->db->pdbquery();
        }
    }

    protected function loadLwListtoolId()
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_modules WHERE name = :name AND type = :type ");
        $this->db->bindParameter("name", "s", "LwListtool");
        $this->db->bindParameter("type", "s", "package");
        $result = $this->db->pselect1();

        $this->lwListtool_id = $result["id"];
    }

    protected function isPackageLwListoolInstalledByPortalId($portal_id)
    {
        if ($this->lwListtool_id > 0) {
            $this->db->setStatement("SELECT * FROM t:lw_info_portals_modules WHERE pid = :pid AND mid = :mid ");
            $this->db->bindParameter("pid", "i", $portal_id);
            $this->db->bindParameter("mid", "i", $this->lwListtool_id);

            $result = $this->db->pselect1();
            if (empty($result)) {
                return false;
            }
            return true;
        } else {
            $this->loadLwListtoolId();
            return $this->isPackageLwListoolInstalledByPortalId($portal_id);
        }
    }

}
