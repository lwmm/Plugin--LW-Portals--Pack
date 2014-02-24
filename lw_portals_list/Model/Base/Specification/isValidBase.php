<?php

namespace LwPortalsList\Model\Base\Specification;

class isValidBase
{

    protected $allowedKeys;
    protected $errors;

    public function isSatisfiedBy($object)
    {
        $valid = true;
        foreach ($this->allowedKeys as $key) {
            $method = $key . "Validate";
            if (method_exists($this, $method)) {
                $result = $this->$method($key, $object);
                if ($result == false) {
                    $valid = false;
                }
            }
        }
        return $valid;
    }

    protected function addError($key, $number, $array = false)
    {
        $this->errors[$key][$number]['error'] = 1;
        $this->errors[$key][$number]['options'] = $array;
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function getErrorsByKey($key)
    {
        return $this->errors[$key];
    }

    protected function defaultValidation($key, $value, $length, $required = false)
    {
        $bool = true;

        if ($required === true) {
            $bool = $this->requiredValidation($key, $value);
        }

        if (strlen($value) > $length) {
            $this->addError($key, MAXLENGTH, array("maxlength" => $length, "actuallength" => strlen($value)));
            $bool = false;
        }

        if ($bool == false) {
            return false;
        }
        return true;
    }

    protected function requiredValidation($key, $value)
    {
        if ($value == "") {
            $this->addError($key, REQUIRED);
            return false;
        }
        return true;
    }

}
