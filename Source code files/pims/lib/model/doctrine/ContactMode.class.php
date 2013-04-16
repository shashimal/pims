<?php
/**
 * Class ContactMode
 * All the functions of contact modes are hadled by this class
 * @package    pims
 * @subpackage model
 * @author     Shashimal Warakagoda
 */
class ContactMode extends BaseContactMode {

    /**
     * Get an object of patient's contact mode
     * @return $patientContactMode object
     */
    public function getContactObject() {

        try {
            
            $patientContactMode = Doctrine::getTable('ContactMode')
                                ->findByPatientNo($this->getPatientNo());
            
            return $patientContactMode[0];
        
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage());
        }
    }
}
