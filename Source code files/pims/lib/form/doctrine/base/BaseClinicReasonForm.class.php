<?php

/**
 * ClinicReason form base class.
 *
 * @method ClinicReason getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseClinicReasonForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'patient_no' => new sfWidgetFormInputHidden(),
      'episode_no' => new sfWidgetFormInputHidden(),
      'gp'         => new sfWidgetFormInputText(),
      'court'      => new sfWidgetFormInputText(),
      'bb'         => new sfWidgetFormInputText(),
      'contact'    => new sfWidgetFormInputText(),
      'cf'         => new sfWidgetFormInputText(),
      'volantary'  => new sfWidgetFormInputText(),
      'opd'        => new sfWidgetFormInputText(),
      'ward'       => new sfWidgetFormInputText(),
      'other'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'patient_no' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'patient_no', 'required' => false)),
      'episode_no' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'episode_no', 'required' => false)),
      'gp'         => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'court'      => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'bb'         => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'contact'    => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'cf'         => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'volantary'  => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'opd'        => new sfValidatorString(array('max_length' => 40, 'required' => false)),
      'ward'       => new sfValidatorString(array('max_length' => 20, 'required' => false)),
      'other'      => new sfValidatorString(array('max_length' => 20, 'required' => false)),
    ));

    $this->widgetSchema->setNameFormat('clinic_reason[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClinicReason';
  }

}
