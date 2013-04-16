<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('User', 'doctrine');

/**
 * BaseUser
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $user_id
 * @property string $user_name
 * @property string $user_password
 * @property string $is_admin
 * @property string $status
 * @property string $user_group_id
 * @property UserGroup $UserGroup
 * 
 * @method string    getUserId()        Returns the current record's "user_id" value
 * @method string    getUserName()      Returns the current record's "user_name" value
 * @method string    getUserPassword()  Returns the current record's "user_password" value
 * @method string    getIsAdmin()       Returns the current record's "is_admin" value
 * @method string    getStatus()        Returns the current record's "status" value
 * @method string    getUserGroupId()   Returns the current record's "user_group_id" value
 * @method UserGroup getUserGroup()     Returns the current record's "UserGroup" value
 * @method User      setUserId()        Sets the current record's "user_id" value
 * @method User      setUserName()      Sets the current record's "user_name" value
 * @method User      setUserPassword()  Sets the current record's "user_password" value
 * @method User      setIsAdmin()       Sets the current record's "is_admin" value
 * @method User      setStatus()        Sets the current record's "status" value
 * @method User      setUserGroupId()   Sets the current record's "user_group_id" value
 * @method User      setUserGroup()     Sets the current record's "UserGroup" value
 * 
 * @package    pims
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BaseUser extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('user');
        $this->hasColumn('user_id', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('user_name', 'string', 45, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 45,
             ));
        $this->hasColumn('user_password', 'string', 45, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 45,
             ));
        $this->hasColumn('is_admin', 'string', 3, array(
             'type' => 'string',
             'fixed' => 1,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 3,
             ));
        $this->hasColumn('status', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('user_group_id', 'string', 20, array(
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
        $this->hasOne('UserGroup', array(
             'local' => 'user_group_id',
             'foreign' => 'user_group_id'));
    }
}