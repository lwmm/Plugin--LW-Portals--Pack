<?php

namespace LwPortalsCollector\Module;

class Plugins extends \LwPortalsCollector\Model\Base\DataHandler\ModulesBase
{

    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function execute($id, $plugins)
    {
        $this->deletePortalModuleConnections($id, "plugin");

        foreach ($plugins as $plugingroup) {
            foreach ($plugingroup as $plugin) {
                if (!$this->isModuleExisting($plugin["pluginname"], "plugin")) {
                    $this->addModule($plugin["pluginname"], "plugin");
                }
                $this->addPortalModuleConnection($id, $this->getModuleIdByName($plugin["pluginname"]));
            }
        }
    }

}
