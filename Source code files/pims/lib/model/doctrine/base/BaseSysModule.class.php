<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('SysModule', 'doctrine');

/**
 * BaseSysModule
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $module_id
 * @property string $name
 * @property string $description
 * @property Doctrine_Collection $Rights
 * 
 * @method string              getModuleId()    Returns the current record's "module_id" value
 * @method string              getName()        Returns the current record's "name" value
 * @method string              getDescription() Returns the current record's "description" value
 * @method Doctrine_Collection getRights()      Returns the current record's "Rights" collection
 * @method SysModule           setModuleId()    Sets the current record's "module_id" value
 * @method SysModule           setName()        Sets the current record's "name" value
 * @method SysModule           setDescription() Sets the current record's "description" value
 * @method SysModule           setRights()      Sets the current record's "Rights" collection
 * 
 * @package    pims
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseSysModule extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('sys_module');
        $this->hasColumn('module_id', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('name', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('description', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('Rights', array(
             'local' => 'module_id',
             'foreign' => 'module_id'));
    }
}