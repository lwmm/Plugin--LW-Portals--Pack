<?php

namespace LwPortalsTableSearch\Model\Table\CommandResolver;

class getTablesCollection
{
    protected $request;
    protected $respone;
    protected $params;
    protected $data;

    public function __construct($params, $data)
    {
        $this->params = $params;
        $this->data = $data;
        $this->respone = \LwPortalsTableSearch\Services\Response::getInstance();
    }
    
    public static function getInstance($params = false, $data = false)
    {
        return new getTablesCollection($params, $data);
    }
    
    public function resolve()
    {
        $collection = array();

        $tableArray = $this->getTableArray();
        
        $i = 1;
        foreach($tableArray as $tableName => $searchableFields){
            $temp = array(
                "name" => $tableName,
                "searchableFields" => $searchableFields
            );
            $entity = new \LwPortalsTableSearch\Model\Table\Object\Table($i++);
            $entity->setValues($temp);
            $collection[] = $entity;
        }

        $this->respone->setDataByKey("TableEntitiesCollection", $collection);
        return $this->respone;
    }
    
    protected function getTableArray()
    {
        $tables = array(
            "lw_cobject" => array("name"),
            "lw_cobject_cat" => array("name"),
            "lw_intranets" => array("name"),
            "lw_in_user" => array("name"),
            "lw_pages" => array("name", "title", "urlname"),
            "lw_pages_cat" => array("name"),
            "lw_project" => array("name"),
            "lw_roles" => array("name"),
            "lw_templates" => array("name", "template"),
            "lw_types" => array("name"),
            "lw_user" => array("name", "firstname", "lastname")
        );
              
        foreach ($tables as $tableName => $searchableFields){
            $arr[] = $tableName;
            foreach ($tables[$tableName] as $nr => $field){
                $arr2[$nr] = $field;
            }
            array_multisort($arr2, SORT_ASC, $tables[$tableName]);
        }
        array_multisort($arr, SORT_ASC, $tables);
        
        
        return $tables;        
    }
}