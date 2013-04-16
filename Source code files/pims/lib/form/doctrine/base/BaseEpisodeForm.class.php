<?php

/**
 * Episode form base class.
 *
 * @method Episode getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseEpisodeForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'episode_no' => new sfWidgetFormInputHidden(),
      'patient_no' => new sfWidgetFormInputHidden(),
      'status'     => new sfWidgetFormInputText(),
      'comment'    => new sfWidgetFormInputText(),
      'start_date' => new sfWidgetFormDate(),
      'end_date'   => new sfWidgetFormDate(),
    ));

    $this->setValidators(array(
      'episode_no' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'episode_no', 'required' => false)),
      'patient_no' => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'patient_no', 'required' => false)),
      'status'     => new sfValidatorInteger(array('required' => false)),
      'comment'    => new sfValidatorString(array('max_length' => 60)),
      'start_date' => new sfValidatorDate(array('required' => false)),
      'end_date'   => new sfValidatorDate(array('required' => false)),
    ));

    $this->widgetSchema->setNameFormat('episode[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'Episode';
  }

}
