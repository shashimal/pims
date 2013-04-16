<?php

/**
 * Visit form base class.
 *
 * @method Visit getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseVisitForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'patient_no'      => new sfWidgetFormInputHidden(),
      'episode_no'      => new sfWidgetFormInputHidden(),
      'visit_no'        => new sfWidgetFormInputHidden(),
      'appointed_date'  => new sfWidgetFormDate(),
      'visited_date'    => new sfWidgetFormDate(),
      'next_visit_date' => new sfWidgetFormDate(),
    ));

    $this->setValidators(array(
      'patient_no'      => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'patient_no', 'required' => false)),
      'episode_no'      => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'episode_no', 'required' => false)),
      'visit_no'        => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'visit_no', 'required' => false)),
      'appointed_date'  => new sfValidatorDate(),
      'visited_date'    => new sfValidatorDate(),
      'next_visit_date' => new sfValidatorDate(),
    ));

    $this->widgetSchema->setNameFormat('visit[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Visit';
  }

}
