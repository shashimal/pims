<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Rights', 'doctrine');

/**
 * BaseRights
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $user_group_id
 * @property string $module_id
 * @property integer $adding
 * @property integer $editing
 * @property integer $deleting
 * @property integer $viewing
 * @property UserGroup $UserGroup
 * @property SysModule $SysModule
 * 
 * @method string    getUserGroupId()   Returns the current record's "user_group_id" value
 * @method string    getModuleId()      Returns the current record's "module_id" value
 * @method integer   getAdding()        Returns the current record's "adding" value
 * @method integer   getEditing()       Returns the current record's "editing" value
 * @method integer   getDeleting()      Returns the current record's "deleting" value
 * @method integer   getViewing()       Returns the current record's "viewing" value
 * @method UserGroup getUserGroup()     Returns the current record's "UserGroup" value
 * @method SysModule getSysModule()     Returns the current record's "SysModule" value
 * @method Rights    setUserGroupId()   Sets the current record's "user_group_id" value
 * @method Rights    setModuleId()      Sets the current record's "module_id" value
 * @method Rights    setAdding()        Sets the current record's "adding" value
 * @method Rights    setEditing()       Sets the current record's "editing" value
 * @method Rights    setDeleting()      Sets the current record's "deleting" value
 * @method Rights    setViewing()       Sets the current record's "viewing" value
 * @method Rights    setUserGroup()     Sets the current record's "UserGroup" value
 * @method Rights    setSysModule()     Sets the current record's "SysModule" value
 * 
 * @package    pims
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseRights extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('rights');
        $this->hasColumn('user_group_id', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('module_id', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('adding', 'integer', 2, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 2,
             ));
        $this->hasColumn('editing', 'integer', 2, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 2,
             ));
        $this->hasColumn('deleting', 'integer', 2, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 2,
             ));
        $this->hasColumn('viewing', 'integer', 2, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 2,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasOne('UserGroup', array(
             'local' => 'user_group_id',
             'foreign' => 'user_group_id'));

        $this->hasOne('SysModule', array(
             'local' => 'module_id',
             'foreign' => 'module_id'));
    }
}