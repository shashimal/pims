<?php

/**
 * UserGroup form base class.
 *
 * @method UserGroup getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserGroupForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_group_id'   => new sfWidgetFormInputHidden(),
      'user_group_name' => new sfWidgetFormInputText(),
      'description'     => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'user_group_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'user_group_id', 'required' => false)),
      'user_group_name' => new sfValidatorString(array('max_length' => 30)),
      'description'     => new sfValidatorString(array('max_length' => 60)),
    ));

    $this->widgetSchema->setNameFormat('user_group[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UserGroup';
  }

}
