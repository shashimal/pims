<?php
// Connection Component Binding
Doctrine_Manager::getInstance()->bindComponent('Patient', 'doctrine');

/**
 * BasePatient
 * 
 * This class has been auto-generated by the Doctrine ORM Framework
 * 
 * @property string $patient_no
 * @property date $registered_date
 * @property string $first_name
 * @property string $last_name
 * @property string $current_address
 * @property string $permanent_address
 * @property string $contact_address
 * @property string $telephone1
 * @property string $telephone2
 * @property string $mobile
 * @property string $email
 * @property string $nic_pp_no
 * @property date $date_of_birth
 * @property integer $sex
 * @property integer $marital_status
 * @property string $nationality
 * @property string $education
 * @property string $occupation
 * @property string $category
 * @property integer $deleted
 * @property string $comment
 * @property Doctrine_Collection $ClinicReason
 * @property Doctrine_Collection $ContactMode
 * @property Doctrine_Collection $Episode
 * @property Doctrine_Collection $StdResult
 * @property Doctrine_Collection $TraceContact
 * @property Doctrine_Collection $Visit
 * 
 * @method string              getPatientNo()         Returns the current record's "patient_no" value
 * @method date                getRegisteredDate()    Returns the current record's "registered_date" value
 * @method string              getFirstName()         Returns the current record's "first_name" value
 * @method string              getLastName()          Returns the current record's "last_name" value
 * @method string              getCurrentAddress()    Returns the current record's "current_address" value
 * @method string              getPermanentAddress()  Returns the current record's "permanent_address" value
 * @method string              getContactAddress()    Returns the current record's "contact_address" value
 * @method string              getTelephone1()        Returns the current record's "telephone1" value
 * @method string              getTelephone2()        Returns the current record's "telephone2" value
 * @method string              getMobile()            Returns the current record's "mobile" value
 * @method string              getEmail()             Returns the current record's "email" value
 * @method string              getNicPpNo()           Returns the current record's "nic_pp_no" value
 * @method date                getDateOfBirth()       Returns the current record's "date_of_birth" value
 * @method integer             getSex()               Returns the current record's "sex" value
 * @method integer             getMaritalStatus()     Returns the current record's "marital_status" value
 * @method string              getNationality()       Returns the current record's "nationality" value
 * @method string              getEducation()         Returns the current record's "education" value
 * @method string              getOccupation()        Returns the current record's "occupation" value
 * @method string              getCategory()          Returns the current record's "category" value
 * @method integer             getDeleted()           Returns the current record's "deleted" value
 * @method string              getComment()           Returns the current record's "comment" value
 * @method Doctrine_Collection getClinicReason()      Returns the current record's "ClinicReason" collection
 * @method Doctrine_Collection getContactMode()       Returns the current record's "ContactMode" collection
 * @method Doctrine_Collection getEpisode()           Returns the current record's "Episode" collection
 * @method Doctrine_Collection getStdResult()         Returns the current record's "StdResult" collection
 * @method Doctrine_Collection getTraceContact()      Returns the current record's "TraceContact" collection
 * @method Doctrine_Collection getVisit()             Returns the current record's "Visit" collection
 * @method Patient             setPatientNo()         Sets the current record's "patient_no" value
 * @method Patient             setRegisteredDate()    Sets the current record's "registered_date" value
 * @method Patient             setFirstName()         Sets the current record's "first_name" value
 * @method Patient             setLastName()          Sets the current record's "last_name" value
 * @method Patient             setCurrentAddress()    Sets the current record's "current_address" value
 * @method Patient             setPermanentAddress()  Sets the current record's "permanent_address" value
 * @method Patient             setContactAddress()    Sets the current record's "contact_address" value
 * @method Patient             setTelephone1()        Sets the current record's "telephone1" value
 * @method Patient             setTelephone2()        Sets the current record's "telephone2" value
 * @method Patient             setMobile()            Sets the current record's "mobile" value
 * @method Patient             setEmail()             Sets the current record's "email" value
 * @method Patient             setNicPpNo()           Sets the current record's "nic_pp_no" value
 * @method Patient             setDateOfBirth()       Sets the current record's "date_of_birth" value
 * @method Patient             setSex()               Sets the current record's "sex" value
 * @method Patient             setMaritalStatus()     Sets the current record's "marital_status" value
 * @method Patient             setNationality()       Sets the current record's "nationality" value
 * @method Patient             setEducation()         Sets the current record's "education" value
 * @method Patient             setOccupation()        Sets the current record's "occupation" value
 * @method Patient             setCategory()          Sets the current record's "category" value
 * @method Patient             setDeleted()           Sets the current record's "deleted" value
 * @method Patient             setComment()           Sets the current record's "comment" value
 * @method Patient             setClinicReason()      Sets the current record's "ClinicReason" collection
 * @method Patient             setContactMode()       Sets the current record's "ContactMode" collection
 * @method Patient             setEpisode()           Sets the current record's "Episode" collection
 * @method Patient             setStdResult()         Sets the current record's "StdResult" collection
 * @method Patient             setTraceContact()      Sets the current record's "TraceContact" collection
 * @method Patient             setVisit()             Sets the current record's "Visit" collection
 * 
 * @package    pims
 * @subpackage model
 * @author     Your name here
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class BasePatient extends sfDoctrineRecord
{
    public function setTableDefinition()
    {
        $this->setTableName('patient');
        $this->hasColumn('patient_no', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('registered_date', 'date', 25, array(
             'type' => 'date',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('first_name', 'string', 30, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 30,
             ));
        $this->hasColumn('last_name', 'string', 30, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 30,
             ));
        $this->hasColumn('current_address', 'string', 50, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 50,
             ));
        $this->hasColumn('permanent_address', 'string', 50, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 50,
             ));
        $this->hasColumn('contact_address', 'string', 50, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 50,
             ));
        $this->hasColumn('telephone1', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('telephone2', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('mobile', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('email', 'string', 40, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 40,
             ));
        $this->hasColumn('nic_pp_no', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('date_of_birth', 'date', 25, array(
             'type' => 'date',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 25,
             ));
        $this->hasColumn('sex', 'integer', 2, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 2,
             ));
        $this->hasColumn('marital_status', 'integer', 2, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 2,
             ));
        $this->hasColumn('nationality', 'string', 30, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 30,
             ));
        $this->hasColumn('education', 'string', 45, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 45,
             ));
        $this->hasColumn('occupation', 'string', 30, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 30,
             ));
        $this->hasColumn('category', 'string', 20, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 20,
             ));
        $this->hasColumn('deleted', 'integer', 2, array(
             'type' => 'integer',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             'length' => 2,
             ));
        $this->hasColumn('comment', 'string', 50, array(
             'type' => 'string',
             'fixed' => 0,
             'unsigned' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             'length' => 50,
             ));
    }

    public function setUp()
    {
        parent::setUp();
        $this->hasMany('ClinicReason', array(
             'local' => 'patient_no',
             'foreign' => 'patient_no'));

        $this->hasMany('ContactMode', array(
             'local' => 'patient_no',
             'foreign' => 'patient_no'));

        $this->hasMany('Episode', array(
             'local' => 'patient_no',
             'foreign' => 'patient_no'));

        $this->hasMany('StdResult', array(
             'local' => 'patient_no',
             'foreign' => 'patient_no'));

        $this->hasMany('TraceContact', array(
             'local' => 'patient_no',
             'foreign' => 'patient_no'));

        $this->hasMany('Visit', array(
             'local' => 'patient_no',
             'foreign' => 'patient_no'));
    }
}