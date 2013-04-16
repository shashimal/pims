<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('PatientCategory', 'doctrine');

/**
 * BasePatientCategory
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $category_id
 * @property string $patient_category
 * @property string $description
 * 
 * @method string          getCategoryId()       Returns the current record's "category_id" value
 * @method string          getPatientCategory()  Returns the current record's "patient_category" value
 * @method string          getDescription()      Returns the current record's "description" value
 * @method PatientCategory setCategoryId()       Sets the current record's "category_id" value
 * @method PatientCategory setPatientCategory()  Sets the current record's "patient_category" value
 * @method PatientCategory setDescription()      Sets the current record's "description" value
 * 
 * @package    pims
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePatientCategory extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('patient_category');
        $this->hasColumn('category_id', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('patient_category', 'string', 30, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 30,
             ));
        $this->hasColumn('description', 'string', 40, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 40,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        
    }
}