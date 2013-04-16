<?php

/**
 * ContactMode filter form base class.
 *
 * @package    pims
 * @subpackage filter
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormFilterGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseContactModeFormFilter extends BaseFormFilterDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'email'      => new sfWidgetFormFilterInput(),
      'letter'     => new sfWidgetFormFilterInput(),
      'telephone'  => new sfWidgetFormFilterInput(),
      'visit'      => new sfWidgetFormFilterInput(),
      'no_contact' => new sfWidgetFormFilterInput(),
    ));

    $this->setValidators(array(
      'email'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'letter'     => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'telephone'  => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'visit'      => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
      'no_contact' => new sfValidatorSchemaFilter('text', new sfValidatorInteger(array('required' => false))),
    ));

    $this->widgetSchema->setNameFormat('contact_mode_filters[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'ContactMode';
  }

  public function getFields()
  {
    return array(
      'id'         => 'Number',
      'patient_no' => 'Text',
      'email'      => 'Number',
      'letter'     => 'Number',
      'telephone'  => 'Number',
      'visit'      => 'Number',
      'no_contact' => 'Number',
    );
  }
}
