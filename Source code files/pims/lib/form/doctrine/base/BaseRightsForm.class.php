<?php

/**
 * Rights form base class.
 *
 * @method Rights getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseRightsForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_group_id' => new sfWidgetFormInputHidden(),
      'module_id'     => new sfWidgetFormInputHidden(),
      'adding'        => new sfWidgetFormInputText(),
      'editing'       => new sfWidgetFormInputText(),
      'deleting'      => new sfWidgetFormInputText(),
      'viewing'       => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'user_group_id' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'user_group_id', 'required' => false)),
      'module_id'     => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'module_id', 'required' => false)),
      'adding'        => new sfValidatorInteger(),
      'editing'       => new sfValidatorInteger(),
      'deleting'      => new sfValidatorInteger(),
      'viewing'       => new sfValidatorInteger(),
    ));

    $this->widgetSchema->setNameFormat('rights[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Rights';
  }

}
