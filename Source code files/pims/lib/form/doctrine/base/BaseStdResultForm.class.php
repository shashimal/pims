<?php

/**
 * StdResult form base class.
 *
 * @method StdResult getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseStdResultForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'patient_no'          => new sfWidgetFormInputHidden(),
      'episode_no'          => new sfWidgetFormInputHidden(),
      'input_code'          => new sfWidgetFormInputHidden(),
      'result_code'         => new sfWidgetFormInputHidden(),
      'input_category_code' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'patient_no'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'patient_no', 'required' => false)),
      'episode_no'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'episode_no', 'required' => false)),
      'input_code'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'input_code', 'required' => false)),
      'result_code'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'result_code', 'required' => false)),
      'input_category_code' => new sfValidatorString(array('max_length' => 10)),
    ));

    $this->widgetSchema->setNameFormat('std_result[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StdResult';
  }

}
