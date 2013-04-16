<?php

/**
 * Patient filter form base class.
 *
 * @package    pims
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePatientFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'registered_date'   => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate(), 'with_empty' => false)),
      'first_name'        => new sfWidgetFormFilterInput(),
      'last_name'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'current_address'   => new sfWidgetFormFilterInput(),
      'permanent_address' => new sfWidgetFormFilterInput(),
      'contact_address'   => new sfWidgetFormFilterInput(),
      'telephone1'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'telephone2'        => new sfWidgetFormFilterInput(),
      'mobile'            => new sfWidgetFormFilterInput(),
      'email'             => new sfWidgetFormFilterInput(),
      'nic_pp_no'         => new sfWidgetFormFilterInput(),
      'date_of_birth'     => new sfWidgetFormFilterDate(array('from_date' => new sfWidgetFormDate(), 'to_date' => new sfWidgetFormDate())),
      'sex'               => new sfWidgetFormFilterInput(),
      'marital_status'    => new sfWidgetFormFilterInput(),
      'nationality'       => new sfWidgetFormFilterInput(),
      'education'         => new sfWidgetFormFilterInput(),
      'occupation'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'category'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'deleted'           => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'registered_date'   => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'first_name'        => new sfValidatorPass(array('required' => false)),
      'last_name'         => new sfValidatorPass(array('required' => false)),
      'current_address'   => new sfValidatorPass(array('required' => false)),
      'permanent_address' => new sfValidatorPass(array('required' => false)),
      'contact_address'   => new sfValidatorPass(array('required' => false)),
      'telephone1'        => new sfValidatorPass(array('required' => false)),
      'telephone2'        => new sfValidatorPass(array('required' => false)),
      'mobile'            => new sfValidatorPass(array('required' => false)),
      'email'             => new sfValidatorPass(array('required' => false)),
      'nic_pp_no'         => new sfValidatorPass(array('required' => false)),
      'date_of_birth'     => new sfValidatorDateRange(array('required' => false, 'from_date' => new sfValidatorDate(array('required' => false)), 'to_date' => new sfValidatorDateTime(array('required' => false)))),
      'sex'               => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'marital_status'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'nationality'       => new sfValidatorPass(array('required' => false)),
      'education'         => new sfValidatorPass(array('required' => false)),
      'occupation'        => new sfValidatorPass(array('required' => false)),
      'category'          => new sfValidatorPass(array('required' => false)),
      'deleted'           => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('patient_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Patient';
  }

  public function getFields()
  {
    return array(
      'patient_no'        => 'Text',
      'registered_date'   => 'Date',
      'first_name'        => 'Text',
      'last_name'         => 'Text',
      'current_address'   => 'Text',
      'permanent_address' => 'Text',
      'contact_address'   => 'Text',
      'telephone1'        => 'Text',
      'telephone2'        => 'Text',
      'mobile'            => 'Text',
      'email'             => 'Text',
      'nic_pp_no'         => 'Text',
      'date_of_birth'     => 'Date',
      'sex'               => 'Number',
      'marital_status'    => 'Number',
      'nationality'       => 'Text',
      'education'         => 'Text',
      'occupation'        => 'Text',
      'category'          => 'Text',
      'deleted'           => 'Number',
    );
  }
}
