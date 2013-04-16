<?php

/**
 * StdInputResult filter form base class.
 *
 * @package    pims
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseStdInputResultFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'result_description' => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'input_code'         => new sfWidgetFormDoctrineChoice(array('model' => $this->getRelatedModelName('StdInput'), 'add_empty' => true)),
    ));

    $this->setValidators(array(
      'result_description' => new sfValidatorPass(array('required' => false)),
      'input_code'         => new sfValidatorDoctrineChoice(array('required' => false, 'model' => $this->getRelatedModelName('StdInput'), 'column' => 'input_code')),
    ));

    $this->widgetSchema->setNameFormat('std_input_result_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StdInputResult';
  }

  public function getFields()
  {
    return array(
      'input_result_code'  => 'Number',
      'result_description' => 'Text',
      'input_code'         => 'ForeignKey',
    );
  }
}
