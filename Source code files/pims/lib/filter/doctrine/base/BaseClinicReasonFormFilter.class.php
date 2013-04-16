<?php

/**
 * ClinicReason filter form base class.
 *
 * @package    pims
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseClinicReasonFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'gp'         => new sfWidgetFormFilterInput(),
      'court'      => new sfWidgetFormFilterInput(),
      'bb'         => new sfWidgetFormFilterInput(),
      'contact'    => new sfWidgetFormFilterInput(),
      'cf'         => new sfWidgetFormFilterInput(),
      'volantary'  => new sfWidgetFormFilterInput(),
      'opd'        => new sfWidgetFormFilterInput(),
      'ward'       => new sfWidgetFormFilterInput(),
      'other'      => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'gp'         => new sfValidatorPass(array('required' => false)),
      'court'      => new sfValidatorPass(array('required' => false)),
      'bb'         => new sfValidatorPass(array('required' => false)),
      'contact'    => new sfValidatorPass(array('required' => false)),
      'cf'         => new sfValidatorPass(array('required' => false)),
      'volantary'  => new sfValidatorPass(array('required' => false)),
      'opd'        => new sfValidatorPass(array('required' => false)),
      'ward'       => new sfValidatorPass(array('required' => false)),
      'other'      => new sfValidatorPass(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('clinic_reason_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ClinicReason';
  }

  public function getFields()
  {
    return array(
      'patient_no' => 'Text',
      'episode_no' => 'Number',
      'gp'         => 'Text',
      'court'      => 'Text',
      'bb'         => 'Text',
      'contact'    => 'Text',
      'cf'         => 'Text',
      'volantary'  => 'Text',
      'opd'        => 'Text',
      'ward'       => 'Text',
      'other'      => 'Text',
    );
  }
}
