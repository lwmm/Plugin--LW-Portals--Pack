<?php

namespace LwPortalsCollector\Model\Base\DataHandler;

class ListtoolBase
{

    protected function addScan($id, $stats)
    {
        $entryId = $this->isTodaysListtoolScanExisting($id);

        if (!$entryId) {
            return $this->addListtoolScan($id, $stats);
        } else {
            return $this->updateTodaysListtoolScan($id, $stats, $entryId);
        }
    }

    protected function isTodaysListtoolScanExisting($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals_listtool WHERE portal_id = :portal_id AND lw_date = :date ");
        $this->db->bindParameter("portal_id", "i", $id);
        $this->db->bindParameter("date", "i", date("Ymd"));

        $result = $this->db->pselect1();

        if (!empty($result)) {
            return $result["id"];
        }
        return false;
    }

    protected function addListtoolScan($id, $stats)
    {
        $this->db->setStatement("INSERT INTO t:lw_info_portals_listtool (portal_id, db_links, db_files, db_files_with_file, dir_files, list_count, lw_date) VALUES (:portal_id, :db_links, :db_files, :file_entry_with_file, :dir_files, :list_count, :lw_date) ");
        $this->db->bindParameter("portal_id", "i", $id);
        $this->db->bindParameter("db_links", "i", $stats["db_links"]);
        $this->db->bindParameter("db_files", "i", $stats["db_files"]);
        $this->db->bindParameter("file_entry_with_file", "i", $stats["db_files_with_file"]);
        $this->db->bindParameter("dir_files", "i", $stats["dir_files"]);
        $this->db->bindParameter("list_count", "i", $stats["list_count"]);
        $this->db->bindParameter("lw_date", "i", date("Ymd"));
        
        return $this->db->pdbquery();
    }

    protected function updateTodaysListtoolScan($id, $stats, $entryId)
    {
        $this->db->setStatement("UPDATE t:lw_info_portals_listtool SET portal_id = :portal_id, db_links = :db_links, db_files = :db_files, db_files_with_file = :file_entry_with_file, dir_files = :dir_files, list_count = :list_count WHERE portal_id = :portal_id AND id = :id ");
        $this->db->bindParameter("id", "i", $entryId);
        $this->db->bindParameter("portal_id", "i", $id);
        $this->db->bindParameter("db_links", "i", $stats["db_links"]);
        $this->db->bindParameter("db_files", "i", $stats["db_files"]);
        $this->db->bindParameter("file_entry_with_file", "i", $stats["db_files_with_file"]);
        $this->db->bindParameter("dir_files", "i", $stats["dir_files"]);
        $this->db->bindParameter("list_count", "i", $stats["list_count"]);
        
        return $this->db->pdbquery();
    }

}
