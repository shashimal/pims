<?php

/**
 * SysModule form base class.
 *
 * @method SysModule getObject() Returns the current form's model object
 *
 * @package    pims
 * @subpackage form
 * @author     Your name here
 * @version    SVN: $Id: sfDoctrineFormGeneratedTemplate.php 24171 2009-11-19 16:37:50Z Kris.Wallsmith $
 */
abstract class BaseSysModuleForm extends BaseFormDoctrine
{
  public function setup()
  {
    $this->setWidgets(array(
      'module_id'   => new sfWidgetFormInputHidden(),
      'name'        => new sfWidgetFormInputText(),
      'description' => new sfWidgetFormInputText(),
    ));

    $this->setValidators(array(
      'module_id'   => new sfValidatorDoctrineChoice(array('model' => $this->getModelName(), 'column' => 'module_id', 'required' => false)),
      'name'        => new sfValidatorString(array('max_length' => 20)),
      'description' => new sfValidatorString(array('max_length' => 20)),
    ));

    $this->widgetSchema->setNameFormat('sys_module[%s]');

    $this->errorSchema = new sfValidatorErrorSchema($this->validatorSchema);

    $this->setupInheritance();

    parent::setup();
  }

  public function getModelName()
  {
    return 'SysModule';
  }

}
