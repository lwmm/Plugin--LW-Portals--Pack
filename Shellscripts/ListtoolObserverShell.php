<?php

error_reporting(E_ALL & ~E_WARNING & ~E_NOTICE & ~E_DEPRECATED);

$config = parse_ini_file("/var/www/c38/lw_configs/conf.inc.php", true);

class Autoloader
{

    public function __construct($config)
    {
        $this->config = $config;
        spl_autoload_register(array($this, 'loader'));
    }

    private function loader($className)
    {
        if (strstr($className, 'lw_')) {
            $path = $this->config["path"]["framework"] . "lw/";
            $filename = $path . $className . ".class";
        }

        $filename = str_replace('\\', '/', $filename) . '.php';

        if (is_file($filename)) {
            include_once($filename);
        }
    }

}

$autoloader = new Autoloader($config);
if ($config["lwdb"]["type"] == "mysql" || $config["lwdb"]["type"] == "mysqli") {
    include_once($config["path"]["framework"] . "lw/lw_db_mysqli.class.php");
    $db = new \lw_db_mysqli($config["lwdb"]["user"], $config["lwdb"]["pass"], $config["lwdb"]["host"], $config["lwdb"]["name"]);
    $db->connect();
} elseif ($config["lwdb"]["type"] == "oracle") {
    include_once($config["path"]["framework"] . "lw/lw_db_oracle.class.php");
    $db = new \lw_db_oracle($config["lwdb"]["user"], $config["lwdb"]["pass"], $config["lwdb"]["host"], $config["lwdb"]["name"]);
    $db->connect();
}

$array = array();

$db->setStatement("SELECT * FROM t:lw_master WHERE lw_object = :lw_object ");
$db->bindParameter("lw_object", "s", "lw_portals_config");
$result = $db->pselect1();
$portalsConfig = unserialize($result["opt1clob"]);

$db->setStatement("SELECT * FROM t:lw_info_portals WHERE scan_exclude = 0 OR scan_exclude IS NULL ORDER BY name ");
$scanablePortals = $db->pselect();

foreach ($scanablePortals as $portal) {
    $temp = array();

    $db->setStatement("SELECT * FROM t:lw_info_portals_listtool WHERE portal_id = :id ORDER BY lw_date DESC ");
    $db->bindParameter("id", "i", $portal["id"]);
    $result = $db->pselect(0, 2);

    $temp["portalID"] = $portal["id"];
    $temp["portalName"] = $portal["name"];
    $temp["scans"] = $result;

    $array[] = $temp;
}

$errors = array();

foreach ($array as $arr) {
    $dir_files_old = $arr["scans"][1]["dir_files"];
    $dir_files_new = $arr["scans"][0]["dir_files"];
    $db_files_old = $arr["scans"][1]["db_files"];
    $db_files_new = $arr["scans"][0]["db_files"];
    $db_files_with_file_old = $arr["scans"][1]["db_files_with_file"];
    $db_files_with_file_new = $arr["scans"][0]["db_files_with_file"];
    $list_count_old = $arr["scans"][1]["list_count"];
    $list_count_new = $arr["scans"][0]["list_count"];

    if ($dir_files_new != $db_files_with_file_new) {

        $errors[$arr["portalName"]]["error"][] = utf8_decode(
                "Die Anzahl der Datei-Einträgen ( mit gesetzten opt1file-Namen ) stimmt nicht mehr mit der Anzahl an gespsicherten Dateien übrein." . PHP_EOL . PHP_EOL .
                "Alte Anzahl von DB-Einträgen: " . $db_files_with_file_old . PHP_EOL .
                "Alte Anzahl von Dateien im Listtool-Verzeichnis: " . $dir_files_old . PHP_EOL . PHP_EOL .
                "Aktuelle Anzahl von DB-Einträgen: " . $db_files_with_file_new . PHP_EOL .
                "Aktuelle Anzahl von Dateien im Listtool-Verzeichnis: " . $dir_files_new . PHP_EOL
        );
    }

    if ($db_files_new >= $portalsConfig["files_min_value"] && $db_files_old >= $portalsConfig["files_min_value"]) {
        $percent = round($db_files_new / $db_files_old * 100, 2);

        if ($percent < 100) {
            $reduceInPercent = 100 - $percent;
            if ($reduceInPercent >= $portalsConfig["files_warn_value"]) {
                $errors[$arr["portalName"]]["warning"][] = utf8_decode(
                        "Die Anzahl der Datei-Einträge hat sich verringert." . PHP_EOL .
                        "Verminderung von " . $reduceInPercent . " %" . PHP_EOL . PHP_EOL .
                        "Alter Wert: " . $db_files_old . PHP_EOL .
                        "Neuer Wert: " . $db_files_new . PHP_EOL
                );
            }
        }
    }

    if ($list_count_new >= $portalsConfig["list_min_value"] && $list_count_old >= $portalsConfig["list_min_value"]) {
        $percent = round($list_count_new / $list_count_old * 100, 2);

        if ($percent < 100) {
            $reduceInPercent = 100 - $percent;
            if ($reduceInPercent >= $portalsConfig["list_warn_value"]) {
                $errors[$arr["portalName"]]["warning"][] = utf8_decode(
                        "Die Anzahl der Listen hat sich verringert." . PHP_EOL .
                        "Verminderung von " . $reduceInPercent . " %" . PHP_EOL . PHP_EOL .
                        "Alter Wert: " . $list_count_old . PHP_EOL .
                        "Neuer Wert: " . $list_count_new . PHP_EOL
                );
            }
        }
    }
}

$subject = "Infosystem - Listtool";
$message = "";

foreach ($errors as $portal => $err) {
    $message .= "PORTAL: " . $portal . PHP_EOL;

    if (isset($err["error"])) {
        $message .= "##################### ERRORS #########################" . PHP_EOL . PHP_EOL;
        foreach ($err["error"] as $e) {
            $message .= "------" . PHP_EOL . PHP_EOL;
            $message .= $e . PHP_EOL;
        }
        $message .= "------" . PHP_EOL . PHP_EOL;
    }
    if (isset($err["warning"])) {
        $message .= "##################### WARNINGS ########################" . PHP_EOL . PHP_EOL;
        foreach ($err["warning"] as $w) {
            $message .= "------" . PHP_EOL . PHP_EOL;
            $message .= $w . PHP_EOL;
        }
        $message .= "------" . PHP_EOL . PHP_EOL;
    }
}

die($subject . PHP_EOL . PHP_EOL . $message);

foreach($portalsConfig["email"] as $email){
    if(filter_var($email, FILTER_VALIDATE_EMAIL)){
        mail($email, $subject, $message);
    }
}