<?php

/**
 * UniqueIdGenerator filter form base class.
 *
 * @package    pims
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUniqueIdGeneratorFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'last_id'    => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tbl'        => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'tbl_column' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'last_id'    => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'tbl'        => new sfValidatorPass(array('required' => false)),
      'tbl_column' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('unique_id_generator_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UniqueIdGenerator';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'last_id'    => 'Number',
      'tbl'        => 'Text',
      'tbl_column' => 'Text',
    );
  }
}
