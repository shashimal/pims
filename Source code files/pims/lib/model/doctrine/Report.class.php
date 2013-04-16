<?php
/*
 * Class Report
 * Generate reports of the PIMS
 * @package    pims
 * @subpackage model
 * @author     Shashimal Warakagoda
 */
class Report extends BaseReport {

    private $startDate;
    private $endDate;

    public function getStartDate() {

        return $this->startDate;
    }

    public function getEndDate() {

        return $this->endDate;
    }

    public function setStartDate($startDate) {

        $this->startDate = $startDate;
    }

    public function setEndDate($endDate) {

        $this->endDate = $endDate;
    }

    /**
     * Generate marital status report
     * @param $year,$quarter
     */
    public function getPatientReport($year, $quarter) {

        try {

            $this->_setStartAndEndDateOfQuarter($year, $quarter);

            $q = Doctrine_Query::create()
                            ->from('Patient')
                            ->where('registered_date >= :start', array(':start' => $this->startDate))
                            ->andWhere('registered_date <= :end', array(':end' => $this->endDate));

            $reportData = $q->fetchArray();

            return $reportData;
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Generate reason for clinic attendance report
     * @param $year,$quarter
     */
    public function getReasonForAttendanceReport($year, $quarter) {

        try {

            $this->_setStartAndEndDateOfQuarter($year, $quarter);

            $q = Doctrine_Query::create()
                            ->from('Patient p')
                            ->leftJoin('p.ClinicReason r ON p.patient_no = r.patient_no')
                            ->where('registered_date >= :start', array(':start' => $this->startDate))
                            ->andWhere('registered_date <= :end', array(':end' => $this->endDate));

            $reportData = $q->fetchArray();

            return $reportData;
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Generate new STD episode report
     * @param $year,$quarter
     */
    public function getNewStdEpisodeReport($year, $quarter) {

        try {

            $this->_setStartAndEndDateOfQuarter($year, $quarter);
            $inputCode = "INC048";

            $q = Doctrine_Query::create()
                            ->from('Patient e')
                            ->leftJoin('e.StdResult v ON e.patient_no = v.patient_no')
                            ->where("e.registered_date Between '{$this->startDate}' AND '{$this->endDate}' ")
                            ->andWhere('v.input_code = ?', $inputCode);

            $reportData = $q->fetchArray();

            return $reportData;
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Generate new STD episode of commercial sex workers report
     * @param $year,$quarter
     */
    public function getNewStdEpisodeOfCswReport($year, $quarter) {

        try {

            $this->_setStartAndEndDateOfQuarter($year, $quarter);
            $inputCode = "INC048";

            $q = Doctrine_Query::create()
                            ->from('Patient e')
                            ->leftJoin('e.StdResult v ON e.patient_no = v.patient_no')
                            ->where("e.registered_date Between '{$this->startDate}' AND '{$this->endDate}' ")
                            ->andWhere('e.occupation = ?', "CSW")
                            ->andWhere('v.input_code = ?', $inputCode);

            $q->getSqlQuery();

            $reportData = $q->fetchArray();

            return $reportData;
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Generate occupational categories of STD patients report
     * @param $year
     */
    public function createOccupationReport($year) {

        try {

            $fromYear = $year . "-01-01";
            $toYear = $year . "-12-31";

            $q = Doctrine_Query::create()
                            ->from('Patient e')
                            ->where("e.registered_date Between '{$fromYear}' AND '{$toYear}' ")
                            ->orderBy('e.registered_date');

            $reportData = $q->fetchArray();

            return $reportData;
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

    /**
     * Generate syphilis distribution of STD patients report
     * @param $year
     */
    public function createSyphilisDistributionReport($year) {

        $fromYear = $year . "-01-01";
        $toYear = $year . "-12-31";
        $inputCode = "INC048";

        $q = Doctrine_Query::create()
                        ->from('Patient e')
                        ->leftJoin('e.StdResult v ON e.patient_no = v.patient_no')
                        ->where("e.registered_date Between '{$fromYear}' AND '{$toYear}' ")
                        ->andWhere('v.input_code = ?', $inputCode);


        $reportData = $q->fetchArray();

        return $reportData;
    }

    /**
     * Generate reason for attendance report
     * @param $year
     */
    public function createReasonForAttendanceReport($year) {

        try {
            $fromYear = $year . "-01-01";
            $toYear = $year . "-12-31";

            $q = Doctrine_Query::create()
                            ->from('Patient e')
                            ->leftJoin('e.ClinicReason v ON e.patient_no = v.patient_no')
                            ->where("e.registered_date Between '{$fromYear}' AND '{$toYear}' ");

            $reportData = $q->fetchArray();

            return $reportData;
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

     /**
     * Generate CSW report
     * @param $year
     */
    public function getCswReportData($year) {

        try {
            $fromYear = $year . "-01-01";
            $toYear = $year . "-12-31";

            $q = Doctrine_Query::create()
                            ->from('Patient e')                            
                            ->where("e.registered_date Between '{$fromYear}' AND '{$toYear}' ");

            $reportData = $q->fetchArray();

            return $reportData;
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }
    
    
     /**
     * Generate no of visits for the quarter report
     * @param $year
     */
    public function getNoOfClinicVisitReport($year, $quarter) {
    try {
           $this->_setStartAndEndDateOfQuarter($year, $quarter);
             
            $q = Doctrine_Query::create()
                 ->from('Patient e')
                 ->leftJoin('e.Visit v ON e.patient_no = v.patient_no')
                 ->where('v.visited_date >= :start', array(':start' => $this->startDate))
                 ->andWhere('v.visited_date <= :end', array(':end' => $this->endDate));              
                                 
                 $visits = $q->fetchArray();
                 
                 return $visits;
            
        }catch(Exception $e) {
            throw new Exception($e->getMessage());
        }
    }
    
 	/**
     * Generate STD positive report
     * @param $year, $quarter
     */
    public function getStdPositiveReport($year, $quarter) {
        try {
               $this->_setStartAndEndDateOfQuarter($year, $quarter);
                 
                $q = Doctrine_Query::create()
                     ->from('Patient e')
                     ->leftJoin('e.Episode v ON e.patient_no = v.patient_no')
                     ->where("e.registered_date Between '{$this->startDate}' AND '{$this->endDate}' ")                 
                     ->andWhere('v.std_positive = ?','Yes')
                     ->andWhere("v.status != '5'");      
                                     
                     $std = $q->fetchArray();
                     
                     return $std;
                
         }catch(Exception $e) {
                throw new Exception($e->getMessage());
         }
    }     
    
    /**
     * Generate HIV positive report
     * @param $year, $quarter
     */
    public function createHivPositiveReport($year, $quarter) {
        
    try {
               $this->_setStartAndEndDateOfQuarter($year, $quarter);
                 
                $q = Doctrine_Query::create()
                     ->from('Patient e')
                     ->leftJoin('e.Episode v ON e.patient_no = v.patient_no')
                     ->where("e.registered_date Between '{$this->startDate}' AND '{$this->endDate}' ")                 
                     ->andWhere('v.hiv_positive = ?','Yes');
                         
                                     
                     $hiv = $q->fetchArray();
                     
                     return $hiv;
                
         }catch(Exception $e) {
                throw new Exception($e->getMessage());
         }
    }
    
     /**
     * Create HIV distribution report
     * @param $from, $to
     */
    public function getHivDistributionReportData($from, $to) {

        try {
            $fromYear = $from . "-01-01";
            $toYear = $to . "-12-31";

            $q = Doctrine_Query::create()
                            ->from('Patient e')                            
                            ->where("e.registered_date Between '{$fromYear}' AND '{$toYear}' ");

            $reportData = $q->fetchArray();

            return $reportData;
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }
   
 /**
     * Generate STD episode report of foreigners
     * @param $year, $quarter
     */
    public function createStdEpisodeReportOfForeigners($year, $quarter) {
        
    try {
               $this->_setStartAndEndDateOfQuarter($year, $quarter);
                 
                $q = Doctrine_Query::create()
                     ->from('Patient e')
                     ->leftJoin('e.Episode v ON e.patient_no = v.patient_no')
                     ->where("e.registered_date Between '{$this->startDate}' AND '{$this->endDate}' ")                 
                     ->andWhere('v.hiv_positive = ?','Yes')
                     ->andWhere("e.nationality != 'Srilankan'");                        
                                     
                     $data = $q->fetchArray();
                     
                     return $data;
                
         }catch(Exception $e) {
                throw new Exception($e->getMessage());
         }
    }
    
    private function _setStartAndEndDateOfQuarter($year, $quarter) {

        switch ($quarter) {
            case 1:
                $this->startDate = $year . "-01-01";
                $this->endDate = $year . "-03-31";
                break;

            case 2:
                $this->startDate = $year . "-04-01";
                $this->endDate = $year . "-06-30";
                break;

            case 3:
                $this->startDate = $year . "-07-01";
                $this->endDate = $year . "-09-30";
                break;

            case 4:
                $this->startDate = $year . "-10-01";
                $this->endDate = $year . "-12-31";
                break;

            default:
                break;
        }
    }

    public function getResultCountOfInput() {

        try {

            $inputCode = "INC048";

            $q = Doctrine_Query::create()
                            ->from('StdInputResult r')
                            ->andWhere('r.input_code = ?', $inputCode);


            $result = $q->fetchArray();

            return  $result;

           
        } catch (Exception $e) {

            throw new Exception($e->getMessage());
        }
    }

}
