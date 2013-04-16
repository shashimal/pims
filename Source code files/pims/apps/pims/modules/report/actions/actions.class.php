<?php
/**
 * report actions.
 *
 * @package    pims
 * @subpackage report
 * @author     Your name here
 * @version    SVN: $Id: actions.class.php 23810 2009-11-12 11:07:44Z Kris.Wallsmith $
 */
class reportActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {

        $this->reports = Doctrine::getTable('Report')->createQuery('a')->execute();
    }

    /**
     * Create marital status report
     * @param $request
     */
    public function executeCreateMaritalStatusReport(sfWebRequest $request) {

    }

    /*
     * Display marital status report
     *
     */
    public function executeViewMaritalStatusReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        $reportData = $this->_getPatientReportData($request);
        
        //Sex 1 = Male
        //Sex 2 = Female
        $maritalStatus['single'] = array(0 => 0, 1 => 0, 2 => 0);
        $maritalStatus['married'] = array(0 => 0, 1 => 0, 2 => 0);
        $maritalStatus['sep'] = array(0 => 0, 1 => 0, 2 => 0);
        $maritalStatus['widowed'] = array(0 => 0, 1 => 0, 2 => 0);
        $maritalStatus['lt'] = array(0 => 0, 1 => 0, 2 => 0);
        $maritalStatus['non'] = array(0 => 0, 1 => 0, 2 => 0);
        
        foreach ($reportData as $record) {
            
            if ($record['sex'] =="1") {
                
                //Male patients
                switch ($record['marital_status']) {
                    case 2 :
                        $maritalStatus['single'][0] = $maritalStatus['single'][0] +1;
                        break;
                    
                    case 1 :
                        $maritalStatus['married'][0] = $maritalStatus['married'][0] +1;
                        break;
                    
                    case 3 :
                        $maritalStatus['sep'][0] = $maritalStatus['sep'][0] +1;
                        break;
                    
                    case 4 :
                        $maritalStatus['widowed'][0] = $maritalStatus['widowed'][0] +1;
                        break;
                    
                    case 5 :
                        $maritalStatus['lt'][0] = $maritalStatus['lt'][0] +1;
                        break;
                    case 6 :
                        $maritalStatus['non'][0] = $maritalStatus['non'][0] +1;
                        break;
                    
                    default :
                        break;
                }
            } else {
                
                //Female patients
                switch ($record['marital_status']) {
                    case 2 :
                        $maritalStatus['single'][1] = $maritalStatus['single'][1] +1;
                        break;
                    
                    case 1 :
                        $maritalStatus['married'][1] = $maritalStatus['married'][1] +1;
                        break;
                    
                    case 3 :
                        $maritalStatus['sep'][1] = $maritalStatus['sep'][1] +1;
                        break;
                    
                    case 4 :
                        $maritalStatus['widowed'][1] = $maritalStatus['widowed'][1] +1;
                        break;
                    
                    case 5 :
                        $maritalStatus['lt'][1] = $maritalStatus['lt'][1] +1;
                        break;
                    case 6 :
                        $maritalStatus['non'][1] = $maritalStatus['non'][1] +1;
                        break;
                    
                    default :
                        break;
                }
            }
        }
        
        $maritalStatus['single'][2] = $maritalStatus['single'][0] +$maritalStatus['single'][1];
        $maritalStatus['married'][2] = $maritalStatus['married'][0] +$maritalStatus['married'][1];
        $maritalStatus['sep'][2] = $maritalStatus['sep'][0] +$maritalStatus['sep'][1];
        $maritalStatus['widowed'][2] = $maritalStatus['widowed'][0] +$maritalStatus['widowed'][1];
        $maritalStatus['lt'][2] = $maritalStatus['lt'][0] +$maritalStatus['lt'][1];
        $maritalStatus['non'][2] = $maritalStatus['non'][0] +$maritalStatus['non'][1];
        
        $this->maritalStatuses = $maritalStatus;
        $this->totalMales = $maritalStatus['single'][0] +$maritalStatus['married'][0] +$maritalStatus['sep'][0] +$maritalStatus['widowed'][0] +$maritalStatus['lt'][0] +$maritalStatus['non'][0];
        $this->totalFemales = $maritalStatus['single'][1] +$maritalStatus['married'][1] +$maritalStatus['sep'][1] +$maritalStatus['widowed'][1] +$maritalStatus['lt'][1] +$maritalStatus['non'][1];
        $this->total = $this->totalMales +$this->totalFemales;
        $this->year = $year;
        $this->quarter = $quarter;
    }

    /**
     * Export appointment reports
     * @param r $request
     * @return unknown_type
     */
    public function executeExportMaritalStatusReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        $reportTye = $request->getParameter('rep');
        
        $reportData = $this->_getPatientReportData($request);
        
        //Sex 1 = Male
        //Sex 2 = Female
        $maritalStatus['single'] = array(0 => 0, 1 => 0, 2 => 0);
        $maritalStatus['married'] = array(0 => 0, 1 => 0, 2 => 0);
        $maritalStatus['sep'] = array(0 => 0, 1 => 0, 2 => 0);
        $maritalStatus['widowed'] = array(0 => 0, 1 => 0, 2 => 0);
        $maritalStatus['lt'] = array(0 => 0, 1 => 0, 2 => 0);
        $maritalStatus['non'] = array(0 => 0, 1 => 0, 2 => 0);
        
        foreach ($reportData as $record) {
            
            if ($record['sex'] =="1") {
                
                //Male patients
                switch ($record['marital_status']) {
                    case 2 :
                        $maritalStatus['single'][0] = $maritalStatus['single'][0] +1;
                        break;
                    
                    case 1 :
                        $maritalStatus['married'][0] = $maritalStatus['married'][0] +1;
                        break;
                    
                    case 3 :
                        $maritalStatus['sep'][0] = $maritalStatus['sep'][0] +1;
                        break;
                    
                    case 4 :
                        $maritalStatus['widowed'][0] = $maritalStatus['widowed'][0] +1;
                        break;
                    
                    case 5 :
                        $maritalStatus['lt'][0] = $maritalStatus['lt'][0] +1;
                        break;
                    case 6 :
                        $maritalStatus['non'][0] = $maritalStatus['non'][0] +1;
                        break;
                    
                    default :
                        break;
                }
            } else {
                
                //Female patients
                switch ($record['marital_status']) {
                    case 2 :
                        $maritalStatus['single'][1] = $maritalStatus['single'][1] +1;
                        break;
                    
                    case 1 :
                        $maritalStatus['married'][1] = $maritalStatus['married'][1] +1;
                        break;
                    
                    case 3 :
                        $maritalStatus['sep'][1] = $maritalStatus['sep'][1] +1;
                        break;
                    
                    case 4 :
                        $maritalStatus['widowed'][1] = $maritalStatus['widowed'][1] +1;
                        break;
                    
                    case 5 :
                        $maritalStatus['lt'][1] = $maritalStatus['lt'][1] +1;
                        break;
                    case 6 :
                        $maritalStatus['non'][1] = $maritalStatus['non'][1] +1;
                        break;
                    
                    default :
                        break;
                }
            }
        }
        
        $maritalStatus['single'][2] = $maritalStatus['single'][0] +$maritalStatus['single'][1];
        $maritalStatus['married'][2] = $maritalStatus['married'][0] +$maritalStatus['married'][1];
        $maritalStatus['sep'][2] = $maritalStatus['sep'][0] +$maritalStatus['sep'][1];
        $maritalStatus['widowed'][2] = $maritalStatus['widowed'][0] +$maritalStatus['widowed'][1];
        $maritalStatus['lt'][2] = $maritalStatus['lt'][0] +$maritalStatus['lt'][1];
        $maritalStatus['non'][2] = $maritalStatus['non'][0] +$maritalStatus['non'][1];
        
        $maritalStatuses = $maritalStatus;
        $totalMales = $maritalStatus['single'][0] +$maritalStatus['married'][0] +$maritalStatus['sep'][0] +$maritalStatus['widowed'][0] +$maritalStatus['lt'][0] +$maritalStatus['non'][0];
        $totalFemales = $maritalStatus['single'][1] +$maritalStatus['married'][1] +$maritalStatus['sep'][1] +$maritalStatus['widowed'][1] +$maritalStatus['lt'][1] +$maritalStatus['non'][1];
        $total = $totalMales +$totalFemales;
        
        $header = array('', 'Males', "Females", "Total");
        $data = array();
        $reportTitle = "Marital Status of STD Clinic Attendees - Quarter {$quarter} in {$year}";
        $fileName = "Marital_Status_Quarter_" .$quarter ."_" .$year;
        
        $data[] = array('Single', $maritalStatuses['single'][0], $maritalStatuses['single'][1], $maritalStatuses['single'][2]);
        $data[] = array('Married', $maritalStatuses['married'][0], $maritalStatuses['married'][1], $maritalStatuses['married'][2]);
        $data[] = array('Sep / Divo', $maritalStatuses['sep'][0], $maritalStatuses['sep'][1], $maritalStatuses['sep'][2]);
        $data[] = array('Widowed', $maritalStatuses['widowed'][0], $maritalStatuses['widowed'][1], $maritalStatuses['widowed'][2]);
        $data[] = array('Living Together', $maritalStatuses['lt'][0], $maritalStatuses['lt'][1], $maritalStatuses['lt'][2]);
        $data[] = array('Not Known ', $maritalStatuses['non'][0], $maritalStatuses['non'][1], $maritalStatuses['non'][2]);
        $data[] = array('Total ', $totalMales, $totalFemales, $total);
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName, 35);
        }
        die();
    }

    /**
     * Create occupation report
     *
     */
    public function executeCreateOccupationReport(sfWebRequest $request) {

    }

    /**
     * Display occupation report
     *
     */
    public function executeViewOccupationReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        
        $reportData = $this->_getPatientReportData($request);
        
        $occupation[0] = array(0 => 0, 1 => 0, 2 => 0); //Unemployed
        $occupation[1] = array(0 => 0, 1 => 0, 2 => 0); //Student
        $occupation[2] = array(0 => 0, 1 => 0, 2 => 0); //CSW
        $occupation[3] = array(0 => 0, 1 => 0, 2 => 0); //Retired
        $occupation[4] = array(0 => 0, 1 => 0, 2 => 0); //Other
        $occupation[5] = array(0 => 0, 1 => 0, 2 => 0); //Not known
        

        foreach ($reportData as $record) {
            
            if ($record['sex'] =="1") {
                
                //Male patients
                switch (trim($record['occupation'])) {
                    case "Unemployed" :
                        $occupation[0][0] = $occupation[0][0] +1;
                        break;
                    
                    case "Student" :
                        $occupation[1][0] = $occupation[1][0] +1;
                        break;
                    
                    case "CSW" :
                        $occupation[2][0] = $occupation[2][0] +1;
                        break;
                    
                    case "Retired" :
                        $occupation[3][0] = $occupation[3][0] +1;
                        break;
                    
                    default :
                        $occupation[4][0] = $occupation[4][0] +1;
                        break;
                }
            } else {
                
                //Female patients
                switch (trim($record['occupation'])) {
                    case "Unemployed" :
                        $occupation[0][1] = $occupation[0][1] +1;
                        break;
                    
                    case "Student" :
                        $occupation[1][1] = $occupation[1][1] +1;
                        break;
                    
                    case "CSW" :
                        $occupation[2][1] = $occupation[2][1] +1;
                        break;
                    
                    case "Retired" :
                        $occupation[3][1] = $occupation[3][1] +1;
                        break;
                    
                    default :
                        $occupation[4][0] = $occupation[4][0] +1;
                        break;
                }
            }
        }
        
        $occupation[0][2] = $occupation[0][0] +$occupation[0][1];
        $occupation[1][2] = $occupation[1][0] +$occupation[1][1];
        $occupation[2][2] = $occupation[2][0] +$occupation[2][1];
        $occupation[3][2] = $occupation[3][0] +$occupation[3][1];
        $occupation[4][2] = $occupation[4][0] +$occupation[4][1];
        $occupation[5][2] = $occupation[5][0] +$occupation[5][1];
        
        $this->occupations = $occupation;
        $this->totalMales = $occupation[0][0] +$occupation[1][0] +$occupation[2][0] +$occupation[3][0] +$occupation[4][0] +$occupation[5][0];
        $this->totalFemales = $occupation[0][1] +$occupation[1][1] +$occupation[2][1] +$occupation[3][1] +$occupation[4][1] +$occupation[5][1];
        $this->total = $this->totalMales +$this->totalFemales;
        $this->year = $year;
        $this->quarter = $quarter;
    }

    /**
     * Export occupation reports
     * @param r $request
     * @return unknown_type
     */
    public function executeExportOccupationReport(sfWebRequest $request) {

        $reportTye = $request->getParameter('rep');
        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        
        $reportData = $this->_getPatientReportData($request);
        
        $occupation[0] = array(0 => 0, 1 => 0, 2 => 0); //Unemployed
        $occupation[1] = array(0 => 0, 1 => 0, 2 => 0); //Student
        $occupation[2] = array(0 => 0, 1 => 0, 2 => 0); //CSW
        $occupation[3] = array(0 => 0, 1 => 0, 2 => 0); //Retired
        $occupation[4] = array(0 => 0, 1 => 0, 2 => 0); //Other
        $occupation[5] = array(0 => 0, 1 => 0, 2 => 0); //Not known
        

        foreach ($reportData as $record) {
            
            if ($record['sex'] =="1") {
                
                //Male patients
                switch (trim($record['occupation'])) {
                    case "Unemployed" :
                        $occupation[0][0] = $occupation[0][0] +1;
                        break;
                    
                    case "Student" :
                        $occupation[1][0] = $occupation[1][0] +1;
                        break;
                    
                    case "CSW" :
                        $occupation[2][0] = $occupation[2][0] +1;
                        break;
                    
                    case "Retired" :
                        $occupation[3][0] = $occupation[3][0] +1;
                        break;
                    
                    default :
                        $occupation[4][0] = $occupation[4][0] +1;
                        break;
                }
            } else {
                
                //Female patients
                switch (trim($record['occupation'])) {
                    case "Unemployed" :
                        $occupation[0][1] = $occupation[0][1] +1;
                        break;
                    
                    case "Student" :
                        $occupation[1][1] = $occupation[1][1] +1;
                        break;
                    
                    case "CSW" :
                        $occupation[2][1] = $occupation[2][1] +1;
                        break;
                    
                    case "Retired" :
                        $occupation[3][1] = $occupation[3][1] +1;
                        break;
                    
                    default :
                        $occupation[4][0] = $occupation[4][0] +1;
                        break;
                }
            }
        }
        
        $occupation[0][2] = $occupation[0][0] +$occupation[0][1];
        $occupation[1][2] = $occupation[1][0] +$occupation[1][1];
        $occupation[2][2] = $occupation[2][0] +$occupation[2][1];
        $occupation[3][2] = $occupation[3][0] +$occupation[3][1];
        $occupation[4][2] = $occupation[4][0] +$occupation[4][1];
        $occupation[5][2] = $occupation[5][0] +$occupation[5][1];
        
        $occupations = $occupation;
        $totalMales = $occupation[0][0] +$occupation[1][0] +$occupation[2][0] +$occupation[3][0] +$occupation[4][0] +$occupation[5][0];
        $totalFemales = $occupation[0][1] +$occupation[1][1] +$occupation[2][1] +$occupation[3][1] +$occupation[4][1] +$occupation[5][1];
        $total = $totalMales +$totalFemales;
        
        $header = array('', 'Males', "Females", "Total");
        $data = array();
        $reportTitle = "Occupations of STD Clinic Attendees - Quarter {$quarter} in {$year}";
        $fileName = "Occupations_Quarter_" .$quarter ."_" .$year;
        
        $data[] = array('Unemployed', $occupations[0][0], $occupations[0][1], $occupations[0][2]);
        $data[] = array('Student', $occupations[1][0], $occupations[1][1], $occupations[1][2]);
        $data[] = array('CSW ', $occupations[2][0], $occupations[2][1], $occupations[2][2]);
        $data[] = array('Retired', $occupations[3][0], $occupations[3][1], $occupations[3][2]);
        $data[] = array('Other ', $occupations[4][0], $occupations[4][1], $occupations[4][2]);
        $data[] = array('Not Known', $occupations[5][0], $occupations[5][1], $occupations[5][2]);
        $data[] = array('Total', $totalMales, $totalFemales, $total);
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName);
        }
        die();
    }

    /**
     * Create nationality report
     * @param $request
     */
    public function executeCreateNationalityReport(sfWebRequest $request) {

    }

    /**
     * Display nationality report
     * @param $request
     */
    public function executeViewNationalityReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        
        $repData = $this->_getPatientReportData($request);
        
        $arrNationality = array();
        
        foreach ($repData as $data) {
            
            if ($data['nationality'] !="0" &&! empty($data['nationality'])) {
                
                $key = strtolower($data['nationality']);
                
                if (array_key_exists($key, $arrNationality)) {
                    
                    $arrNationality[$key] = $arrNationality[$key] +1;
                } else {
                    
                    $arrNationality[$key] = 1;
                }
            }
        }
        
        $this->nationalties = $arrNationality;
        $this->quarter = $quarter;
        $this->year = $year;
    
    }

    /**
     * Export nationality reports
     * @param r $request
     * @return unknown_type
     */
    public function executeExportNationalityReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        $reportTye = $request->getParameter('rep');
        
        $repData = $this->_getPatientReportData($request);
        
        $arrNationality = array();
        
        foreach ($repData as $data) {
            
            if ($data['nationality'] !="0" &&! empty($data['nationality'])) {
                
                $key = strtolower($data['nationality']);
                
                if (array_key_exists($key, $arrNationality)) {
                    
                    $arrNationality[$key] = $arrNationality[$key] +1;
                } else {
                    
                    $arrNationality[$key] = 1;
                }
            }
        }
        
        $header = array('Country', "Total");
        $data = array();
        $reportTitle = "Nationalities of STD Clinic Attendees - Quarter {$quarter} in {$year}";
        $fileName = "Nationalities_Quarter_" .$quarter ."_" .$year;
        
        foreach ($arrNationality as $key => $value) {
            
            $temp = array();
            $temp[] = ucfirst($key);
            $temp[] = $value;
            
            $data[] = $temp;
        }
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName);
        }
        die();
    }

    /**
     * Create reason for attendance report
     *
     */
    public function executeCreateReasonForAttendanceReport(sfWebRequest $request) {

    }

    /**
     * Display reason for attendance report
     *
     */
    public function executeViewReasonForAttendanceReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        
        $objReport = new Report();
        $reportData = $objReport->getReasonForAttendanceReport($year, $quarter);
        $reasonForAttendance[0] = array(0 => 0, 1 => 0, 2 => 0); //Contact of patient
        $reasonForAttendance[1] = array(0 => 0, 1 => 0, 2 => 0); //Voluntary
        $reasonForAttendance[2] = array(0 => 0, 1 => 0, 2 => 0); //Court
        $reasonForAttendance[3] = array(0 => 0, 1 => 0, 2 => 0); //Blood
        $reasonForAttendance[4] = array(0 => 0, 1 => 0, 2 => 0); //Other
        

        foreach ($reportData as $record) {
            
            if ($record['sex'] =="1") {
                
                if (! empty($record['ClinicReason'][0]['contact'])) {
                    $reasonForAttendance[0][0] += 1;
                } else if (! empty($record['ClinicReason'][0]['volantary'])) {
                    $reasonForAttendance[1][0] += 1;
                } else if (! empty($record['ClinicReason'][0]['court'])) {
                    $reasonForAttendance[2][0] += 1;
                } else if (! empty($record['ClinicReason'][0]['bb'])) {
                    $reasonForAttendance[3][0] += 1;
                } else {
                    $reasonForAttendance[4][0] += 1;
                }
            } else {
                
                if (! empty($record['ClinicReason'][0]['contact'])) {
                    $reasonForAttendance[0][1] += 1;
                } else if (! empty($record['ClinicReason'][0]['volantary'])) {
                    $reasonForAttendance[1][1] += 1;
                } else if (! empty($record['ClinicReason'][0]['court'])) {
                    $reasonForAttendance[2][1] += 1;
                } else if (! empty($record['ClinicReason'][0]['bb'])) {
                    $reasonForAttendance[3][1] += 1;
                } else {
                    $reasonForAttendance[4][1] += 1;
                }
            }
        }
        
        $reasonForAttendance[0][2] = $reasonForAttendance[0][0] +$reasonForAttendance[0][1];
        $reasonForAttendance[1][2] = $reasonForAttendance[1][0] +$reasonForAttendance[1][1];
        $reasonForAttendance[2][2] = $reasonForAttendance[2][0] +$reasonForAttendance[2][1];
        $reasonForAttendance[3][2] = $reasonForAttendance[3][0] +$reasonForAttendance[3][1];
        $reasonForAttendance[4][2] = $reasonForAttendance[4][0] +$reasonForAttendance[4][1];
        
        $this->reason = $reasonForAttendance;
        $this->totalMales = $reasonForAttendance[0][0] +$reasonForAttendance[1][0] +$reasonForAttendance[2][0] +$reasonForAttendance[3][0] +$reasonForAttendance[4][0];
        $this->totalFemales = $reasonForAttendance[0][1] +$reasonForAttendance[1][1] +$reasonForAttendance[2][1] +$reasonForAttendance[3][1] +$reasonForAttendance[4][1];
        $this->total = $this->totalMales +$this->totalFemales;
        $this->year = $year;
        $this->quarter = $quarter;
    }

    /**
     * Export reason for attendance report
     * @param r $request
     * @return unknown_type
     */
    public function executeExportReasonsForAttendanceReport(sfWebRequest $request) {

        $reportTye = $request->getParameter('rep');
        $quarter = $request->getParameter('qtr');
        $year = $request->getParameter('year');
        
        $objReport = new Report();
        $reportData = $objReport->getReasonForAttendanceReport($year, $quarter);
        $reasonForAttendance[0] = array(0 => 0, 1 => 0, 2 => 0); //Contact of patient
        $reasonForAttendance[1] = array(0 => 0, 1 => 0, 2 => 0); //Voluntary
        $reasonForAttendance[2] = array(0 => 0, 1 => 0, 2 => 0); //Court
        $reasonForAttendance[3] = array(0 => 0, 1 => 0, 2 => 0); //Blood
        $reasonForAttendance[4] = array(0 => 0, 1 => 0, 2 => 0); //Other
        

        foreach ($reportData as $record) {
            
            if ($record['sex'] =="1") {
                
                if (! empty($record['ClinicReason'][0]['contact'])) {
                    $reasonForAttendance[0][0] += 1;
                } else if (! empty($record['ClinicReason'][0]['volantary'])) {
                    $reasonForAttendance[1][0] += 1;
                } else if (! empty($record['ClinicReason'][0]['court'])) {
                    $reasonForAttendance[2][0] += 1;
                } else if (! empty($record['ClinicReason'][0]['bb'])) {
                    $reasonForAttendance[3][0] += 1;
                } else {
                    $reasonForAttendance[4][0] += 1;
                }
            } else {
                
                if (! empty($record['ClinicReason'][0]['contact'])) {
                    $reasonForAttendance[0][1] += 1;
                } else if (! empty($record['ClinicReason'][0]['volantary'])) {
                    $reasonForAttendance[1][1] += 1;
                } else if (! empty($record['ClinicReason'][0]['court'])) {
                    $reasonForAttendance[2][1] += 1;
                } else if (! empty($record['ClinicReason'][0]['bb'])) {
                    $reasonForAttendance[3][1] += 1;
                } else {
                    $reasonForAttendance[4][1] += 1;
                }
            }
        }
        
        $reasonForAttendance[0][2] = $reasonForAttendance[0][0] +$reasonForAttendance[0][1];
        $reasonForAttendance[1][2] = $reasonForAttendance[1][0] +$reasonForAttendance[1][1];
        $reasonForAttendance[2][2] = $reasonForAttendance[2][0] +$reasonForAttendance[2][1];
        $reasonForAttendance[3][2] = $reasonForAttendance[3][0] +$reasonForAttendance[3][1];
        $reasonForAttendance[4][2] = $reasonForAttendance[4][0] +$reasonForAttendance[4][1];
        
        $totalMales = $reasonForAttendance[0][0] +$reasonForAttendance[1][0] +$reasonForAttendance[2][0] +$reasonForAttendance[3][0] +$reasonForAttendance[4][0];
        $totalFemales = $reasonForAttendance[0][1] +$reasonForAttendance[1][1] +$reasonForAttendance[2][1] +$reasonForAttendance[3][1] +$reasonForAttendance[4][1];
        $total = $totalMales +$totalFemales;
        
        $reason = $reasonForAttendance;
        
        $header = array('', 'Males', "Females", "Total");
        $data = array();
        $reportTitle = "Reasons For Clinic Attendance - Quarter " .$quarter ." in " .$year;
        $fileName = "Reasons_For_Attendance_Qtr_" .$quarter ."_in_" .$year;
        
        $data[] = array('Contact of Patient', $reason[0][0], $reason[0][1], $reason[0][2]);
        $data[] = array('Voluntary', $reason[10][0], $reason[1][1], $reason[1][2]);
        $data[] = array('Referral from Court', $reason[2][0], $reason[2][1], $reason[2][2]);
        $data[] = array('Blood Bank', $reason[3][0], $reason[3][1], $reason[3][2]);
        $data[] = array('Other', $reason[4][0], $reason[4][1], $reason[4][2]);
        $data[] = array('Total', $totalMales, $totalFemales, $total);
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName, 35);
        }
        die();
    }

    /**
     * Create appointment report of patients
     * @param $request
     */
    public function executeCreateAppointmentReport(sfWebRequest $request) {

    }

    /**
     * Display the appointment report
     * @param $request
     */
    public function executeViewAppointmentReport(sfWebRequest $request) {

        $date = $request->getParameter('txtDate');
        $gender = $request->getParameter('cmbGender');
        
        $visit = new Visit();
        
        $this->appoinmentDate = $date;
        $this->gender = $gender;
        $this->appoinments = $visit->viewAppoinmentDeatils($date, $gender);
    }

    /**
     * Export appointment reports
     * @param r $request
     * @return unknown_type
     */
    public function executeExportAppointmentReport(sfWebRequest $request) {

        $reportTye = $request->getParameter('rep');
        $gender = $request->getParameter('gender');
        $date = $request->getParameter('appDate');
        
        $visit = new Visit();
        $appointments = $visit->viewAppoinmentDeatils($date, $gender);
        
        if ($gender =="1") {
            $gender = "Male";
        } else {
            $gender = "Female";
        }
        
        $header = array('Patient No', 'Name', "Visit No");
        $data = array();
        $reportTitle = "Appointments of " .$gender ." Patients on " .$date;
        $fileName = "Appointments_" .$gender ."_" .$date;
        
        foreach ($appointments as $appoinment) {
            
            $arrValues = array();
            $arrValues[0] = $appoinment['patient_no'];
            $arrValues[1] = base64_decode($appoinment['first_name']) ." " .base64_decode($appoinment['last_name']);
            $arrValues[2] = $appoinment['Visit'][0]['visit_no'];
            
            $data[] = $arrValues;
        }
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName);
        }
        die();
    }

    /**
     * Create new STD episode report
     * @param r $request
     * @return unknown_type
     */
    public function executeCreateNewStdEpisodeReport(sfWebRequest $request) {

    }

    /**
     * Display STD new episode report
     * @param r $request
     * @return unknown_type
     */
    public function executeViewNewStdEpisodeReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        
        $objReport = new Report();
        $reportData = $objReport->getNewStdEpisodeReport($year, $quarter);
        $stdInputResults = $objReport->getResultCountOfInput();
        $arrResultCodes = array();
        foreach ($stdInputResults as $stdResult) {
            
            $arrResultCodes[] = $stdResult['input_result_code'];
        }
        
        $arrAges = array('0-14', '15-19', '20-24', '25-29', '30-34', '35-39', '40-44', '45-49', '50+', 'Total');
        $reportRow = array();
        
        for ($a = 0; $a <count($arrResultCodes); $a++) {
            
            for ($c = 0; $c <count($arrAges); $c++) {
                $arr[$c] = array('m' => 0, 'f' => 0, 'mtot' => 0, 'ftot' => 0);
            }
            
            $reportRow[$arrResultCodes[$a]] = $arr;
        }
        
        foreach ($reportData as $record) {
            
            $sex = $record['sex'];
            $dateOfBirth = $record['date_of_birth'];
            $age = $this->_getAge($dateOfBirth);
            
            $stdResults = $record['StdResult'];
            
            foreach ($stdResults as $result) {
                
                $resultCode = $result['result_code'];
                
                if (in_array($resultCode, $arrResultCodes)) {
                    
                    if ($sex =="1") {
                        
                        if ($age >0 &&$age <=14) {
                            $reportRow[$resultCode][0]['m'] += 1;
                            //$reportRow[$resultCode][0]
                        } else if ($age >=15 &&$age <=19) {
                            $reportRow[$resultCode][1]['m'] += 1;
                        } else if ($age >=20 &&$age <=24) {
                            $reportRow[$resultCode][2]['m'] += 1;
                        } else if ($age >=25 &&$age <=29) {
                            $reportRow[$resultCode][3]['m'] += 1;
                        } else if ($age >=30 &&$age <=34) {
                            $reportRow[$resultCode][4]['m'] += 1;
                            $reportRow[$resultCode][4]['mtot'] += 1;
                        } else if ($age >=35 &&$age <=39) {
                            $reportRow[$resultCode][5]['m'] += 1;
                        } else if ($age >=40 &&$age <=44) {
                            $reportRow[$resultCode][6]['m'] += 1;
                        } else if ($age >=45 &&$age <=49) {
                            $reportRow[$resultCode][7]['m'] += 1;
                        } else if ($age >=50) {
                            $reportRow[$resultCode][8]['m'] += 1;
                        }
                    } else {
                        if ($age >0 &&$age <=14) {
                            $reportRow[$resultCode][0]['f'] += 1;
                        } else if ($age >=15 &&$age <=19) {
                            $reportRow[$resultCode][1]['f'] += 1;
                        } else if ($age >=20 &&$age <=24) {
                            $reportRow[$resultCode][2]['f'] += 1;
                        } else if ($age >=25 &&$age <=29) {
                            $reportRow[$resultCode][3]['f'] += 1;
                        } else if ($age >=30 &&$age <=34) {
                            $reportRow[$resultCode][4]['f'] += 1;
                            $reportRow[$resultCode][4]['ftot'] += 1;
                        } else if ($age >=35 &&$age <=39) {
                            $reportRow[$resultCode][5]['f'] += 1;
                        } else if ($age >=40 &&$age <=44) {
                            $reportRow[$resultCode][6]['f'] += 1;
                        } else if ($age >=45 &&$age <=49) {
                            $reportRow[$resultCode][7]['f'] += 1;
                        } else if ($age >=50) {
                            $reportRow[$resultCode][8]['f'] += 1;
                        }
                    }
                } else {
                
                }
            }
        }
        
        //Keep the column valus of all STD results
        $arrTotCol = array();
        $arrTotCol[0] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[1] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[2] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[3] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[4] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[5] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[6] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[7] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[8] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[9] = array("m" => 0, "f" => 0, "gtot" => 0);
        
        //Keep all the column values except non venereal
        $arrNonVeneral[0] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[1] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[2] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[3] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[4] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[5] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[6] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[7] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[8] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[9] = array("m" => 0, "f" => 0, "gtot" => 0);
        
        foreach ($reportRow as $index => $row) {
            foreach ($row as $key => $val) {
                //Total venereal
                if ($index !=234) {
                    $arrTotCol[$key]['m'] = $arrTotCol[$key]['m'] +$val['m'];
                    $arrTotCol[$key]['f'] = $arrTotCol[$key]['f'] +$val['f'];
                    $arrTotCol[$key]['gtot'] = $arrTotCol[$key]['m'] +$arrTotCol[$key]['f'];
                }
                
                //Total non venereal
                if ($index ==234) {
                    
                    $arrNonVeneral[$key]['m'] = $arrNonVeneral[$key]['m'] +$val['m'];
                    $arrNonVeneral[$key]['f'] = $arrNonVeneral[$key]['f'] +$val['f'];
                    $arrNonVeneral[$key]['gtot'] = $arrNonVeneral[$key]['m'] +$arrNonVeneral[$key]['f'];
                }
            }
        
        }
        
        //Grand total of venereal
        $arrColNetTot = array("m" => 0, "f" => 0, "cgot" => 0);
        
        foreach ($arrTotCol as $col) {
            $arrColNetTot['m'] = $arrColNetTot['m'] +$col['m'];
            $arrColNetTot['f'] = $arrColNetTot['f'] +$col['f'];
            $arrColNetTot['cgot'] = $arrColNetTot['cgot'] +$col['gtot'];
        }
        
        //Grand total of non venereal
        $arrNonVerNetTot = array("m" => 0, "f" => 0, "cgot" => 0);
        
        foreach ($arrNonVeneral as $nonVerCol) {
            $arrNonVerNetTot['m'] = $arrNonVerNetTot['m'] +$nonVerCol['m'];
            $arrNonVerNetTot['f'] = $arrNonVerNetTot['f'] +$nonVerCol['f'];
            $arrNonVerNetTot['cgot'] = $arrNonVerNetTot['cgot'] +$nonVerCol['gtot'];
        }
        
        $resultNames = array('No illness', 'HIV Positive', 'GC', 'Early Syphilis', 'Late Syphilis', 'Cong Syphilis', 'Herpes', 'Chlamydia', 'NGI/NSGI', 'Trichomoniasis', 'Warts', 'Pubic Lice', 'Scabies', 'Candida', 'Bacterial Vagino', 'Epididymitis', 'Molluscum', 'Opth. neonatorum', 'Other STD', 'Uncertain');
        
        $inputCode = "INC048";
        $stdInputResult = new StdInputResult();
        
        $this->inputResult = $resultNames;
        $this->repRow = $reportRow;
        $this->startDate = $objReport->getStartDate();
        $this->endDate = $objReport->getEndDate();
        $this->quarter = $quarter;
        $this->num = count($stdInputResults);
        $this->totCol = $arrTotCol;
        $this->arrColNetTot = $arrColNetTot;
        $this->arrNonVeneral = $arrNonVeneral;
        $this->arrNonVerNetTot = $arrNonVerNetTot;
        $this->year = $year;
        $this->quarter = $quarter;
    }

    /**
     * Export new STD episode reports
     * @param r $request
     * @return unknown_type
     */
    public function executeExportNewStdEpisodeReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        $reportTye = $request->getParameter('rep');
        
        $objReport = new Report();
        $reportData = $objReport->getNewStdEpisodeReport($year, $quarter);
        $stdInputResults = $objReport->getResultCountOfInput();
        $arrResultCodes = array();
        foreach ($stdInputResults as $stdResult) {
            
            $arrResultCodes[] = $stdResult['input_result_code'];
        }
        
        $arrAges = array('0-14', '15-19', '20-24', '25-29', '30-34', '35-39', '40-44', '45-49', '50+', 'Total');
        $reportRow = array();
        
        for ($a = 0; $a <count($arrResultCodes); $a++) {
            
            for ($c = 0; $c <count($arrAges); $c++) {
                $arr[$c] = array('m' => 0, 'f' => 0, 'mtot' => 0, 'ftot' => 0);
            }
            
            $reportRow[$arrResultCodes[$a]] = $arr;
        }
        
        foreach ($reportData as $record) {
            
            $sex = $record['sex'];
            $dateOfBirth = $record['date_of_birth'];
            $age = $this->_getAge($dateOfBirth);
            
            $stdResults = $record['StdResult'];
            
            foreach ($stdResults as $result) {
                
                $resultCode = $result['result_code'];
                
                if (in_array($resultCode, $arrResultCodes)) {
                    
                    if ($sex =="1") {
                        
                        if ($age >0 &&$age <=14) {
                            $reportRow[$resultCode][0]['m'] += 1;
                            //$reportRow[$resultCode][0]
                        } else if ($age >=15 &&$age <=19) {
                            $reportRow[$resultCode][1]['m'] += 1;
                        } else if ($age >=20 &&$age <=24) {
                            $reportRow[$resultCode][2]['m'] += 1;
                        } else if ($age >=25 &&$age <=29) {
                            $reportRow[$resultCode][3]['m'] += 1;
                        } else if ($age >=30 &&$age <=34) {
                            $reportRow[$resultCode][4]['m'] += 1;
                            $reportRow[$resultCode][4]['mtot'] += 1;
                        } else if ($age >=35 &&$age <=39) {
                            $reportRow[$resultCode][5]['m'] += 1;
                        } else if ($age >=40 &&$age <=44) {
                            $reportRow[$resultCode][6]['m'] += 1;
                        } else if ($age >=45 &&$age <=49) {
                            $reportRow[$resultCode][7]['m'] += 1;
                        } else if ($age >=50) {
                            $reportRow[$resultCode][8]['m'] += 1;
                        }
                    } else {
                        if ($age >0 &&$age <=14) {
                            $reportRow[$resultCode][0]['f'] += 1;
                        } else if ($age >=15 &&$age <=19) {
                            $reportRow[$resultCode][1]['f'] += 1;
                        } else if ($age >=20 &&$age <=24) {
                            $reportRow[$resultCode][2]['f'] += 1;
                        } else if ($age >=25 &&$age <=29) {
                            $reportRow[$resultCode][3]['f'] += 1;
                        } else if ($age >=30 &&$age <=34) {
                            $reportRow[$resultCode][4]['f'] += 1;
                            $reportRow[$resultCode][4]['ftot'] += 1;
                        } else if ($age >=35 &&$age <=39) {
                            $reportRow[$resultCode][5]['f'] += 1;
                        } else if ($age >=40 &&$age <=44) {
                            $reportRow[$resultCode][6]['f'] += 1;
                        } else if ($age >=45 &&$age <=49) {
                            $reportRow[$resultCode][7]['f'] += 1;
                        } else if ($age >=50) {
                            $reportRow[$resultCode][8]['f'] += 1;
                        }
                    }
                } else {
                
                }
            }
        }
        
        //Keep the column valus of all STD results
        $arrTotCol = array();
        $arrTotCol[0] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[1] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[2] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[3] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[4] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[5] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[6] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[7] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[8] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[9] = array("m" => 0, "f" => 0, "gtot" => 0);
        
        //Keep all the column values except non venereal
        $arrNonVeneral[0] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[1] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[2] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[3] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[4] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[5] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[6] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[7] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[8] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[9] = array("m" => 0, "f" => 0, "gtot" => 0);
        
        foreach ($reportRow as $index => $row) {
            foreach ($row as $key => $val) {
                //Total venereal
                if ($index !=234) {
                    $arrTotCol[$key]['m'] = $arrTotCol[$key]['m'] +$val['m'];
                    $arrTotCol[$key]['f'] = $arrTotCol[$key]['f'] +$val['f'];
                    $arrTotCol[$key]['gtot'] = $arrTotCol[$key]['m'] +$arrTotCol[$key]['f'];
                }
                
                //Total non venereal
                if ($index ==234) {
                    
                    $arrNonVeneral[$key]['m'] = $arrNonVeneral[$key]['m'] +$val['m'];
                    $arrNonVeneral[$key]['f'] = $arrNonVeneral[$key]['f'] +$val['f'];
                    $arrNonVeneral[$key]['gtot'] = $arrNonVeneral[$key]['m'] +$arrNonVeneral[$key]['f'];
                }
            }
        
        }
        
        //Grand total of venereal
        $arrColNetTot = array("m" => 0, "f" => 0, "cgot" => 0);
        
        foreach ($arrTotCol as $col) {
            $arrColNetTot['m'] = $arrColNetTot['m'] +$col['m'];
            $arrColNetTot['f'] = $arrColNetTot['f'] +$col['f'];
            $arrColNetTot['cgot'] = $arrColNetTot['cgot'] +$col['gtot'];
        }
        
        //Grand total of non venereal
        $arrNonVerNetTot = array("m" => 0, "f" => 0, "cgot" => 0);
        
        foreach ($arrNonVeneral as $nonVerCol) {
            $arrNonVerNetTot['m'] = $arrNonVerNetTot['m'] +$nonVerCol['m'];
            $arrNonVerNetTot['f'] = $arrNonVerNetTot['f'] +$nonVerCol['f'];
            $arrNonVerNetTot['cgot'] = $arrNonVerNetTot['cgot'] +$nonVerCol['gtot'];
        }
        
        $resultNames = array('No illness', 'HIV Positive', 'GC', 'Early Syphilis', 'Late Syphilis', 'Cong Syphilis', 'Herpes', 'Chlamydia', 'NGI/NSGI', 'Trichomoniasis', 'Warts', 'Pubic Lice', 'Scabies', 'Candida', 'Bacterial Vagino', 'Epididymitis', 'Molluscum', 'Opth. neonatorum', 'Other STD', 'Uncertain');
        
        $data = array();
        
        $data[] = array("", "M", "F", "M", "F", "M", "F", "M", "F", "M", "F", "M", "F", "M", "F", "M", "F", "M", "F", "M", "F", "");
        
        $a = 0;
        $arr = array();
        foreach ($reportRow as $id => $row) {
            //$temp[0] = $row[$a]['m'];
            //$temp[1] = $row[$a]['f'];
            $arr[$id][0] = $arr[$id][0] +$row[$a]['m'];
            $arr[$id][1] = $arr[$id][0] +$row[$a]['f'];
            $totalMale = $row[0]['m'] +$row[1]['m'] +$row[2]['m'] +$row[3]['m'] +$row[4]['m'] +$row[5]['m'] +$row[6]['m'] +$row[7]['m'] +$row[8]['m'];
            $totalFemale = $row[0]['f'] +$row[1]['f'] +$row[2]['f'] +$row[3]['f'] +$row[4]['f'] +$row[5]['f'] +$row[6]['f'] +$row[7]['f'] +$row[8]['f'];
            $grandTotal = $totalMale +$totalFemale;
            
            $arrTemp = array();
            $arrTemp[] = $resultNames[$a];
            $arrTemp[] = $row[0]['m'];
            $arrTemp[] = $row[0]['f'];
            $arrTemp[] = $row[1]['m'];
            $arrTemp[] = $row[1]['f'];
            $arrTemp[] = $row[2]['m'];
            $arrTemp[] = $row[2]['f'];
            $arrTemp[] = $row[3]['m'];
            $arrTemp[] = $row[3]['f'];
            $arrTemp[] = $row[4]['m'];
            $arrTemp[] = $row[4]['f'];
            $arrTemp[] = $row[5]['m'];
            $arrTemp[] = $row[5]['f'];
            $arrTemp[] = $row[6]['m'];
            $arrTemp[] = $row[6]['f'];
            $arrTemp[] = $row[7]['m'];
            $arrTemp[] = $row[7]['f'];
            $arrTemp[] = $row[8]['m'];
            $arrTemp[] = $row[8]['f'];
            $arrTemp[] = $totalMale;
            $arrTemp[] = $totalFemale;
            $arrTemp[] = $grandTotal;
            
            $data[] = $arrTemp;
            $a++;
        }
        
        $arrTemp2[] = "Total Venereal ";
        
        /* for ($a=0; $a<count($arrTotCol);$a++) {
            $arrTemp2[] = $arrTotCol[$a]['m'];
            $arrTemp2[] = $arrTotCol[$a]['f'];
           // echo $a;
        }*/
        $arrTemp2[] = $arrTotCol[0]['m'];
        $arrTemp2[] = $arrTotCol[0]['f'];
        $arrTemp2[] = $arrTotCol[1]['m'];
        $arrTemp2[] = $arrTotCol[1]['f'];
        $arrTemp2[] = $arrTotCol[2]['m'];
        $arrTemp2[] = $arrTotCol[2]['f'];
        $arrTemp2[] = $arrTotCol[3]['m'];
        $arrTemp2[] = $arrTotCol[3]['f'];
        $arrTemp2[] = $arrTotCol[4]['m'];
        $arrTemp2[] = $arrTotCol[4]['f'];
        $arrTemp2[] = $arrTotCol[5]['m'];
        $arrTemp2[] = $arrTotCol[5]['f'];
        $arrTemp2[] = $arrTotCol[6]['m'];
        $arrTemp2[] = $arrTotCol[6]['f'];
        $arrTemp2[] = $arrTotCol[7]['m'];
        $arrTemp2[] = $arrTotCol[7]['f'];
        $arrTemp2[] = $arrTotCol[8]['m'];
        $arrTemp2[] = $arrTotCol[8]['f'];
        
        $arrTemp2[] = $arrColNetTot['m'];
        $arrTemp2[] = $arrColNetTot['f'];
        $arrTemp2[] = $arrColNetTot['cgot'];
        
        $data[] = $arrTemp2;
        
        $arrTemp3[] = "Total Non Venereal ";
        $arrTemp3[] = $arrNonVeneral[0]['m'];
        $arrTemp3[] = $arrNonVeneral[0]['f'];
        $arrTemp3[] = $arrNonVeneral[1]['m'];
        $arrTemp3[] = $arrNonVeneral[1]['f'];
        $arrTemp3[] = $arrNonVeneral[2]['f'];
        $arrTemp3[] = $arrNonVeneral[2]['m'];
        $arrTemp3[] = $arrNonVeneral[3]['m'];
        $arrTemp3[] = $arrNonVeneral[3]['f'];
        $arrTemp3[] = $arrNonVeneral[4]['m'];
        $arrTemp3[] = $arrNonVeneral[4]['f'];
        $arrTemp3[] = $arrNonVeneral[5]['m'];
        $arrTemp3[] = $arrNonVeneral[5]['f'];
        $arrTemp3[] = $arrNonVeneral[6]['m'];
        $arrTemp3[] = $arrNonVeneral[6]['f'];
        $arrTemp3[] = $arrNonVeneral[7]['m'];
        $arrTemp3[] = $arrNonVeneral[7]['f'];
        $arrTemp3[] = $arrNonVeneral[8]['m'];
        $arrTemp3[] = $arrNonVeneral[8]['f'];
        
        $arrTemp3[] = $arrNonVerNetTot['m'];
        $arrTemp3[] = $arrNonVerNetTot['f'];
        $arrTemp3[] = $arrNonVerNetTot['cgot'];
        
        $data[] = $arrTemp3;
        
        $arrTemp4[] = "Grand Total  ";
        $arrTemp4[] = $arrTotCol[0]['m'] +$arrNonVeneral[0]['m'];
        $arrTemp4[] = $arrTotCol[0]['f'] +$arrNonVeneral[0]['f'];
        $arrTemp4[] = $arrTotCol[1]['m'] +$arrNonVeneral[1]['m'];
        $arrTemp4[] = $arrTotCol[1]['f'] +$arrNonVeneral[1]['f'];
        $arrTemp4[] = $arrTotCol[2]['m'] +$arrNonVeneral[2]['m'];
        $arrTemp4[] = $arrTotCol[2]['f'] +$arrNonVeneral[2]['f'];
        $arrTemp4[] = $arrTotCol[3]['m'] +$arrNonVeneral[3]['m'];
        $arrTemp4[] = $arrTotCol[3]['f'] +$arrNonVeneral[3]['f'];
        ;
        $arrTemp4[] = $arrTotCol[4]['m'] +$arrNonVeneral[4]['m'];
        $arrTemp4[] = $arrTotCol[4]['f'] +$arrNonVeneral[4]['f'];
        $arrTemp4[] = $arrTotCol[5]['m'] +$arrNonVeneral[5]['m'];
        $arrTemp4[] = $arrTotCol[5]['f'] +$arrNonVeneral[5]['f'];
        $arrTemp4[] = $arrTotCol[6]['m'] +$arrNonVeneral[6]['m'];
        $arrTemp4[] = $arrTotCol[6]['f'] +$arrNonVeneral[6]['f'];
        $arrTemp4[] = $arrTotCol[7]['m'] +$arrNonVeneral[7]['m'];
        $arrTemp4[] = $arrTotCol[7]['f'] +$arrNonVeneral[7]['f'];
        $arrTemp4[] = $arrTotCol[8]['m'] +$arrNonVeneral[8]['m'];
        $arrTemp4[] = $arrTotCol[8]['f'] +$arrNonVeneral[8]['f'];
        
        $arrTemp4[] = $arrColNetTot['m'] +$arrNonVerNetTot['m'];
        $arrTemp4[] = $arrColNetTot['m'] +$arrNonVerNetTot['f'];
        $arrTemp4[] = $arrColNetTot['cgot'] +$arrNonVerNetTot['cgot'];
        
        $data[] = $arrTemp4;
        
        $header = array("", '0-14', '0-14', '15-19', '15-19', '20-24', '20-24', '25-29', '25-29', '30-34', '30-34', '35-39', '35-39', '40-44', '40-44', '45-49', '45-49', '50+', '50+', 'Total', 'Total', 'Grand Total ');
        $reportTitle = "New Episodes of STD Recorded Quarter " .$quarter ." in " .$year;
        $fileName = "New_Episodes_Of_STD_Quarter_" .$quarter ."_" .$year;
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName, 8);
        }
        die();
    
    }

    /**
     * Create new STD episode report of comercial sex workers
     * @param r $request
     * @return unknown_type
     */
    public function executeCreateNewEpisodeOfCswReport(sfWebRequest $request) {

    }

    /**
     * Display new STD episode report of comercial sex workers
     * @param r $request
     * @return unknown_type
     */
    public function executeViewNewEpisodesOfCswReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        
        $objReport = new Report();
        $stdInputResults = $objReport->getResultCountOfInput();
        $reportData = $objReport->getNewStdEpisodeOfCswReport($year, $quarter);
        
        $arrResultCodes = array();
        foreach ($stdInputResults as $stdResult) {
            
            $arrResultCodes[] = $stdResult['input_result_code'];
        }
        
        $arrAges = array('0-14', '15-19', '20-24', '25-29', '30-34', '35-39', '40-44', '45-49', '50+', 'Total');
        $reportRow = array();
        
        for ($a = 0; $a <count($arrResultCodes); $a++) {
            
            for ($c = 0; $c <count($arrAges); $c++) {
                $arr[$c] = array('m' => 0, 'f' => 0, 'mtot' => 0, 'ftot' => 0);
            }
            
            $reportRow[$arrResultCodes[$a]] = $arr;
        }
        
        foreach ($reportData as $record) {
            
            $sex = $record['sex'];
            $dateOfBirth = $record['date_of_birth'];
            $age = $this->_getAge($dateOfBirth);
            
            $stdResults = $record['StdResult'];
            
            foreach ($stdResults as $result) {
                
                $resultCode = $result['result_code'];
                
                if (in_array($resultCode, $arrResultCodes)) {
                    
                    if ($sex =="1") {
                        
                        if ($age >0 &&$age <=14) {
                            $reportRow[$resultCode][0]['m'] += 1;
                            //$reportRow[$resultCode][0]
                        } else if ($age >=15 &&$age <=19) {
                            $reportRow[$resultCode][1]['m'] += 1;
                        } else if ($age >=20 &&$age <=24) {
                            $reportRow[$resultCode][2]['m'] += 1;
                        } else if ($age >=25 &&$age <=29) {
                            $reportRow[$resultCode][3]['m'] += 1;
                        } else if ($age >=30 &&$age <=34) {
                            $reportRow[$resultCode][4]['m'] += 1;
                            $reportRow[$resultCode][4]['mtot'] += 1;
                        } else if ($age >=35 &&$age <=39) {
                            $reportRow[$resultCode][5]['m'] += 1;
                        } else if ($age >=40 &&$age <=44) {
                            $reportRow[$resultCode][6]['m'] += 1;
                        } else if ($age >=45 &&$age <=49) {
                            $reportRow[$resultCode][7]['m'] += 1;
                        } else if ($age >=50) {
                            $reportRow[$resultCode][8]['m'] += 1;
                        }
                    } else {
                        if ($age >0 &&$age <=14) {
                            $reportRow[$resultCode][0]['f'] += 1;
                        } else if ($age >=15 &&$age <=19) {
                            $reportRow[$resultCode][1]['f'] += 1;
                        } else if ($age >=20 &&$age <=24) {
                            $reportRow[$resultCode][2]['f'] += 1;
                        } else if ($age >=25 &&$age <=29) {
                            $reportRow[$resultCode][3]['f'] += 1;
                        } else if ($age >=30 &&$age <=34) {
                            $reportRow[$resultCode][4]['f'] += 1;
                            $reportRow[$resultCode][4]['ftot'] += 1;
                        } else if ($age >=35 &&$age <=39) {
                            $reportRow[$resultCode][5]['f'] += 1;
                        } else if ($age >=40 &&$age <=44) {
                            $reportRow[$resultCode][6]['f'] += 1;
                        } else if ($age >=45 &&$age <=49) {
                            $reportRow[$resultCode][7]['f'] += 1;
                        } else if ($age >=50) {
                            $reportRow[$resultCode][8]['f'] += 1;
                        }
                    }
                } else {
                
                }
            }
        }
        
        $resultNames = array('No illness', 'HIV Positive', 'GC', 'Early Syphilis', 'Late Syphilis', 'Cong Syphilis', 'Herpes', 'Chlamydia', 'NGI/NSGI', 'Trichomoniasis', 'Warts', 'Pubic Lice', 'Scabies', 'Candida', 'Bacterial Vagino', 'Epididymitis', 'Molluscum', 'Opth. neonatorum', 'Other STD', 'Uncertain');
        
        //Keep the column valus of all STD results
        $arrTotCol = array();
        $arrTotCol[0] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[1] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[2] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[3] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[4] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[5] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[6] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[7] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[8] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[9] = array("m" => 0, "f" => 0, "gtot" => 0);
        
        //Keep all the column values except non venereal
        $arrNonVeneral[0] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[1] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[2] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[3] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[4] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[5] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[6] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[7] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[8] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[9] = array("m" => 0, "f" => 0, "gtot" => 0);
        
        foreach ($reportRow as $index => $row) {
            foreach ($row as $key => $val) {
                //Total venereal
                if ($index !=234) {
                    $arrTotCol[$key]['m'] = $arrTotCol[$key]['m'] +$val['m'];
                    $arrTotCol[$key]['f'] = $arrTotCol[$key]['f'] +$val['f'];
                    $arrTotCol[$key]['gtot'] = $arrTotCol[$key]['m'] +$arrTotCol[$key]['f'];
                }
                
                //Total non venereal
                if ($index ==234) {
                    
                    $arrNonVeneral[$key]['m'] = $arrNonVeneral[$key]['m'] +$val['m'];
                    $arrNonVeneral[$key]['f'] = $arrNonVeneral[$key]['f'] +$val['f'];
                    $arrNonVeneral[$key]['gtot'] = $arrNonVeneral[$key]['m'] +$arrNonVeneral[$key]['f'];
                }
            }
        
        }
        
        //Grand total of venereal
        $arrColNetTot = array("m" => 0, "f" => 0, "cgot" => 0);
        
        foreach ($arrTotCol as $col) {
            $arrColNetTot['m'] = $arrColNetTot['m'] +$col['m'];
            $arrColNetTot['f'] = $arrColNetTot['f'] +$col['f'];
            $arrColNetTot['cgot'] = $arrColNetTot['cgot'] +$col['gtot'];
        }
        
        //Grand total of non venereal
        $arrNonVerNetTot = array("m" => 0, "f" => 0, "cgot" => 0);
        
        foreach ($arrNonVeneral as $nonVerCol) {
            $arrNonVerNetTot['m'] = $arrNonVerNetTot['m'] +$nonVerCol['m'];
            $arrNonVerNetTot['f'] = $arrNonVerNetTot['f'] +$nonVerCol['f'];
            $arrNonVerNetTot['cgot'] = $arrNonVerNetTot['cgot'] +$nonVerCol['gtot'];
        }
        
        $inputCode = "INC048";
        $stdInputResult = new StdInputResult();
        $this->inputResult = $resultNames;
        $this->repRow = $reportRow;
        $this->startDate = $objReport->getStartDate();
        $this->endDate = $objReport->getEndDate();
        $this->quarter = $quarter;
        $this->year = $year;
        $this->num = count($stdInputResults);
        $this->totCol = $arrTotCol;
        $this->arrColNetTot = $arrColNetTot;
        $this->arrNonVeneral = $arrNonVeneral;
        $this->arrNonVerNetTot = $arrNonVerNetTot;
        $this->year = $year;
        $this->quarter = $quarter;
    }

    /**
     * Export new STD episode report of comercial sex workers
     * @param r $request
     * @return unknown_type
     */
    public function executeExportNewEpisodesOfCswReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        $reportTye = $request->getParameter('rep');
        
        $objReport = new Report();
        $stdInputResults = $objReport->getResultCountOfInput();
        $reportData = $objReport->getNewStdEpisodeOfCswReport($year, $quarter);
        
        $arrResultCodes = array();
        foreach ($stdInputResults as $stdResult) {
            
            $arrResultCodes[] = $stdResult['input_result_code'];
        }
        
        $arrAges = array('0-14', '15-19', '20-24', '25-29', '30-34', '35-39', '40-44', '45-49', '50+', 'Total');
        $reportRow = array();
        
        for ($a = 0; $a <count($arrResultCodes); $a++) {
            
            for ($c = 0; $c <count($arrAges); $c++) {
                $arr[$c] = array('m' => 0, 'f' => 0, 'mtot' => 0, 'ftot' => 0);
            }
            
            $reportRow[$arrResultCodes[$a]] = $arr;
        }
        
        foreach ($reportData as $record) {
            
            $sex = $record['sex'];
            $dateOfBirth = $record['date_of_birth'];
            $age = $this->_getAge($dateOfBirth);
            
            $stdResults = $record['StdResult'];
            
            foreach ($stdResults as $result) {
                
                $resultCode = $result['result_code'];
                
                if (in_array($resultCode, $arrResultCodes)) {
                    
                    if ($sex =="1") {
                        
                        if ($age >0 &&$age <=14) {
                            $reportRow[$resultCode][0]['m'] += 1;
                            //$reportRow[$resultCode][0]
                        } else if ($age >=15 &&$age <=19) {
                            $reportRow[$resultCode][1]['m'] += 1;
                        } else if ($age >=20 &&$age <=24) {
                            $reportRow[$resultCode][2]['m'] += 1;
                        } else if ($age >=25 &&$age <=29) {
                            $reportRow[$resultCode][3]['m'] += 1;
                        } else if ($age >=30 &&$age <=34) {
                            $reportRow[$resultCode][4]['m'] += 1;
                            $reportRow[$resultCode][4]['mtot'] += 1;
                        } else if ($age >=35 &&$age <=39) {
                            $reportRow[$resultCode][5]['m'] += 1;
                        } else if ($age >=40 &&$age <=44) {
                            $reportRow[$resultCode][6]['m'] += 1;
                        } else if ($age >=45 &&$age <=49) {
                            $reportRow[$resultCode][7]['m'] += 1;
                        } else if ($age >=50) {
                            $reportRow[$resultCode][8]['m'] += 1;
                        }
                    } else {
                        if ($age >0 &&$age <=14) {
                            $reportRow[$resultCode][0]['f'] += 1;
                        } else if ($age >=15 &&$age <=19) {
                            $reportRow[$resultCode][1]['f'] += 1;
                        } else if ($age >=20 &&$age <=24) {
                            $reportRow[$resultCode][2]['f'] += 1;
                        } else if ($age >=25 &&$age <=29) {
                            $reportRow[$resultCode][3]['f'] += 1;
                        } else if ($age >=30 &&$age <=34) {
                            $reportRow[$resultCode][4]['f'] += 1;
                            $reportRow[$resultCode][4]['ftot'] += 1;
                        } else if ($age >=35 &&$age <=39) {
                            $reportRow[$resultCode][5]['f'] += 1;
                        } else if ($age >=40 &&$age <=44) {
                            $reportRow[$resultCode][6]['f'] += 1;
                        } else if ($age >=45 &&$age <=49) {
                            $reportRow[$resultCode][7]['f'] += 1;
                        } else if ($age >=50) {
                            $reportRow[$resultCode][8]['f'] += 1;
                        }
                    }
                } else {
                
                }
            }
        }
        
        $resultNames = array('No illness', 'HIV Positive', 'GC', 'Early Syphilis', 'Late Syphilis', 'Cong Syphilis', 'Herpes', 'Chlamydia', 'NGI/NSGI', 'Trichomoniasis', 'Warts', 'Pubic Lice', 'Scabies', 'Candida', 'Bacterial Vagino', 'Epididymitis', 'Molluscum', 'Opth. neonatorum', 'Other STD', 'Uncertain');
        
        //Keep the column valus of all STD results
        $arrTotCol = array();
        $arrTotCol[0] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[1] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[2] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[3] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[4] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[5] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[6] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[7] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[8] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[9] = array("m" => 0, "f" => 0, "gtot" => 0);
        
        //Keep all the column values except non venereal
        $arrNonVeneral[0] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[1] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[2] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[3] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[4] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[5] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[6] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[7] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[8] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[9] = array("m" => 0, "f" => 0, "gtot" => 0);
        
        foreach ($reportRow as $index => $row) {
            foreach ($row as $key => $val) {
                //Total venereal
                if ($index !=234) {
                    $arrTotCol[$key]['m'] = $arrTotCol[$key]['m'] +$val['m'];
                    $arrTotCol[$key]['f'] = $arrTotCol[$key]['f'] +$val['f'];
                    $arrTotCol[$key]['gtot'] = $arrTotCol[$key]['m'] +$arrTotCol[$key]['f'];
                }
                
                //Total non venereal
                if ($index ==234) {
                    
                    $arrNonVeneral[$key]['m'] = $arrNonVeneral[$key]['m'] +$val['m'];
                    $arrNonVeneral[$key]['f'] = $arrNonVeneral[$key]['f'] +$val['f'];
                    $arrNonVeneral[$key]['gtot'] = $arrNonVeneral[$key]['m'] +$arrNonVeneral[$key]['f'];
                }
            }
        
        }
        
        //Grand total of venereal
        $arrColNetTot = array("m" => 0, "f" => 0, "cgot" => 0);
        
        foreach ($arrTotCol as $col) {
            $arrColNetTot['m'] = $arrColNetTot['m'] +$col['m'];
            $arrColNetTot['f'] = $arrColNetTot['f'] +$col['f'];
            $arrColNetTot['cgot'] = $arrColNetTot['cgot'] +$col['gtot'];
        }
        
        //Grand total of non venereal
        $arrNonVerNetTot = array("m" => 0, "f" => 0, "cgot" => 0);
        
        foreach ($arrNonVeneral as $nonVerCol) {
            $arrNonVerNetTot['m'] = $arrNonVerNetTot['m'] +$nonVerCol['m'];
            $arrNonVerNetTot['f'] = $arrNonVerNetTot['f'] +$nonVerCol['f'];
            $arrNonVerNetTot['cgot'] = $arrNonVerNetTot['cgot'] +$nonVerCol['gtot'];
        }
        
        //Keep the column valus of all STD results
        $arrTotCol = array();
        $arrTotCol[0] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[1] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[2] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[3] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[4] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[5] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[6] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[7] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[8] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrTotCol[9] = array("m" => 0, "f" => 0, "gtot" => 0);
        
        //Keep all the column values except non venereal
        $arrNonVeneral[0] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[1] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[2] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[3] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[4] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[5] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[6] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[7] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[8] = array("m" => 0, "f" => 0, "gtot" => 0);
        $arrNonVeneral[9] = array("m" => 0, "f" => 0, "gtot" => 0);
        
        foreach ($reportRow as $index => $row) {
            foreach ($row as $key => $val) {
                //Total venereal
                if ($index !=234) {
                    $arrTotCol[$key]['m'] = $arrTotCol[$key]['m'] +$val['m'];
                    $arrTotCol[$key]['f'] = $arrTotCol[$key]['f'] +$val['f'];
                    $arrTotCol[$key]['gtot'] = $arrTotCol[$key]['m'] +$arrTotCol[$key]['f'];
                }
                
                //Total non venereal
                if ($index ==234) {
                    $arrNonVeneral[$key]['m'] = $arrNonVeneral[$key]['m'] +$val['m'];
                    $arrNonVeneral[$key]['f'] = $arrNonVeneral[$key]['f'] +$val['f'];
                    $arrNonVeneral[$key]['gtot'] = $arrNonVeneral[$key]['m'] +$arrNonVeneral[$key]['f'];
                }
            }
        
        }
        
        //Grand total of venereal
        $arrColNetTot = array("m" => 0, "f" => 0, "cgot" => 0);
        
        foreach ($arrTotCol as $col) {
            $arrColNetTot['m'] = $arrColNetTot['m'] +$col['m'];
            $arrColNetTot['f'] = $arrColNetTot['f'] +$col['f'];
            $arrColNetTot['cgot'] = $arrColNetTot['cgot'] +$col['gtot'];
        }
        
        //Grand total of non venereal
        $arrNonVerNetTot = array("m" => 0, "f" => 0, "cgot" => 0);
        
        foreach ($arrNonVeneral as $nonVerCol) {
            $arrNonVerNetTot['m'] = $arrNonVerNetTot['m'] +$nonVerCol['m'];
            $arrNonVerNetTot['f'] = $arrNonVerNetTot['f'] +$nonVerCol['f'];
            $arrNonVerNetTot['cgot'] = $arrNonVerNetTot['cgot'] +$nonVerCol['gtot'];
        }
        
        $resultNames = array('No illness', 'HIV Positive', 'GC', 'Early Syphilis', 'Late Syphilis', 'Cong Syphilis', 'Herpes', 'Chlamydia', 'NGI/NSGI', 'Trichomoniasis', 'Warts', 'Pubic Lice', 'Scabies', 'Candida', 'Bacterial Vagino', 'Epididymitis', 'Molluscum', 'Opth. neonatorum', 'Other STD', 'Uncertain');
        
        $data = array();
        
        $data[] = array("", "M", "F", "M", "F", "M", "F", "M", "F", "M", "F", "M", "F", "M", "F", "M", "F", "M", "F", "M", "F", "");
        
        $a = 0;
        $arr = array();
        foreach ($reportRow as $id => $row) {
           
            $arr[$id][0] = $arr[$id][0] +$row[$a]['m'];
            $arr[$id][1] = $arr[$id][0] +$row[$a]['f'];
            $totalMale = $row[0]['m'] +$row[1]['m'] +$row[2]['m'] +$row[3]['m'] +$row[4]['m'] +$row[5]['m'] +$row[6]['m'] +$row[7]['m'] +$row[8]['m'];
            $totalFemale = $row[0]['f'] +$row[1]['f'] +$row[2]['f'] +$row[3]['f'] +$row[4]['f'] +$row[5]['f'] +$row[6]['f'] +$row[7]['f'] +$row[8]['f'];
            $grandTotal = $totalMale +$totalFemale;
            
            $arrTemp = array();
            $arrTemp[] = $resultNames[$a];
            $arrTemp[] = $row[0]['m'];
            $arrTemp[] = $row[0]['f'];
            $arrTemp[] = $row[1]['m'];
            $arrTemp[] = $row[1]['f'];
            $arrTemp[] = $row[2]['m'];
            $arrTemp[] = $row[2]['f'];
            $arrTemp[] = $row[3]['m'];
            $arrTemp[] = $row[3]['f'];
            $arrTemp[] = $row[4]['m'];
            $arrTemp[] = $row[4]['f'];
            $arrTemp[] = $row[5]['m'];
            $arrTemp[] = $row[5]['f'];
            $arrTemp[] = $row[6]['m'];
            $arrTemp[] = $row[6]['f'];
            $arrTemp[] = $row[7]['m'];
            $arrTemp[] = $row[7]['f'];
            $arrTemp[] = $row[8]['m'];
            $arrTemp[] = $row[8]['f'];
            $arrTemp[] = $totalMale;
            $arrTemp[] = $totalFemale;
            $arrTemp[] = $grandTotal;
            
            $data[] = $arrTemp;
            $a++;
        }
        
        $arrTemp2[] = "Total Venereal ";
        
        /* for ($a=0; $a<count($arrTotCol);$a++) {
            $arrTemp2[] = $arrTotCol[$a]['m'];
            $arrTemp2[] = $arrTotCol[$a]['f'];
           // echo $a;
        }*/
        $arrTemp2[] = $arrTotCol[0]['m'];
        $arrTemp2[] = $arrTotCol[0]['f'];
        $arrTemp2[] = $arrTotCol[1]['m'];
        $arrTemp2[] = $arrTotCol[1]['f'];
        $arrTemp2[] = $arrTotCol[2]['m'];
        $arrTemp2[] = $arrTotCol[2]['f'];
        $arrTemp2[] = $arrTotCol[3]['m'];
        $arrTemp2[] = $arrTotCol[3]['f'];
        $arrTemp2[] = $arrTotCol[4]['m'];
        $arrTemp2[] = $arrTotCol[4]['f'];
        $arrTemp2[] = $arrTotCol[5]['m'];
        $arrTemp2[] = $arrTotCol[5]['f'];
        $arrTemp2[] = $arrTotCol[6]['m'];
        $arrTemp2[] = $arrTotCol[6]['f'];
        $arrTemp2[] = $arrTotCol[7]['m'];
        $arrTemp2[] = $arrTotCol[7]['f'];
        $arrTemp2[] = $arrTotCol[8]['m'];
        $arrTemp2[] = $arrTotCol[8]['f'];
        
        $arrTemp2[] = $arrColNetTot['m'];
        $arrTemp2[] = $arrColNetTot['f'];
        $arrTemp2[] = $arrColNetTot['cgot'];
        
        $data[] = $arrTemp2;
        
        $arrTemp3[] = "Total Non Venereal ";
        $arrTemp3[] = $arrNonVeneral[0]['m'];
        $arrTemp3[] = $arrNonVeneral[0]['f'];
        $arrTemp3[] = $arrNonVeneral[1]['m'];
        $arrTemp3[] = $arrNonVeneral[1]['f'];
        $arrTemp3[] = $arrNonVeneral[2]['f'];
        $arrTemp3[] = $arrNonVeneral[2]['m'];
        $arrTemp3[] = $arrNonVeneral[3]['m'];
        $arrTemp3[] = $arrNonVeneral[3]['f'];
        $arrTemp3[] = $arrNonVeneral[4]['m'];
        $arrTemp3[] = $arrNonVeneral[4]['f'];
        $arrTemp3[] = $arrNonVeneral[5]['m'];
        $arrTemp3[] = $arrNonVeneral[5]['f'];
        $arrTemp3[] = $arrNonVeneral[6]['m'];
        $arrTemp3[] = $arrNonVeneral[6]['f'];
        $arrTemp3[] = $arrNonVeneral[7]['m'];
        $arrTemp3[] = $arrNonVeneral[7]['f'];
        $arrTemp3[] = $arrNonVeneral[8]['m'];
        $arrTemp3[] = $arrNonVeneral[8]['f'];
        
        $arrTemp3[] = $arrNonVerNetTot['m'];
        $arrTemp3[] = $arrNonVerNetTot['f'];
        $arrTemp3[] = $arrNonVerNetTot['cgot'];
        
        $data[] = $arrTemp3;
        
        $arrTemp4[] = "Grand Total  ";
        $arrTemp4[] = $arrTotCol[0]['m'] +$arrNonVeneral[0]['m'];
        $arrTemp4[] = $arrTotCol[0]['f'] +$arrNonVeneral[0]['f'];
        $arrTemp4[] = $arrTotCol[1]['m'] +$arrNonVeneral[1]['m'];
        $arrTemp4[] = $arrTotCol[1]['f'] +$arrNonVeneral[1]['f'];
        $arrTemp4[] = $arrTotCol[2]['m'] +$arrNonVeneral[2]['m'];
        $arrTemp4[] = $arrTotCol[2]['f'] +$arrNonVeneral[2]['f'];
        $arrTemp4[] = $arrTotCol[3]['m'] +$arrNonVeneral[3]['m'];
        $arrTemp4[] = $arrTotCol[3]['f'] +$arrNonVeneral[3]['f'];
        ;
        $arrTemp4[] = $arrTotCol[4]['m'] +$arrNonVeneral[4]['m'];
        $arrTemp4[] = $arrTotCol[4]['f'] +$arrNonVeneral[4]['f'];
        $arrTemp4[] = $arrTotCol[5]['m'] +$arrNonVeneral[5]['m'];
        $arrTemp4[] = $arrTotCol[5]['f'] +$arrNonVeneral[5]['f'];
        $arrTemp4[] = $arrTotCol[6]['m'] +$arrNonVeneral[6]['m'];
        $arrTemp4[] = $arrTotCol[6]['f'] +$arrNonVeneral[6]['f'];
        $arrTemp4[] = $arrTotCol[7]['m'] +$arrNonVeneral[7]['m'];
        $arrTemp4[] = $arrTotCol[7]['f'] +$arrNonVeneral[7]['f'];
        $arrTemp4[] = $arrTotCol[8]['m'] +$arrNonVeneral[8]['m'];
        $arrTemp4[] = $arrTotCol[8]['f'] +$arrNonVeneral[8]['f'];
        
        $arrTemp4[] = $arrColNetTot['m'] +$arrNonVerNetTot['m'];
        $arrTemp4[] = $arrColNetTot['m'] +$arrNonVerNetTot['f'];
        $arrTemp4[] = $arrColNetTot['cgot'] +$arrNonVerNetTot['cgot'];
        
        $data[] = $arrTemp4;
        
        $header = array("", '0-14', '0-14', '15-19', '15-19', '20-24', '20-24', '25-29', '25-29', '30-34', '30-34', '35-39', '35-39', '40-44', '40-44', '45-49', '45-49', '50+', '50+', 'Total', 'Total', 'Grand Total ');
        $reportTitle = "New Episodes of STD Recorded Quarter " .$quarter ." in " .$year;
        $fileName = "New_Episodes_Of_STD_Quarter_" .$quarter ."_" .$year;
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName, 8);
        }
        die();
    }

    /**
     * Create CSW report
     * @param $request
     */
    
    public function executeCreateCswtReport(sfWebRequest $request) {

    }

    /**
     * Display CSW report
     * @param $request
     */
    public function executeViewCswReport(sfWebRequest $request) {

        $year = $request->getParameter('cmbYear');
        
        $report = new Report();
        $arrCsw = $report->getCswReportData($year);
        
        $arrYear = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0);
        $total = 0;
        
        foreach ($arrCsw as $csw) {
            
            $arrDate = explode("-", $csw['registered_date']);
            $month = ltrim($arrDate[1], "0");
            
            if (array_key_exists($month, $arrYear)) {
                $arrYear[$month] = $arrYear[$month] +1;
                $total = $total +1;
            }
        }
        
        $this->total = $total;
        $this->arrData = $arrYear;
        $this->year = $year;
    }

    /**
     * Export CSW reports
     * @param $request
     */
    public function executeExportCswReport(sfWebRequest $request) {

        $year = $request->getParameter('cmbYear');
        $reportTye = $request->getParameter('rep');
        
        $report = new Report();
        $arrCsw = $report->getCswReportData($year);
        
        $arrYear = array('1' => 0, '2' => 0, '3' => 0, '4' => 0, '5' => 0, '6' => 0, '7' => 0, '8' => 0, '9' => 0, '10' => 0, '11' => 0, '12' => 0);
        $total = 0;
        
        foreach ($arrCsw as $csw) {
            
            $arrDate = explode("-", $csw['registered_date']);
            $month = ltrim($arrDate[1], "0");
            
            if (array_key_exists($month, $arrYear)) {
                $arrYear[$month] = $arrYear[$month] +1;
                $total = $total +1;
            }
        }
        
        $arrYear['13'] = $total;
        
        $header = array('Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec', 'Total');
        $data = array();
        $reportTitle = "Clinic Attendees of Commercial Sex Workers in " .$year;
        $fileName = "CSW_" .$year;
        
        $data[] = $arrYear;
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName, 10);
        }
        die();
    
    }

    private function _getAge($dob) {

        list($BirthYear, $BirthMonth, $BirthDay) = explode("-", $dob);
        // Find the differences
        $YearDiff = date("Y") -$BirthYear;
        $MonthDiff = date("m") -$BirthMonth;
        $DayDiff = date("d") -$BirthDay;
        // If the birthday has not occured this year
        if ($DayDiff <0 ||$MonthDiff <0)
            $YearDiff--;
        return $YearDiff;
    }

    /**
     * Create occupational categories of clinic attendees graphs
     *
     */
    public function executeCreateOccupationGraphRport(sfWebRequest $request) {

    }

    /**
     * View occupational categories of clinic attendees graphs
     *
     */
    public function executeViewOccupationGraph(sfWebRequest $request) {

        $year = $request->getParameter('txtYear');      
        
        $objReport = new Report();
        $reportData = $objReport->createOccupationReport($year);
        
        $occupationArray = array('Male' => array('CSW' => 0, 'Student' => 0, 'Retired' => 0, 'Unemployed' => 0, 'Employed' => 0), 'Female' => array('CSW' => 0, 'Student' => 0, 'Retired' => 0, 'Unemployed' => 0, 'Employed' => 0));
        
        foreach ($reportData as $record) {
            $sex = $record['sex'];
            if ($sex =="1") {
                
                if ($record['occupation'] =="CSW") {
                    $occupationArray['Male']['CSW'] += 1;
                } else if ($record['occupation'] =="Student") {
                    $occupationArray['Male']['Student'] += 1;
                } else if ($record['occupation'] =="Retired") {
                    $occupationArray['Male']['Retired'] += 1;
                } else if ($record['occupation'] =="Unemployed") {
                    $occupationArray['Male']['Unemployed'] += 1;
                } else {
                    $occupationArray['Male']['Employed'] += 1;
                }
            } else {
                if ($record['occupation'] =="CSW") {
                    $occupationArray['Female']['CSW'] += 1;
                } else if ($record['occupation'] =="Student") {
                    $occupationArray['Female']['Student'] += 1;
                } else if ($record['occupation'] =="Retired") {
                    $occupationArray['Female']['Retired'] += 1;
                } else if ($record['occupation'] =="Unemployed") {
                    $occupationArray['Female']['Unemployed'] += 1;
                } else {
                    $occupationArray['Female']['Employed'] += 1;
                }
            }
        }
        
        $this->chartData = $occupationArray;
        $this->occupation = $occupation;
        $this->year = $year;
    }

    /**
     * Create STD distribution graph
     *
     */
    public function executeCreateStdDistributionReport(sfWebRequest $request) {

    }

    /**
     * View STD distribution graph
     *
     */
    public function executeViewStdDistributionGraph(sfWebRequest $request) {

        $std = $request->getParameter('etio');
        
        $year = $request->getParameter('txtYear');
        //$year = date("Y", strtotime($year));
        
        $objReport = new Report();
        $reportData = $objReport->createSyphilisDistributionReport($year);
        
        $arrayStd = array("Male" => array('m' => 0, "f" => 0), "Female" => array('m' => 0, "f" => 0));
        
        foreach ($reportData as $record) {
            
            $sex = $record['sex'];
            $stdResults = $record['StdResult'];
            
            foreach ($stdResults as $result) {
                
                if ($std =="1") {
                    
                    if ($result['result_code'] =="237" ||$result['result_code'] =="238" ||$result['result_code'] =="239") {
                        $stdName = "Syphilis";
                        if ($sex =="1") {
                            $arrayStd["Male"]['m'] += 1;
                        } else {
                            $arrayStd["Female"]['f'] += 1;
                        }
                    }
                } else if ($std =="2") {
                    
                    if ($result['result_code'] =="240") {
                        $stdName = "Herpes";
                        if ($sex =="1") {
                            $arrayStd["Male"]['m'] += 1;
                        } else {
                            $arrayStd["Female"]['f'] += 1;
                        }
                    }
                } else if ($std =="3") {
                
                }
            }
        }
        
        $this->chartData = $arrayStd;
        $this->stdName = $stdName;
        $this->year = $year;
    }

    /**
     * Create civil status of clinic attendees graph
     *
     */
    public function executeCreateMaritalStatusGraphReport(sfWebRequest $request) {

    }

    /**
     * View occupational civil status of clinic attendees graph
     *
     */
    public function executeViewMaritalStatusGraph(sfWebRequest $request) {

        $year = $request->getParameter('txtYear');
        
        //$year = date("Y", strtotime($year));
        
        $objReport = new Report();
        $reportData = $objReport->createOccupationReport($year);
        
        $maritalStatus = array('Male' => array('Married' => 0, 'Single' => 0, 'SepDivo' => 0, 'Widowed' => 0, 'LivingTogether' => 0, "NotKnown" => 0), 'Female' => array('Married' => 0, 'Single' => 0, 'SepDivo' => 0, 'Widowed' => 0, 'LivingTogether' => 0, "NotKnown" => 0));
        
        foreach ($reportData as $record) {
            $sex = $record['sex'];
            if ($sex =="1") {
                
                if ($record['marital_status'] =="1") {
                    $maritalStatus['Male']['Married'] += 1;
                } else if ($record['marital_status'] =="2") {
                    $maritalStatus['Male']['Single'] += 1;
                } else if ($record['marital_status'] =="3") {
                    $maritalStatus['Male']['SepDivo'] += 1;
                } else if ($record['marital_status'] =="4") {
                    $maritalStatus['Male']['Widowed'] += 1;
                } else if ($record['marital_status'] =="5") {
                    $maritalStatus['Male']['LivingTogether'] += 1;
                } else {
                    $maritalStatus['Male']['NotKnown'] += 1;
                }
            } else {
                if ($record['marital_status'] =="1") {
                    $maritalStatus['Female']['Married'] += 1;
                } else if ($record['marital_status'] =="2") {
                    $maritalStatus['Female']['Single'] += 1;
                } else if ($record['marital_status'] =="3") {
                    $maritalStatus['Female']['SepDivo'] += 1;
                } else if ($record['marital_status'] =="4") {
                    $maritalStatus['Female']['Widowed'] += 1;
                } else if ($record['marital_status'] =="5") {
                    $maritalStatus['Female']['LivingTogether'] += 1;
                } else {
                    $maritalStatus['Female']['NotKnown'] += 1;
                }
            }
        }
        
        $this->chartData = $maritalStatus;
        $this->year = $year;
    }

    public function executeCreateReasonForAttendanceGraphReport(sfWebRequest $request) {

    }

    public function executeViewReasonForAttendanceGraph(sfWebRequest $request) {

        $year = $request->getParameter('txtYear');
        
        //$year = date("Y", strtotime($year));
        
        $objReport = new Report();
        $reportData = $objReport->createReasonForAttendanceReport($year);
        
        $reasons = array('Male' => array('contact' => 0, 'volantary' => 0, 'court' => 0, 'other' => 0), 'Female' => array('contact' => 0, 'volantary' => 0, 'court' => 0, 'other' => 0));
        
        foreach ($reportData as $record) {
            
            $sex = $record['sex'];
            $attendanceReasons = $record['ClinicReason'];
            
            foreach ($attendanceReasons as $attendance) {
                
                if ($sex =="1") {
                    
                    if (! empty($attendance['contact'])) {
                        $reasons['Male']['contact'] += 1;
                    } else if (! empty($attendance['volantary'])) {
                        $reasons['Male']['volantary'] += 1;
                    } else if (! empty($attendance['court'])) {
                        $reasons['Male']['court'] += 1;
                    } else {
                        $reasons['Male']['other'] += 1;
                    }
                } else {
                    
                    if (! empty($attendance['contact'])) {
                        $reasons['Female']['contact'] += 1;
                    } else if (! empty($attendance['volantary'])) {
                        $reasons['Female']['volantary'] += 1;
                    } else if (! empty($attendance['court'])) {
                        $reasons['Female']['court'] += 1;
                    } else {
                        $reasons['Female']['other'] += 1;
                    }
                }
            }
        }
        $this->chartData = $reasons;
        $this->year = $year;
    
    }

    /**
     * Create clinic attendees report
     *
     */
    public function executeCreateClinicAttendeeReport(sfWebRequest $request) {

    }

    /**
     * Display clinic attendees report
     *
     */
    public function executeViewClinicAttendeeReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        
        //Registered patients for the quarter
        $patients = $this->_getPatientReportData($request);
        $arrPatients = array('male' => 0, 'female' => 0, "tot" => 0);
        
        if (! empty($patients)) {
            
            foreach ($patients as $patient) {
                
                if ($patient['sex'] =='1') {
                    $arrPatients['male'] = $arrPatients['male'] +1;
                } else {
                    $arrPatients['female'] = $arrPatients['female'] +1;
                
                }
                
                $arrPatients['tot'] = $arrPatients['tot'] +1;
            }
        }
        
        //No of visits for the quarter
        $report = new Report();
        $clicVisits = $report->getNoOfClinicVisitReport($year, $quarter);
        $arrVisits = array('male' => 0, 'female' => 0, "tot" => 0);
        
        if (! empty($clicVisits)) {
            
            foreach ($clicVisits as $visit) {
                
                if ($visit['sex'] =='1') {
                    $arrVisits['male'] = $arrVisits['male'] +count($visit['Visit']);
                } else {
                    $arrVisits['female'] = $arrVisits['female'] +count($visit['Visit']);
                }
                
                $arrVisits['tot'] = $arrVisits['male'] +$arrVisits['female'];
            }
        }
        
        //Positive STD patients
        $positiveStds = $report->getStdPositiveReport($year, $quarter);
        $arrStds = array('male' => 0, 'female' => 0, "tot" => 0);
        
        if (! empty($positiveStds)) {
            
            foreach ($positiveStds as $std) {
                
                if ($std['sex'] =='1') {
                    $arrStds['male'] = $arrStds['male'] +count($std['Episode']);
                } else {
                    $arrStds['female'] = $arrStds['female'] +count($std['Episode']);
                }
                
                $arrStds['tot'] = $arrStds['male'] +$arrStds['female'];
            }
        
        }
        
        $this->registerdPatients = $arrPatients;
        $this->visits = $arrVisits;
        $this->stdPatients = $arrStds;
        $this->year = $year;
        $this->quarter = $quarter;
    
    }

    /**
     * Export clinic attendee reports
     * @param r $request
     * @return unknown_type
     */
    public function executeExportClinicAttendeeReport(sfWebRequest $request) {

        $reportTye = $request->getParameter('rep');
        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        
        //Registered patients for the quarter
        $patients = $this->_getPatientReportData($request);
        $arrPatients = array('male' => 0, 'female' => 0, "tot" => 0);
        
        if (! empty($patients)) {
            
            foreach ($patients as $patient) {
                
                if ($patient['sex'] =='1') {
                    $arrPatients['male'] = $arrPatients['male'] +1;
                } else {
                    $arrPatients['female'] = $arrPatients['female'] +1;
                
                }
                
                $arrPatients['tot'] = $arrPatients['tot'] +1;
            }
        }
        
        //No of visits for the quarter
        $report = new Report();
        $clicVisits = $report->getNoOfClinicVisitReport($year, $quarter);
        $arrVisits = array('male' => 0, 'female' => 0, "tot" => 0);
        
        if (! empty($clicVisits)) {
            
            foreach ($clicVisits as $visit) {
                
                if ($visit['sex'] =='1') {
                    $arrVisits['male'] = $arrVisits['male'] +count($visit['Visit']);
                } else {
                    $arrVisits['female'] = $arrVisits['female'] +count($visit['Visit']);
                }
                
                $arrVisits['tot'] = $arrVisits['male'] +$arrVisits['female'];
            }
        }
        
        //Positive STD patients
        $positiveStds = $report->getStdPositiveReport($year, $quarter);
        $arrStds = array('male' => 0, 'female' => 0, "tot" => 0);
        
        if (! empty($positiveStds)) {
            
            foreach ($positiveStds as $std) {
                
                if ($std['sex'] =='1') {
                    $arrStds['male'] = $arrStds['male'] +count($std['Episode']);
                } else {
                    $arrStds['female'] = $arrStds['female'] +count($std['Episode']);
                }
                
                $arrStds['tot'] = $arrStds['male'] +$arrStds['female'];
            }
        
        }
        
        $header = array('', 'Males', "Females", "Total");
        $data = array();
        $reportTitle = "Clinic Attendees - Quarter {$quarter} in {$year}";
        $fileName = "Clinic_Attendees_Quarter_" .$quarter ."_" .$year;
        
        $data[] = array('Newly Registered', $arrPatients['male'], $arrPatients['female'], $arrPatients['tot']);
        $data[] = array('Newly Registered with STD', $arrStds['male'], $arrStds['female'], $arrStds['tot']);
        $data[] = array('Total number of visits', $arrVisits['male'], $arrVisits['female'], $arrVisits['tot']);
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName, 45);
        }
        die();
    
    }

    /**
     * Get all the patients records for a particualr quarter
     *
     */
    private function _getPatientReportData(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        
        $objReport = new Report();
        $reportData = $objReport->getPatientReport($year, $quarter);
        
        return $reportData;
    }

    /**
     * Create PDF files
     * To create PDF files ExportPdfUtility class is used.
     * ExportPdfUtility class inherits from the TCPDF library class
     * TCDF is a libray which is uesd for creating PDF files (http://www.tecnick.com)
     * @param $arrData, $header, $reportTitle 
     */
    public function _createPDFFile($arrData, $header, $reportTitle, $fileName, $cellWidth = 50) {

        // create new PDF document
        $pdf = new ExportPdfUtility(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
        
        $pdf->SetAuthor('PIMS');
        $pdf->SetTitle('Report');
        $pdf->SetSubject('Report');
        $pdf->setCommonSettings($pdf);
        
        // build the array of data that needs to be published in a pdf
        $value = '';
        $data = $arrData;
        
        // iterate the appoinment variable and build array
        foreach ($repData as $key => $val) {
            
            if ($val !=null) {
                array_push($data, array($key, $val));
            }
        }
        
        $html = "<h3 align='center'>" .$reportTitle ."</h3><br /> ";
        
        // Print text using writeHTMLCell()
        $pdf->writeHTMLCell($w = 0, $h = 0, $x = '', $y = '', $html, $border = 0, $ln = 1, $fill = 0, $reseth = true, $align = 'C', $autopadding = true);
        $pdf->ColoredTable($header, $data, $cellWidth);
        
        //Close and output PDF document
        $fileName = $fileName .".pdf";
        $pdf->Output($fileName, 'I');
    }

    //Create the CSV report and export 
    private function _creatCSVFile($header, $data, $fileName) {

        $csvReport = new CSVReportUtility($header, $data, $fileName);
        $csvReport->exportCSVData();
    }

    /**
     * Create HIV positive report
     * @param r $request
     * @return unknown_type
     */
    public function executeCreateHivPositiveReport(sfWebRequest $request) {

    }

    /**
     * Display HIV positive report
     * @param r $request
     * @return unknown_type
     */
    public function executeViewHivPositiveReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        
        $report = new Report();
        $hivPositivePatients = $report->createHivPositiveReport($year, $quarter);
        
        $arrHiv = array("m" => 0, "f" => 0, "tot" => 0);
        
        foreach ($hivPositivePatients as $hivPositivePatient) {
            
            if ($hivPositivePatient['sex'] =="1") {
                $arrHiv['m'] = $arrHiv['m'] +1;
            } else {
                $arrHiv['f'] = $arrHiv['f'] +1;
            }
            
            $arrHiv['tot'] = $arrHiv['tot'] +1;
        }
        
        $this->arrHiv = $arrHiv;
        $this->year = $year;
        $this->quarter = $quarter;
    
    }

    /**
     * Export HIV positive report
     * @param $request
     * @return unknown_type
     */
    public function executeExportHivPositiveReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        $reportTye = $request->getParameter('rep');
        
        $report = new Report();
        $hivPositivePatients = $report->createHivPositiveReport($year, $quarter);
        
        $arrHiv = array("m" => 0, "f" => 0, "tot" => 0);
        
        foreach ($hivPositivePatients as $hivPositivePatient) {
            
            if ($hivPositivePatient['sex'] =="1") {
                $arrHiv['m'] = $arrHiv['m'] +1;
            } else {
                $arrHiv['f'] = $arrHiv['f'] +1;
            }
            
            $arrHiv['tot'] = $arrHiv['tot'] +1;
        }
        
        $header = array('Males', "Females", "Total");
        $data = array();
        $reportTitle = "HIV Positive of STD Clinic Attendees - Quarter {$quarter} in {$year}";
        $fileName = "HIV_Positive_Quarter_" .$quarter ."_" .$year;
        
        $data[] = $arrHiv;
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName, 35);
        }
        die();
    
    }

    /**
     * Create HIV positive detailed report
     * @param $request
     * @return unknown_type
     */
    public function executeCreateHivPositiveDetailedReport(sfWebRequest $request) {

    }

    /**
     * Create HIV positive detailed report
     * @param $request
     * @return unknown_type
     */
    public function executeViewHivPositiveDetailedReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        
        $report = new Report();
        $hivPositivePatients = $report->createHivPositiveReport($year, $quarter);
        
        $arrHivPatients = array();
        
        foreach ($hivPositivePatients as $hivPositivePatient) {
            $temp = array();
            $temp[] = $hivPositivePatient['patient_no'];
            $temp[] = base64_decode($hivPositivePatient['first_name']);
            $temp[] = base64_decode($hivPositivePatient['last_name']);
            $temp[] = $hivPositivePatients['sex'] =="1" ? "Male" : "Female";
            $temp[] = base64_decode($hivPositivePatient['current_address']);
            $temp[] = base64_decode($hivPositivePatient['permanent_address']);
            $temp[] = base64_decode($hivPositivePatient['contact_address']);
            $temp[] = base64_decode($hivPositivePatient['telephone1']);
            $temp[] = $hivPositivePatient['occupation'];
            
            $arrHivPatients[] = $temp;
        }
        
        $this->hivPositivePatients = $arrHivPatients;
        $this->year = $year;
        $this->quarter = $quarter;
    }

    /**
     * Export HIV positive detailed report
     * @param $request
     * @return unknown_type
     */
    public function executeExportHivPositiveDetailedReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        $reportTye = $request->getParameter('rep');
        
        $report = new Report();
        $hivPositivePatients = $report->createHivPositiveReport($year, $quarter);
        
        $arrHivPatients = array();
        
        foreach ($hivPositivePatients as $hivPositivePatient) {
            $temp = array();
            $temp[] = $hivPositivePatient['patient_no'];
            $temp[] = base64_decode($hivPositivePatient['first_name']) ." " .base64_decode($hivPositivePatient['last_name']);
            $temp[] = $hivPositivePatients['sex'] =="1" ? "Male" : "Female";
            $temp[] = base64_decode($hivPositivePatient['current_address']);
            $temp[] = base64_decode($hivPositivePatient['permanent_address']);
            $temp[] = base64_decode($hivPositivePatient['contact_address']);
            $temp[] = base64_decode($hivPositivePatient['telephone1']);
            $temp[] = $hivPositivePatient['occupation'];
            
            $arrHivPatients[] = $temp;
        }
        
        $header = array('PatientNo', "Name", "Gender", "Cur Add", "Per Add", "Con Add", "Tel", "Occupation");
        
        $reportTitle = "HIV Positive of STD Clinic Attendees - Quarter {$quarter} in {$year}";
        $fileName = "HIV_Positive_Quarter_" .$quarter ."_" .$year;
        
        $data = $arrHivPatients;
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName, 25);
        }
        die();
    }

    /**
     * Create HIV distribution report
     * @param $request
     * @return unknown_type
     */
    public function executeCreateHivDistributionReport(sfWebRequest $request) {

    }

    /**
     * Display HIV distribution report
     * @param $request
     * @return unknown_type
     */
    public function executeViewHivDistributionReport(sfWebRequest $request) {

        $fromYear = $request->getParameter('cmbFromYear');
        $toYear = $request->getParameter('cmbToYear');
        
        $report = new Report();
        $patients = $report->getHivDistributionReportData($fromYear, $toYear);
        
        $years = array();
        
        foreach ($patients as $patient) {
            $arrYear = explode("-", $patient['registered_date']);
            
            $key = $arrYear[0];
            
            if (! array_key_exists($key, $years)) {
                $years[$key] = array('m' => 0, "f" => 0, "tot" => 0);
            
            } else {
            
            }
            
            if ($patient['sex'] =="1") {
                
                $years[$key]['m'] = $years[$key]['m'] +1;
            
            } else {
                
                $years[$key]['f'] = $years[$key]['f'] +1;
            }
            
            $years[$key]['tot'] = $years[$key]['tot'] +1;
        }
        
        $this->arrYears = $years;
        $this->from = $fromYear;
        $this->to = $toYear;
    }

    /**
     * Export HIV distribution report
     * @param $request
     * @return unknown_type
     */
    public function executeExportHivDistributionReport(sfWebRequest $request) {

        $fromYear = $request->getParameter('cmbFromYear');
        $toYear = $request->getParameter('cmbToYear');
        $reportTye = $request->getParameter('rep');
        
        $report = new Report();
        $patients = $report->getHivDistributionReportData($fromYear, $toYear);
        
        $years = array();
        
        foreach ($patients as $patient) {
            $arrYear = explode("-", $patient['registered_date']);
            
            $key = $arrYear[0];
            
            if (! array_key_exists($key, $years)) {
                $years[$key] = array('m' => 0, "f" => 0, "tot" => 0);
            
            } else {
            
            }
            
            if ($patient['sex'] =="1") {
                
                $years[$key]['m'] = $years[$key]['m'] +1;
            
            } else {
                
                $years[$key]['f'] = $years[$key]['f'] +1;
            }
            
            $years[$key]['tot'] = $years[$key]['tot'] +1;
        }
        
        $data = array();
        $totMale = 0;
        $totFemale = 0;
        $grandTotal = 0;
        foreach ($years as $key => $val) {
            
            $temp = array();
            $temp[] = $key;
            $temp[] = $val['m'];
            $temp[] = $val['f'];
            $temp[] = $val['tot'];
            
            $totMale = $totMale +$val['m'];
            $totFemale = $totFemale +$val['f'];
            $grandTotal = $grandTotal +$val['tot'];
            
            $data[] = $temp;
        }
        
        $data[] = array('Total', $totMale, $totFemale, $grandTotal);
        
        $header = array('', 'Male', "Female", "Total");
        
        $reportTitle = "HIV Distribution From  {$fromYear} to {$toYear}";
        $fileName = "HIV_Distribution_" .$fromYear ."_" .$toYear;
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName, 25);
        }
        die();
    }

    /**
     * Create education report
     * @param $request
     */
    public function executeCreateStdEpisodeOfForeignersReport(sfWebRequest $request) {

    }

    /**
     * View education report
     * @param $request
     */
    public function executeViewStdEpisodeOfForeignersReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        
        $report = new Report();
        $repData = $report->createStdEpisodeReportOfForeigners($year, $quarter);
        
        $arrNationality = array();
        
        foreach ($repData as $data) {
            
            if ($data['nationality'] !="0" &&! empty($data['nationality'])) {
                
                $key = strtolower($data['nationality']);
                
                if (array_key_exists($key, $arrNationality)) {
                    
                    $arrNationality[$key] = $arrNationality[$key] +1;
                } else {
                    
                    $arrNationality[$key] = 1;
                }
            }
        }
        
        $this->nationalties = $arrNationality;
        $this->quarter = $quarter;
        $this->year = $year;
    
    }

    /**
     * Export nationality reports
     * @param r $request
     * @return unknown_type
     */
    public function executeExportStdEpisodeOfForeignersReport(sfWebRequest $request) {

        $quarter = $request->getParameter('cmbQuarter');
        $year = $request->getParameter('cmbYear');
        $reportTye = $request->getParameter('rep');
        
        $report = new Report();
        $repData = $report->createStdEpisodeReportOfForeigners($year, $quarter);
        
        $arrNationality = array();
        
        foreach ($repData as $data) {
            
            if ($data['nationality'] !="0" &&! empty($data['nationality'])) {
                
                $key = strtolower($data['nationality']);
                
                if (array_key_exists($key, $arrNationality)) {
                    
                    $arrNationality[$key] = $arrNationality[$key] +1;
                } else {
                    
                    $arrNationality[$key] = 1;
                }
            }
        }
        
        $header = array('Country', "Total");
        $data = array();
        $reportTitle = "STD Episodes of Foreigners - Quarter {$quarter} in {$year}";
        $fileName = "STD_Episodes_of_Foreigners_Quarter_" .$quarter ."_" .$year;
        
        foreach ($arrNationality as $key => $value) {
            
            $temp = array();
            $temp[] = ucfirst($key);
            $temp[] = $value;
            
            $data[] = $temp;
        }
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName);
        }
        die();
    }
    
	/**
     * Create Defaulted patients
     * @param $request
     */
    
    public function executeCreateDefaultPatientReport(sfWebRequest $request) {

    }
    
	/**
     * Display Defaulted patients
     * @param $request
     */
    
    public function executeViewDefaultPatientReport(sfWebRequest $request) {

        $fromDate = $request->getParameter('txtFromDate');
        $toDate = $request->getParameter('txtToDate');
        $noOfDays = $request->getParameter('txtNo');
        
        $strFromdate = strtotime($fromDate);
        $strToDate = strtotime($toDate);
        
        //Validate dates        
        if ($strFromdate >$strToDate) {
            
            $this->getUser()->setFlash('error', 'From date can not be greater than To date');
            $this->redirect('report/createDefaultPatientReport');
        }
        
        $visit = new Visit();
        $objDefaultPatients = $visit->traceDefaultPatients($fromDate, $toDate);
        $defaultPatients = array();
        
        foreach ($objDefaultPatients as $objDefaultPatient) {
            
            $temp = array();
            
            $temp[0] = $objDefaultPatient->getPatientNo();
            $temp[1] = base64_decode($objDefaultPatient->getPatient()->getFirstName()) ." " .base64_decode($objDefaultPatient->getPatient()->getLastName());
            $temp[2] = $objDefaultPatient->getPatient()->getSex() =="1" ? "Male" : "Female";
            $temp[3] = base64_decode($objDefaultPatient->getPatient()->getCurrentAddress());
            $temp[4] = base64_decode($objDefaultPatient->getPatient()->getTelephone1());
            $temp[5] = $objDefaultPatient->getEpisodeNo();            
            $temp[6] = $objDefaultPatient->getAppointed_date();
            $temp[7] = $this->_dateDiff($objDefaultPatient->getAppointed_date(), $toDate);
            
            $defaultPatients[] = $temp;
        }
       
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->defaultPatients = $defaultPatients;
    }
    
     /**
     * Export reports of default patients
     * @param r $request
     * @return unknown_type
     */
