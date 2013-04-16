<?php

/**
 * Patient form base class.
 *
 * @method Patient getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePatientForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'patient_no'        => new sfWidgetFormInputHidden(),
      'registered_date'   => new sfWidgetFormDate(),
      'first_name'        => new sfWidgetFormInputText(),
      'last_name'         => new sfWidgetFormInputText(),
      'current_address'   => new sfWidgetFormInputText(),
      'permanent_address' => new sfWidgetFormInputText(),
      'contact_address'   => new sfWidgetFormInputText(),
      'telephone1'        => new sfWidgetFormInputText(),
      'telephone2'        => new sfWidgetFormInputText(),
      'mobile'            => new sfWidgetFormInputText(),
      'email'             => new sfWidgetFormInputText(),
      'nic_pp_no'         => new sfWidgetFormInputText(),
      'date_of_birth'     => new sfWidgetFormDate(),
      'sex'               => new sfWidgetFormInputText(),
      'marital_status'    => new sfWidgetFormInputText(),
      'nationality'       => new sfWidgetFormInputText(),
      'education'         => new sfWidgetFormInputText(),
      'occupation'        => new sfWidgetFormInputText(),
      'category'          => new sfWidgetFormInputText(),
      'deleted'           => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'patient_no'        => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'patient_no', 'required' => false)),
      'registered_date'   => new sfValidatorDate(),
      'first_name'        => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'last_name'         => new sfValidatorString(array('max_length' => 30)),
      'current_address'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'permanent_address' => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'contact_address'   => new sfValidatorString(array('max_length' => 50, 'required' => false)),
      'telephone1'        => new sfValidatorString(array('max_length' => 20)),
      'telephone2'        => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'mobile'            => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'email'             => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'nic_pp_no'         => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'date_of_birth'     => new sfValidatorDate(array('required' => false)),
      'sex'               => new sfValidatorInteger(array('required' => false)),
      'marital_status'    => new sfValidatorInteger(array('required' => false)),
      'nationality'       => new sfValidatorString(array('max_length' => 30, 'required' => false)),
      'education'         => new sfValidatorString(array('max_length' => 45, 'required' => false)),
      'occupation'        => new sfValidatorString(array('max_length' => 30)),
      'category'          => new sfValidatorString(array('max_length' => 20)),
      'deleted'           => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('patient[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Patient';
  }

}
