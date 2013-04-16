<?php

/**
 * PatientNoTracker filter form base class.
 *
 * @package    pims
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BasePatientNoTrackerFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'male'   => new sfWidgetFormFilterInput(array('with_empty' => false)),
      'female' => new sfWidgetFormFilterInput(array('with_empty' => false)),
    ));

    $this->setValidators(array(
      'male'   => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'female' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('patient_no_tracker_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'PatientNoTracker';
  }

  public function getFields()
  {
    return array(
      'year'   => 'Number',
      'male'   => 'Number',
      'female' => 'Number',
    );
  }
}
