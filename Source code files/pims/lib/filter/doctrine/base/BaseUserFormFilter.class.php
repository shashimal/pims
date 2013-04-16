<?php

/**
 * User filter form base class.
 *
 * @package    pims
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUserFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'user_name'     => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_password' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'is_admin'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'status'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'user_group_id' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('UserGroup'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'user_name'     => new sfValidatorPass(array('required' => false)),
      'user_password' => new sfValidatorPass(array('required' => false)),
      'is_admin'      => new sfValidatorPass(array('required' => false)),
      'status'        => new sfValidatorPass(array('required' => false)),
      'user_group_id' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('UserGroup'), 'column' => 'user_group_id')),
    ));

    $this->widgetSchema->setNameFormat('user_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'User';
  }

  public function getFields()
  {
    return array(
      'user_id'       => 'Text',
      'user_name'     => 'Text',
      'user_password' => 'Text',
      'is_admin'      => 'Text',
      'status'        => 'Text',
      'user_group_id' => 'ForeignKey',
    );
  }
}
