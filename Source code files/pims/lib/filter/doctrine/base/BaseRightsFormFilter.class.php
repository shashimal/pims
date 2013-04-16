<?php

/**
 * Rights filter form base class.
 *
 * @package    pims
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseRightsFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'adding'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'editing'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'deleting'      => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'viewing'       => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'adding'        => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'editing'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'deleting'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'viewing'       => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('rights_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Rights';
  }

  public function getFields()
  {
    return array(
      'user_group_id' => 'Text',
      'module_id'     => 'Text',
      'adding'        => 'Number',
      'editing'       => 'Number',
      'deleting'      => 'Number',
      'viewing'       => 'Number',
    );
  }
}
