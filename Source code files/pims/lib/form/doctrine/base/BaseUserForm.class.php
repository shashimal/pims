<?php

/**
 * User form base class.
 *
 * @method User getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_id'       => new sfWidgetFormInputHidden(),
      'user_name'     => new sfWidgetFormInputText(),
      'user_password' => new sfWidgetFormInputText(),
      'is_admin'      => new sfWidgetFormInputText(),
      'status'        => new sfWidgetFormInputText(),
      'user_group_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserGroup'), 'add_empty' => false)),
    ));

    $this->setValidators(array(
      'user_id'       => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'user_id', 'required' => false)),
      'user_name'     => new sfValidatorString(array('max_length' => 45)),
      'user_password' => new sfValidatorString(array('max_length' => 45)),
      'is_admin'      => new sfValidatorString(array('max_length' => 3)),
      'status'        => new sfValidatorString(array('max_length' => 20)),
      'user_group_id' => new sfValidatorDoctrineChoice(array('model' => $this->getRelatedModelName('UserGroup'))),
    ));

    $this->widgetSchema->setNameFormat('user[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }

}
