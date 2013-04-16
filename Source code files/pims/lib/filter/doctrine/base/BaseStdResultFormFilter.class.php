<?php

/**
 * StdResult filter form base class.
 *
 * @package    pims
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseStdResultFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'input_category_code' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'input_category_code' => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('std_result_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'StdResult';
  }

  public function getFields()
  {
    return array(
      'patient_no'          => 'Text',
      'episode_no'          => 'Number',
      'input_code'          => 'Text',
      'result_code'         => 'Number',
      'input_category_code' => 'Text',
    );
  }
}
