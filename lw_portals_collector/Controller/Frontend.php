<?php

namespace LwPortalsCollector\Controller;

class Frontend
{

    protected $request;
    protected $admin;
    protected $pageIndexes;
    protected $db;

    public function __construct()
    {
        $this->request = \lw_registry::getInstance()->getEntry("request");
        $this->db = \lw_registry::getInstance()->getEntry("db");
    }

    public function setAdmin($bool)
    {
        if ($bool === true) {
            $this->admin = true;
        } else {
            $this->admin = false;
        }
    }

    public function setPageIndexes($pageIndexes)
    {
        $this->pageIndexes = $pageIndexes;
    }

    protected function isAdmin()
    {
        return $this->admin;
    }

    public function execute()
    {
        if ($this->isAdmin()) {
            $id = 0;
            if ($this->request->getInt("id")) {
                $id = $this->request->getInt("id");
            }

            $collector = new \LwPortalsCollector\Controller\InfoCollector($this->db);

            if ($id > 0) {
                $response = \LwPortalsCollector\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
                $entity = $response->getDataByKey("PortalEntity");
                $collector->setEntity($entity);
            } else {
                $response = \LwPortalsCollector\Model\Portal\CommandResolver\getPortalsCollection::getInstance()->resolve();
                $collection = $response->getDataByKey("PortalEntitiesCollection");
                $collector->setCollection($collection);
            }

            $collector->execute();

            $indexPortals = 0;
            if (isset($this->pageIndexes["portals"])) {
                $indexPortals = $this->pageIndexes["portals"];
            }

            if ($id > 0) {
                \LwPortalsCollector\Services\Page::reload(\lw_page::getInstance($indexPortals)->getUrl(array("portalsPlugin" => "LwPortalsList", "cmd" => "showDetail", "id" => $id, "response" => 1)));
            } else {
                \LwPortalsCollector\Services\Page::reload(\lw_page::getInstance($indexPortals)->getUrl(array("portalsPlugin" => "LwPortalsList", "cmd" => "showList", "response" => 1)));
            }
        }
    }

}
