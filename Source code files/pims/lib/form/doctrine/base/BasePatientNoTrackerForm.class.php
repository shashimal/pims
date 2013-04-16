<?php

/**
 * PatientNoTracker form base class.
 *
 * @method PatientNoTracker getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePatientNoTrackerForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'year'   => new sfWidgetFormInputHidden(),
      'male'   => new sfWidgetFormInputText(),
      'female' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'year'   => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'year', 'required' => false)),
      'male'   => new sfValidatorInteger(),
      'female' => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('patient_no_tracker[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PatientNoTracker';
  }

}
