<?php

namespace LwPortalsHttpsCheck\Model\Base\Object;

class ObjectBase
{

    protected $id;
    protected $values;
    
    public function __construct($id)
    {
        $this->id = $id;
    }

    public function setValues($values)
    {
        $this->values = $values;
    }

    public function getValueByKey($key)
    {
        return $this->values[$key];
    }

    public function getValues()
    {
        return $this->values;
    }
    
    public function getId()
    {
        return $this->id;
    }

}
