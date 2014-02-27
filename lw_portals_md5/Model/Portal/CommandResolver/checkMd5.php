<?php

namespace LwPortalsMd5\Model\Portal\CommandResolver;

class checkMd5
{

    protected $request;
    protected $respone;
    protected $params;
    protected $data;

    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
        $this->respone = \LwPortalsMd5\Services\Response::getInstance();
        $this->request = \lw_registry::getInstance()->getEntry("request");
        ini_set("max_execution_time", 600); #10 min
    }

    public static function getInstance($params = false, $data = false)
    {
        return new checkMd5($params, $data);
    }

    public function resolve()
    {
        $result = array();
        $fileDataArray = $this->request->getFileData("inputFile");
        
        if (!empty($fileDataArray["name"])) {
            $csv = file_get_contents($fileDataArray["tmp_name"]);
        } else {
            $csv = $this->request->getRaw("configPath") . "," . $this->request->getRaw("path") . "," . $this->request->getRaw("expectedMd5") . ";";
            $this->respone->setDataByKey("postArray", array("configPath" => str_replace("CONFIG:path_", "", $this->request->getRaw("configPath")), "path" => $this->request->getRaw("path"), "expectedMd5" => $this->request->getRaw("expectedMd5")));
        }

        $files = $this->prepareFileArray($csv);

        if ($this->request->getInt("id") && $this->request->getInt("id") > 0) {
            $response = \LwPortalsMd5\Model\Portal\CommandResolver\getPortalEntityById::getInstance(array("id" => $this->request->getInt("id")))->resolve();
            $entity = $response->getDataByKey("PortalEntity");
            $result[$entity->getValueByKey("name")] = $this->compareFiles($entity, $files);
        } else {
            $response = \LwPortalsMd5\Model\Portal\CommandResolver\getPortalsCollection::getInstance()->resolve();
            $collection = $response->getDataByKey("PortalEntitiesCollection");
            foreach ($collection as $entity) {
                $result[$entity->getValueByKey("name")] = $this->compareFiles($entity, $files);
            }
        } 
     
        $this->respone->setDataByKey("Md5Results", $result);
        return $this->respone;
    }

    protected function prepareFileArray($csv)
    {
        $array = array();

        $lines = explode(";", $csv);
        unset($lines[count($lines) - 1]);

        foreach ($lines as $line) {
            $lineContent = explode(",", $line);
            if (count($lineContent) == 3) {
                if (strstr($lineContent[0], "CONFIG:path_")) {
                    $lineContent[0] = str_replace("CONFIG:path_", "", $lineContent[0]);
                } else if (strstr($lineContent[0], "CONFIG:")) {
                    $lineContent[0] = str_replace("CONFIG:", "", $lineContent[0]);
                }

                $array[] = array(
                    "configPath" => trim($lineContent[0]),
                    "path" => trim($lineContent[1]),
                    "expectedMd5" => trim($lineContent[2])
                );
            }
        }
        return $array;
    }

    protected function compareFiles($entity, $files)
    {
        $array = array();

        if (substr($entity->getValueByKey("url"), -1) == "/") {
            $url = $entity->getValueByKey("url");
        } else {
            $url = $entity->getValueByKey("url") . "/";
        }
        foreach ($files as $file) {            
            $json = file_get_contents($url . "index.php?getSystemInfo=1&cmd=Md5&configPath=" . $file["configPath"] . "&filePath=" . urlencode($file["path"]) . "&expectedMd5=" . $file["expectedMd5"]);
            $result = json_decode($json, true);
            if (is_array($result)) {
                $array[] = $result;
            }
        }

        return $array;
    }

}