public function executeExportDefaultPatients(sfWebRequest $request) {

        $fromDate = $request->getParameter('fromDate');
        $toDate = $request->getParameter('toDate');
        $reportTye = $request->getParameter('rep');
        
        $strFromdate = strtotime($fromDate);
        $strToDate = strtotime($toDate);
        
        //Validate dates        
        if ($strFromdate >$strToDate) {
            
            $this->getUser()->setFlash('error', 'From date can not be greater than To date');
            $this->redirect('consultancy/traceDefaultPatients');
        }
        
        $visit = new Visit();
        $objDefaultPatients = $visit->traceDefaultPatients($fromDate, $toDate);
        $defaultPatients = array();
        
        foreach ($objDefaultPatients as $objDefaultPatient) {
            
            $temp = array();
            
            $temp[0] = $objDefaultPatient->getPatientNo();
            $temp[1] = base64_decode($objDefaultPatient->getPatient()->getFirstName()) ." " .base64_decode($objDefaultPatient->getPatient()->getLastName());
            $temp[2] = $objDefaultPatient->getPatient()->getSex() =="1" ? "Male" : "Female";
            $temp[3] = base64_decode($objDefaultPatient->getPatient()->getCurrentAddress());
            $temp[4] = base64_decode($objDefaultPatient->getPatient()->getTelephone1());
            $temp[5] = $objDefaultPatient->getEpisodeNo();            
            $temp[6] = $objDefaultPatient->getAppointed_date();
            $temp[7] = $this->_dateDiff($objDefaultPatient->getAppointed_date(), $toDate);
            
            $defaultPatients[] = $temp;
        }
        
        $header = array('Patient No', 'Name', "Gender","Address", "Telephone", "Episode No", "App Date", "Delayed Days");
        $data = $defaultPatients;
        $reportTitle = "Default Patients from " .$fromDate ." to " .$toDate;
        $fileName = "Defalut Patients_" .$fromDate ."_" .$toDate;
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName, 23);
        }
        die();
    }
    
    //Get the date difference 
    private function _dateDiff($start, $end) {

        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        
        $diff = $end_ts -$start_ts;
        
        return round($diff /86400);
    
    }
}
