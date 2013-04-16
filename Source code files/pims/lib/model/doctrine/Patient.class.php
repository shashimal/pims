<?php
/**
 * Class Patient
 * All the functions of patients are hadled by this class
 * @package    pims
 * @subpackage model
 * @author     Shashimal Warakagoda
 */
class Patient extends BasePatient {

    /**
     * Get the patients objects from the Patient table for displaying in the list
     * @return $patients array of objects of registerd patients
     */
    public function showPatientsList() {

        try {
            
            $q = Doctrine_Query::create()
                ->from('Patient p')
                ->orderBy('p.registered_date DESC');
            
            $patients = $q->execute();
            
            return $patients;
        
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage());
        
        }
    
    }

    /**
     * Search a patient
     * @param $patientNo
     * @return object of $patient
     */
    public function searchPatient($patientNo) {

        try {
            
            $q = Doctrine_Query::create()
                ->from('Patient')
                ->where("patient_no = ?", $patientNo);
            
            $patient = $q->execute();
            
            return $patient;
        
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage());
        
        }
    }

    /**
     * Find an object of patient
     * @return object of $patient
     */
    public function getPatientObject() {

        try {
            
            $patient = Doctrine::getTable('Patient')
                     ->find($this->getPatientNo());
            
            return $patient;
        
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage());
        }
    }
    
  	/**
     * Delete patient
     * @param $patienstList
     * @return unknown_type
     */
    public function deletePatient($patientsList) {

        try {

            if (is_array($patientsList)) {

                $q = Doctrine_Query::create()
                        ->delete('Patient')
                        ->whereIn('patient_no', $patientsList );

                $numDeleted = $q->execute();
            }

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }
    
    /**
     * Search the attendacne details of patients
     * @param $date, $occupation, $newPatient, $gender
     * @return $attendanceDetails array of attendance details
     */
    
    public function searchAttendacneDetailsOfPatients($date, $occupation = null, $newPatient = null,$gender ) {
     
     try {        
         
            $q = Doctrine_Query::create()
                 ->from('Patient p') ;
                 
            if($newPatient == true) {
                
               $q->where('p.registered_date = ?', $date) ;
                
            }else {
                
               $q->leftJoin('p.Visit v ON p.patient_no = v.patient_no')
                 ->where('v.visited_date = ?', $date)
                 ->orWhere('p.registered_date = ?', $date) ;
            }
            
            if($occupation != null) {
                
               $q->andWhere('p.occupation = ?', $occupation);
            }
            
            $q->andWhere('p.sex = ?', $gender);         
                                    
            $attendanceDetails = $q->fetchArray();
                       
            return $attendanceDetails;

        }catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }    

    
}
