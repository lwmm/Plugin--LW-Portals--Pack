<?php

namespace LwPortalsCollector\Module;

class Packages extends \LwPortalsCollector\Model\Base\DataHandler\ModulesBase
{

    protected $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function execute($id, $packages)
    {
        $this->deletePortalModuleConnections($id, "package");

        foreach ($packages as $package) {
            if (!$this->isModuleExisting($package["packagename"], "package")) {
                $this->addModule($package["packagename"], "package");
            }
            $this->addPortalModuleConnection($id, $this->getModuleIdByName($package["packagename"]));
        }
    }

}
