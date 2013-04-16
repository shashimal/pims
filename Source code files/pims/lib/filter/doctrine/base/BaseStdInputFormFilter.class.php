<?php

/**
 * StdInput filter form base class.
 *
 * @package    pims
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseStdInputFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'input_name'          => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'input_description'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'no_of_input'         => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'sex'                 => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'input_category_code' => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StdInputCategory'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'input_name'          => new sfValidatorPass(array('required' => false)),
      'input_description'   => new sfValidatorPass(array('required' => false)),
      'no_of_input'         => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'sex'                 => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'input_category_code' => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('StdInputCategory'), 'column' => 'input_category_code')),
    ));

    $this->widgetSchema->setNameFormat('std_input_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StdInput';
  }

  public function getFields()
  {
    return array(
      'input_code'          => 'Text',
      'input_name'          => 'Text',
      'input_description'   => 'Text',
      'no_of_input'         => 'Number',
      'sex'                 => 'Number',
      'input_category_code' => 'ForeignKey',
    );
  }
}
