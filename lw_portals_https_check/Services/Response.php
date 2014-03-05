<?php
/**
 * This object is a container for informations which
 * can be used in this plugin by different classes.
 *
 * @author Andreas Eckhoff <andreas.eckhoff@logic-works.de>
 */

namespace LwPortalsHttpsCheck\Services;

class Response
{
    private $parameter;
    private $output;
    private $data;    
    private static $instance = null;
    
    
    public static function getInstance()
    {
        if(self::$instance == null)
        {
            self::$instance = new Response();
        }
        return self::$instance;
    }
    
    public function setParameterByKey($key, $value)
    {
        $this->parameter[$key] = $value;
    }
    
    public function getParameterByKey($key)
    {
        return $this->parameter[$key];
    }
    
    public function getParameterArray()
    {
        return $this->parameter;
    }
    
    public function setDataByKey($key, $value)
    {
        $this->data[$key] = $value;
    }
    
    public function getDataByKey($key)
    {
        return $this->data[$key];
    }
    
    public function getDataArray()
    {
        return $this->data;
    }
    
    public function setOutputByKey($key, $output)
    {
        $this->output[$key] = $output;
    }
    
    public function getOutputByKey($key)
    {
        return $this->output[$key];
    }
}