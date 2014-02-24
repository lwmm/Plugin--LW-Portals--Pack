<?php

namespace LwPortalsCollector\Model\Base\DataHandler;

class StatsBase
{

    protected function addStats($id, $stats)
    {
        $entryId = $this->isTodaysStatsScanExisting($id);

        if (!$entryId) {
            return $this->addStatsScan($id, $stats);
        } else {
            return $this->updateTodayStatsScan($id, $stats, $entryId);
        }
    }

    protected function isTodaysStatsScanExisting($id)
    {
        $this->db->setStatement("SELECT * FROM t:lw_info_portals_stats WHERE portal_id = :portal_id AND lw_date = :date ");
        $this->db->bindParameter("portal_id", "i", $id);
        $this->db->bindParameter("date", "i", date("Ymd"));

        $result = $this->db->pselect1();

        if (!empty($result)) {
            return $result["id"];
        }
        return false;
    }

    protected function addStatsScan($id, $stats)
    {
        $uniquePlaceholerArray = array();

        foreach ($stats as $column => $value) {
            $rnd = rand(10, 999);
            while (array_key_exists($rnd, $uniquePlaceholerArray)) {
                $rnd = rand(10, 999);
            }
            $uniquePlaceholerArray[$column] = trim(substr(sha1(date("His") . $rnd), 0, 8));

            $columns_str.= $column . " ,";
            $placeholders_str.= ":" . $uniquePlaceholerArray[$column] . " ,";
        }

        $columns_str = substr($columns_str, 0, strlen($columns_str) - 1);
        $placeholders_str = substr($placeholders_str, 0, strlen($placeholders_str) - 1);

        $this->db->setStatement("INSERT INTO t:lw_info_portals_stats ( portal_id, " . $columns_str . ", lw_date ) VALUES ( :portal_id, " . $placeholders_str . ", :date ) ");
        $this->db->bindParameter("portal_id", "i", $id);
        foreach ($stats as $column => $value) {
            $this->db->bindParameter($uniquePlaceholerArray[$column], "i", $value);
        }
        $this->db->bindParameter("date", "i", date("Ymd"));

        return $this->db->pdbquery();
    }

    protected function updateTodayStatsScan($id, $stats, $entryId)
    {
        $uniquePlaceholerArray = array();

        foreach ($stats as $column => $value) {
            $rnd = rand(10, 999);
            while (array_key_exists($rnd, $uniquePlaceholerArray)) {
                $rnd = rand(10, 999);
            }
            $uniquePlaceholerArray[$column] = trim(substr(sha1(date("His") . $rnd), 0, 8));

            $updateString .= $column . " = :" . $uniquePlaceholerArray[$column] . " ,";
        }

        $updateString = substr($updateString, 0, strlen($updateString) - 1);

        $this->db->setStatement("UPDATE t:lw_info_portals_stats SET " . $updateString . " WHERE portal_id = :portal_id AND id = :entryId ");
        $this->db->bindParameter("portal_id", "i", $id);
        $this->db->bindParameter("entryId", "i", $entryId);
        foreach ($stats as $column => $value) {
            $this->db->bindParameter($uniquePlaceholerArray[$column], "i", $value);
        }

        return $this->db->pdbquery();
    }

}
