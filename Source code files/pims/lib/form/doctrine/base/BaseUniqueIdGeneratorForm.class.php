<?php

/**
 * UniqueIdGenerator form base class.
 *
 * @method UniqueIdGenerator getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseUniqueIdGeneratorForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'id'         => new sfWidgetFormInputHidden(),
      'last_id'    => new sfWidgetFormInputText(),
      'tbl'        => new sfWidgetFormInputText(),
      'tbl_column' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'id'         => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'id', 'required' => false)),
      'last_id'    => new sfValidatorInteger(),
      'tbl'        => new sfValidatorString(array('max_length' => 50)),
      'tbl_column' => new sfValidatorString(array('max_length' => 50)),
    ));

    $this->widgetSchema->setNameFormat('unique_id_generator[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'UniqueIdGenerator';
  }

}
