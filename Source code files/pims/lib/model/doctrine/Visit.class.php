<?php
/**
 * Class Visit
 * All the functions of clinic visits are hadled by this class
 * @package    pims
 * @subpackage model
 * @author     Shashimal Warakagoda
 */
class Visit extends BaseVisit {

     /**
     * Get the visits of the current episode
     * @return $visits array of current visits
     */
    public function getCurrentEpisodeVisitsOfPatient() {

       try {           
               $q = Doctrine_Query::create()
                    ->from('Visit v')
                    ->where('v.patient_no = ?', $this->getPatientNo())
                    ->andWhere('v.episode_no  = ?', $this->getEpisodeNo());

        $visits = $q->fetchArray();
      
        return $visits;

       }catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Get the current visit object
     * @return $currentVisit object
     */
    public function getCurrentVisitObject() {

        try {

            $currentVisit = Doctrine::getTable('Visit')
                            ->find(array($this->getPatientNo(), $this->getEpisodeNo(), $this->getVisitNo()));

            return $currentVisit;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

     /**
     * View the appointment details of patients
     * @return $appointments of array
     */
    public function viewAppoinmentDeatils($date, $gender) {
        
        try {

            $q = Doctrine_Query::create()
                    ->from('Patient e')
                    ->leftJoin('e.Visit v ON e.patient_no = v.patient_no')
                    ->where('v.appointed_date = ?', $date)
                    ->andWhere('e.sex 	 = ?',$gender)
                    ->andWhere('v.visited_date = ?','0000-00-00')
                    ->andWhere('v.next_visit_date = ?','0000-00-00');

            $appointments = $q->fetchArray();

            return $appointments;

        }catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Get all the visit data of a patient for an episode
     * @return $visits of array
     */
    public static function getAllVisitsOfEpisode($patientNo, $episodeNo) {

        try {

           $q = Doctrine_Query::create()
                ->from('Visit e')
                ->where('e.patient_no = ?', $patientNo)
                ->andWhere('e.episode_no = ?', $episodeNo);
               

        $visits = $q->fetchArray();

        return $visits;

        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }
    
     /**
     * Trace default patients
     * @return $visits of array
     */
    public function traceDefaultPatients($fromDate, $toDate, $noOfDays = null) {
        
        try {
            
            $q = Doctrine_Query::create()
                 ->from('Visit v')
                 ->where("v.appointed_date BETWEEN '$fromDate' AND '$toDate' ")
                 ->andWhere('v.visited_date = ?','0000-00-00');               
                                 
                 $defaultPatients = $q->execute();
                 
                 return $defaultPatients;
            
        }catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
}
