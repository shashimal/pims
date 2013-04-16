<?php
/**
 * Class Episode
 * All the functions of episode are hadled by this class
 * @package    pims
 * @subpackage model
 * @author     Shashimal Warakagoda
 */
class Episode extends BaseEpisode {
    
    //Statuses of an episode
    const EPISODE_STATUS_OPEN = 0;
    const EPISODE_STATUS_COMPLETED = 1;
    const EPISODE_STATUS_REFERRED = 2;
    const EPISODE_STATUS_DEFAULTED = 3;
    const EPISODE_STATUS_CONTINUED = 4;
    const EPISODE_STATUS_CANCELED = 5;

    /**
     * Get the curret episode No
     * @param $patientNo     
     * @return episode no
     */
    public function getCurrentEpisodeNoOfPatient($patientNo) {

        try {
            
            $q = Doctrine_Query::create()
                ->select('*')
                ->from('Episode e')
                ->where('e.patient_no = ?', $patientNo)
                ->andWhere('e.status != ?', self::EPISODE_STATUS_CANCELED);
            
            $episode = $q->fetchArray();
            
            if (count($episode) >0) {                
                return count($episode) +1;
            
            } else {
                return 1;
            }
            
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage());
        }
    
    }
    
     /**
     * Get the curret episode object     
     * @return $episode object
     */
    public function getCurrentEpisodeObject() {

        try {
            
            $episode = Doctrine::getTable('Episode')
                     ->find(array($this->getEpisodeNo(), $this->getPatientNo()));
            
            return $episode;
        
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage());
        }
    }

     /**
     * Get the all the episodes except the cancled ones of a patient.   
     * @param $patientNo 
     * @return array of $episodes
     */
    public function getNotCanceledEpisodes($patientNo) {

        try {
            
            $q = Doctrine_Query::create()
                ->select('*')
                ->from('Episode e')
                ->where('e.patient_no = ?', $patientNo)->andWhere('e.status != ?', self::EPISODE_STATUS_CANCELED);
           
            $episodes = $q->fetchArray();
                        
            return $episodes;
        
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage());
        }
    }
    
	/**
     * Search the attendacne details of patients of new episodes
     * @param $date ,$occupation, $gender
     * @return $attendanceDetails array of attendance details
     */
    
    public function searchAttendacneDetailsOfNewEpisodes($date ,$occupation, $gender) {

        try {        
         
            $q = Doctrine_Query::create()
                 ->from('Patient p') ;                 
                         
            $q->leftJoin('p.Episode e ON p.patient_no = e.patient_no')
                 ->where('e.start_date = ?', $date);            
            
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
    
    /**
     * Get the last episode no of the patient     
     * @return $episodeNo
     */
    public function getLastEpisodeNoOfPatient($patientNo) {
        
    try {
            
            $q = Doctrine_Query::create()
                ->select('*')
                ->from('Episode e')
                ->where('e.patient_no = ?', $patientNo);               
            
            $episode = $q->fetchArray();
            
            return $lastEpisodeNo = count($episode);
            
            
        } catch (Exception $e) {
            
            throw new Exception($e->getMessage());
        }
        
    }
    
}
