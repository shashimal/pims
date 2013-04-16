<?php
/**
 * Class ClinicReason
 * All the functions of clinic reason are hadled by this class
 * @package    pims
 * @subpackage model
 * @author     Shashimal Warakagoda
 */
class ClinicReason extends BaseClinicReason {

    /**
     * Get an object of reason
     * @return $reason object
     */
    public function getReasonObject() {

        try {
            
            $reason = Doctrine::getTable('ClinicReason')
                    ->findByPatientNo($this->getPatientNo());
            
            return $reason[0];
        
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage());
        }
    }
}
