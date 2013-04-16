<?php

/**
 * StdInputResult form base class.
 *
 * @method StdInputResult getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseStdInputResultForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'input_result_code'  => new sfWidgetFormInputHidden(),
      'result_description' => new sfWidgetFormInputText(),
      'input_code'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StdInput'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'input_result_code'  => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'input_result_code', 'required' => false)),
      'result_description' => new sfValidatorString(array('max_length' => 20)),
      'input_code'         => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('StdInput'))),
    ));

    $this->widgetSchema->setNameFormat('std_input_result[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StdInputResult';
  }

}
