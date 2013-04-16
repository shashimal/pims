<?php

/**
 * StdInput form base class.
 *
 * @method StdInput getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseStdInputForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'input_code'          => new sfWidgetFormInputHidden(),
      'input_name'          => new sfWidgetFormInputText(),
      'input_description'   => new sfWidgetFormInputText(),
      'no_of_input'         => new sfWidgetFormInputText(),
      'sex'                 => new sfWidgetFormInputText(),
      'input_category_code' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StdInputCategory'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'input_code'          => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'input_code', 'required' => false)),
      'input_name'          => new sfValidatorString(array('max_length' => 60)),
      'input_description'   => new sfValidatorString(array('max_length' => 60)),
      'no_of_input'         => new sfValidatorInteger(),
      'sex'                 => new sfValidatorInteger(),
      'input_category_code' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('StdInputCategory'))),
    ));

    $this->widgetSchema->setNameFormat('std_input[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StdInput';
  }

}
