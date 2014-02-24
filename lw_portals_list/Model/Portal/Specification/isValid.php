<?php

namespace LwPortalsList\Model\Portal\Specification;

define("REQUIRED", "1");    # array( 1 => array( "error" => 1, "options" => "" ));
define("MAXLENGTH", "2");   # array( 2 => array( "error" => 1, "options" => array( "maxlength" => $maxlength, "actuallength" => $strlen ) ));
define("DIGITFIELD", "3");  # array( 6 => array( "error" => 1, "options" => "" ));
define("SYNTAXURL", "4");
define("BOOL", "5");

class isValid extends \LwPortalsList\Model\Base\Specification\isValidBase
{

    public function __construct()
    {
        $this->errors = array();

        $this->allowedKeys = array(
            "id",
            "name",
            "server",
            "path",
            "url",
            "piwik_id",
            "scan_exclude"
        );
    }
    
    static public function getInstance()
    {
        return new isValid();
    }

    protected function nameValidate($key, $object)
    {
        return $this->defaultValidation($key, $object->getValueByKey($key), 255, true);
    }

    protected function serverValidate($key, $object)
    {
        return $this->defaultValidation($key, $object->getValueByKey($key), 255, true);
    }

    protected function pathValidate($key, $object)
    {
        return $this->defaultValidation($key, $object->getValueByKey($key), 255, true);
    }

    protected function urlValidate($key, $object)
    {
        $ok = true;
        if (!$this->defaultValidation($key, $object->getValueByKey($key), 255, true)) {
            $ok = false;
        }

        if ($object->getValueByKey($key) != "" && !filter_var($object->getValueByKey($key), FILTER_VALIDATE_URL)) {
            $this->addError($key, SYNTAXURL);
            $ok = false;
        }
        return $ok;
    }

    protected function piwik_idValidate($key, $object)
    {
        $ok = true;

        if ($object->getValueByKey($key) != "" && !ctype_digit($object->getValueByKey($key))) {
            $this->addError($key, DIGITFIELD);
            $ok = false;
        }

        if (!$this->requiredValidation($key, $object->getValueByKey($key))) {
            $ok = false;
        }

        return $ok;
    }
    
    protected function scan_excludeValidate($key, $object)
    {
        $ok = true;
        
        if ($object->getValueByKey($key) != "" && !ctype_digit($object->getValueByKey($key))) {
            $this->addError($key, DIGITFIELD);
            $ok = false;
        }
        if ($object->getValueByKey($key) != "" && $object->getValueByKey($key) > 1) {
            $this->addError($key, BOOL);
            $ok = false;
        }
        
        return $ok;
    }

}
