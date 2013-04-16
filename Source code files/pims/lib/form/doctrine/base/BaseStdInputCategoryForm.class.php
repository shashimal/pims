<?php

/**
 * StdInputCategory form base class.
 *
 * @method StdInputCategory getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseStdInputCategoryForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'input_category_code' => new sfWidgetFormInputHidden(),
      'name'                => new sfWidgetFormInputText(),
      'description'         => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'input_category_code' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'input_category_code', 'required' => false)),
      'name'                => new sfValidatorString(array('max_length' => 50)),
      'description'         => new sfValidatorString(array('max_length' => 50)),
    ));

    $this->widgetSchema->setNameFormat('std_input_category[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StdInputCategory';
  }

}
