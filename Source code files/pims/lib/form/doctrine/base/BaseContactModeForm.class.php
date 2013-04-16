<?php

/**
 * ContactMode form base class.
 *
 * @method ContactMode getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseContactModeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'patient_no' => new sfWidgetFormInputHidden(),
      'email'      => new sfWidgetFormInputText(),
      'letter'     => new sfWidgetFormInputText(),
      'telephone'  => new sfWidgetFormInputText(),
      'visit'      => new sfWidgetFormInputText(),
      'no_contact' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'patient_no' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'patient_no', 'required' => false)),
      'email'      => new sfValidatorInteger(array('required' => false)),
      'letter'     => new sfValidatorInteger(array('required' => false)),
      'telephone'  => new sfValidatorInteger(array('required' => false)),
      'visit'      => new sfValidatorInteger(array('required' => false)),
      'no_contact' => new sfValidatorInteger(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('contact_mode[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ContactMode';
  }

}
