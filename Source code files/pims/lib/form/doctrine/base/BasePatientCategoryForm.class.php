<?php

/**
 * PatientCategory form base class.
 *
 * @method PatientCategory getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePatientCategoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'category_id'      => new sfWidgetFormInputHidden(),
      'patient_category' => new sfWidgetFormInputText(),
      'description'      => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'category_id'      => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'category_id', 'required' => false)),
      'patient_category' => new sfValidatorString(array('max_length' => 30)),
      'description'      => new sfValidatorString(array('max_length' => 40)),
    ));

    $this->widgetSchema->setNameFormat('patient_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PatientCategory';
  }

}
