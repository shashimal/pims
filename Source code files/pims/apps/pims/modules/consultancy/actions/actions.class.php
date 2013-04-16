<?php
/**
 * Consultancy actions.
 * All the functions of the registration module are handled by this class.
 * @package    pims
 * @subpackage registration
 * @author     Shashimal Warakagoda
 */
class consultancyActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {

    }

    /**
     * List STD input category list
     * @param sfWebRequest $request
     * @return unknown_type
     */
    function executeShowStdInputCategoryList(sfWebRequest $request) {

        $stdCategory = new StdInputCategory();
        $this->stdCategories = $stdCategory->showStdInputCategoryList();
    
    }

    /**
     * Show STD input category form
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddStdInputCategory(sfWebRequest $request) {

    }

    /**
     * Save STD input category object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeSaveStdInputCategory(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $idGenService = new UniqueIdGenerator();
            $idGenService->setEntityTable('StdInputCategory');
            
            $stdCategory = new StdInputCategory();
            
            if ($stdCategory->isStdInputCategoryExist($idGenService->getNextID(false), $request->getParameter('txtInputCategory'))) {
                
                $this->getUser()->setFlash('error', 'Duplicate record');
                $this->redirect('consultancy/showStdInputCategoryList/');
            
            } else {
                
                $categoryCode = $idGenService->getNextID();
                $stdCategory->setInputCategoryCode($categoryCode);
                $stdCategory->setName($request->getParameter('txtInputCategory'));
                $stdCategory->setDescription($request->getParameter('txtDescription'));
                $stdCategory->saveStdInputCategory();
                
                $this->getUser()->setFlash('notice', 'Record saved successfully');
                $this->redirect('consultancy/showStdInputCategoryList');
            
            }
        }
    }

    /**
     * View STD input category object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewStdInputCategory(sfWebRequest $request) {

        $stdCategory = new StdInputCategory();
        $stdCategory->setInputCategoryCode($request->getParameter('id'));
        $this->stdInputCategory = $stdCategory->viewStdInputCategory();
    }

    /**
     * Update STD input category object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeUpdateStdInputCategory(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $stdCategory = new StdInputCategory();
            $stdCategory->setInputCategoryCode($request->getParameter('txtInputCategoryCode'));
            
            if ($stdCategory->isStdInputCategoryExist($request->getParameter('txtInputCategoryCode'), $request->getParameter('txtInputCategory'))) {
                
                $this->getUser()->setFlash('error', 'Duplicate Record');
                $this->redirect('consultancy/showStdInputCategoryList/');
            
            } else {
                
                $this->stdInputCategory = $stdCategory->getStdInputCategoryObject();
                $this->stdInputCategory->setName($request->getParameter('txtInputCategory'));
                $this->stdInputCategory->setDescription($request->getParameter('txtDescription'));
                $this->stdInputCategory->saveStdInputCategory();
                
                $this->getUser()->setFlash('notice', 'Record updated successfully');
                $this->redirect('consultancy/showStdInputCategoryList');
            
            }
        
        }
    }

    /**
     * Delete a STD input category
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeDeleteStdInputCategory(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) >0) {
            
            $stdCategory = new StdInputCategory();
            $stdCategory->deleteStdInputCategory($request->getParameter('chkLocID'));
            $this->getUser()->setFlash('notice', 'Successfully deleted the record');
        
        } else {
            
            $this->getUser()->setFlash('error', 'Select at least lne record to delete');
        }
        
        $this->redirect('consultancy/showStdInputCategoryList');
    }

    /**
     * List STD input list
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeShowStdInputList(sfWebRequest $request) {

        $stdInput = new StdInput();
        $this->stdInputs = $stdInput->showStdInputList();
    
    }

    //Show all the STD inputs
    private function _showAllStdInputs(StdInput $stdInput, sfWebRequest $request) {

        $this->pagination = true;
        $this->pager = new sfDoctrinePager('StdInput', 20);
        $this->pager->setQuery($stdInput->showStdInputList());
        $this->pager->setPage($request->getParameter('page', 1));
        $this->pager->init();
    }

    /**
     * View STD input object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewStdInput(sfWebRequest $request) {

        $stdCategory = new StdInputCategory();
        $objStdInput = new StdInput();
        
        $objStdInput->setInputCode($request->getParameter('id'));
        $this->stdInputCategories = $stdCategory->getAllStdInputCategories();
        $this->stdInput = $objStdInput->viewStdInput();
    
    }

    /**
     * Show STD input form
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddStdInput(sfWebRequest $request) {

        $stdCategory = new StdInputCategory();
        $this->stdInputCategories = $stdCategory->getAllStdInputCategories();
    }

    /**
     * Save STD input object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeSaveStdInput(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $idGenService = new UniqueIdGenerator();
            $idGenService->setEntityTable('StdInput');
            
            $stdInput = new StdInput();
            
            if ($stdInput->isStdInputExist($idGenService->getNextID(false), $request->getParameter('txtInputName'))) {
                
                $this->getUser()->setFlash('error', 'Duplicate record');
                $this->redirect('consultancy/showStdInputList/');
            
            } else {
                
                $stdInputCode = $idGenService->getNextID();
                $stdInput->setInputCode($stdInputCode);
                $stdInput->setInputName($request->getParameter('txtInputName'));
                $stdInput->setInputDescription($request->getParameter('txtDescription'));
                $stdInput->setNoOfInput($request->getParameter('txtNoOfInput'));
                $stdInput->setInputCategoryCode($request->getParameter('cmbInputCategory'));
                $stdInput->setSex($request->getParameter('cmbSex'));
                $stdInput->saveStdInput();
                
                $this->redirect('consultancy/addStdInputResult?sic=' .$request->getParameter('cmbInputCategory') ."&inc=" .$stdInputCode);
            
            }
        }
    }

    /**
     * Update STD object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeUpdateStdInput(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $stdInput = new StdInput();
            
            $inputCode = $request->getParameter('txtInputCode');
            $stdInput->setInputCode($inputCode);
            
            if ($stdInput->isStdInputExist($request->getParameter('txtInputCode'), $request->getParameter('txtInputName'))) {
                
                $this->getUser()->setFlash('error', 'Duplicate record');
                $this->redirect('consultancy/showStdInputList/');
            
            } else {
                
                $objStdInput = $stdInput->getStdInputObject();
                $objStdInput->setInputName($request->getParameter('txtInputName'));
                $objStdInput->setInputDescription($request->getParameter('txtDescription'));
                $objStdInput->setNoOfInput($request->getParameter('txtNoOfInput'));
                $objStdInput->setInputCategoryCode($request->getParameter('cmbInputCategory'));
                $objStdInput->setSex($request->getParameter('cmbSex'));
                $objStdInput->saveStdInput();
                
                $this->getUser()->setFlash('notice', "Record saved successfully");
                $this->redirect('consultancy/showStdInputList');
            }
        }
    }

    /**
     * Delete STD input object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeDeleteStdInput(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) >0) {
            
            $stdInput = new StdInput();
            $stdInput->deleteStdInput($request->getParameter('chkLocID'));
            $this->getUser()->setFlash('notice', 'Successfully deleted the record');
        
        } else {
            
            $this->getUser()->setFlash('notice', 'Select at least one record to delete');
        }
        
        $this->redirect('consultancy/showStdInputList');
    }

    /**
     * Show STD input result form
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddStdInputResult(sfWebRequest $request) {

        $objStdInput = new StdInput();
        $objStdInput->setInputCode($request->getParameter('inc'));
        $this->stdInput = $objStdInput->getStdInputObject();
    
    }

    /**
     * Save STD input result object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeSaveStdInputResult(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            if ($request->getParameter('state') =="new") {
                
                $idGenService = new UniqueIdGenerator();
                $idGenService->setEntityTable('StdInputResult');
                
                $stdInputResult = new StdInputResult();
                
                if ($stdInputResult->isStdInputResultExist(ltrim($idGenService->getNextID(false), "0"), $request->getParameter('txtInputResult'))) {
                    
                    $this->getUser()->setFlash('error', 'Duplicate record');
                    $this->redirect('consultancy/addStdInputResult?sic=' .$request->getParameter('txtInputCategory') ."&inc=" .$request->getParameter('txtInput'));
                
                } else {
                    
                    $stdInputResultCode = $idGenService->getNextID();
                    $stdInputResult->setResultDescription($request->getParameter('txtInputResult'));
                    $stdInputResult->setInputCode($request->getParameter('txtInput'));
                    $stdInputResult->saveStdInputResult();
                    
                    $this->getUser()->setFlash('notice', "Record saved successfully");
                }
            
            } else {
                
                $stdInputResult = new StdInputResult();
                
                if ($stdInputResult->isStdInputResultExist($request->getParameter('resultId'), $request->getParameter('txtInputResult'))) {
                    
                    $this->getUser()->setFlash('error', 'Duplicate record');
                    $this->redirect('consultancy/addStdInputResult?sic=' .$request->getParameter('txtInputCategory') ."&inc=" .$request->getParameter('txtInput'));
                
                } else {
                    
                    $objInputResult = $stdInputResult->getStdInputResultObject($request->getParameter('resultId'));
                    $objInputResult->setResultDescription($request->getParameter('txtInputResult'));
                    $objInputResult->saveStdInputResult();
                    
                    $this->getUser()->setFlash('notice', "Record saved successfully");
                }
            }
            
            $this->redirect('consultancy/addStdInputResult?sic=' .$request->getParameter('txtInputCategory') ."&inc=" .$request->getParameter('txtInput'));
        }
    }

    /**
     * Delete STD input object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeDeleteStdInputResult(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) >0) {
            
            $stdInputResult = new StdInputResult();
            
            $stdInputResult->deleteStdInputResult($request->getParameter('chkLocID'));
            $this->getUser()->setFlash('notice', 'Successfully deleted the record');
            
            $this->redirect('consultancy/addStdInputResult?sic=' .$request->getParameter('hdnTxtInputCategory') ."&inc=" .$request->getParameter('hdnTxtInput'));
        
        } else {
            
            $this->getUser()->setFlash('notice', 'Select at least one record to delete');
        }
    }

    /**
     * Show episode details
     * @param sfWebRequest $request
     * @return unknown_type
     */
    
    public function executeShowEpisodeDetails(sfWebRequest $request) {

    }

     /**
     * View episode details
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewEpisodeHistory(sfWebRequest $request) {

        if ($request->getParameter('mode') =='search') {
            $patientNo = $request->getParameter('txtPatientNo');
            $this->_getPatientEpisodes($patientNo);
        
        } else if ($request->getParameter('mode') =='view') {
            
            $stdCategory = new StdInputCategory();
            $this->avilableStdCategories = $stdCategory->getAllStdInputCategories();
            $patientNo = $request->getParameter('pid');
            $episodeNo = $request->getParameter('eid');
            $this->_getPatientEpisodes($patientNo);
            $this->_getStdInputQuestion();
            $this->_getgetEpisodeHistory($patientNo, $episodeNo);
            $this->episodeNo = $episodeNo;
        }
    }

    //Get patients episodes
    private function _getPatientEpisodes($patientNo) {

        $ObjPatient = new Patient();
        $ObjPatient->setPatientNo($patientNo);
        $this->patient = $ObjPatient->getPatientObject();
    
    }

    //Get STD input question
    private function _getStdInputQuestion() {

        $objStdInput = new StdInput();
        $this->stdInputs = $objStdInput->getInputAndResults();
        
        $arrCategoy = array();
        $temp = array();
        
        foreach ($this->stdInputs as $stdInput) {
            
            if (array_key_exists($stdInput['input_category_code'], $arrCategoy)) {
            
            } else {
                
                $arrCategoy[$stdInput['input_category_code']] = array();
            }
            
            $arrCategoy[$stdInput['input_category_code']][] = $stdInput;
        
        }
        
        $this->stdInputs = $arrCategoy;
    
    }
    
    // Get episode history
    private function _getgetEpisodeHistory($patientNo, $episodeNo) {

        $objStdResult = new StdResult();
        $objStdResult->setPatientNo($patientNo);
        $objStdResult->setEpisodeNo($episodeNo);
        $this->episodeHistory = $objStdResult->getEpisodeHistory();
    
    }

    /**
     * Save the STD episode details
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeSaveEpisodeDetails(sfWebRequest $request) {

        $stdInput = new StdInput();
        $inputs = $stdInput->getInputAndResults(); //Get all the STD inputs and their results
        

        $patientNo = $request->getParameter('txtPatientId');
        $episodeNo = $request->getParameter('txtEpisodeId');
        
        $arrCheckBoxes = array(); // This array keeps all the status of the check boxes
        

        foreach ($inputs as $input) {
            
            $resultIndex = "res" .$input['input_code'];
            $results = $request->getParameter($resultIndex); //Get the selected STD results when the user submitted the form
            

            $inputCode = $input['input_code'];
            $inputCategoryCode = $input['input_category_code'];
            
            /*
             * Check the type of selected field in the form
             * If the number of inputs for a input is 1, it should be an option button
             * If it is > 1 it should be a check box
             *
            */
            if ($input['no_of_input'] ==1) {
                
                if (! empty($results)) {
                    
                    $objStdResult = new StdResult();
                    $objStdResult->setPatientNo($patientNo);
                    $objStdResult->setEpisodeNo($episodeNo);
                    $objStdResult->setInputCode($input['input_code']);
                    
                    $tempObjStdResult = $objStdResult->getStdEpisodeResultObjectWithoutResultCode();
                    
                    //Update an existing option button
                    if (count($tempObjStdResult) >0) {
                        
                        $tempObjStdResult[0]->setResultCode($results[0]);
                        $tempObjStdResult[0]->save();
                    
                    } else {
                        
                        //Add a new option button
                        $objStdResult->setResultCode($results[0]);
                        $objStdResult->setInputCategoryCode($input['input_category_code']);
                        $objStdResult->save();
                    }
                    
                    //Change the status of the episode
                    

                    if ($inputCode =="INC051") {
                        
                        $episode = new Episode();
                        $episode->setPatientNo($patientNo);
                        $episode->setEpisodeNo($episodeNo);
                        $objEpisode = $episode->getCurrentEpisodeObject();
                        
                        if ($results[0] =="280") {
                            $episodeStatus = Episode::EPISODE_STATUS_COMPLETED;
                        } else if ($results[0] =="281") {
                            $episodeStatus = Episode::EPISODE_STATUS_REFERRED;
                        } else if ($results[0] =="282") {
                            $episodeStatus = Episode::EPISODE_STATUS_DEFAULTED;
                        } else if ($results[0] =="283") {
                            $episodeStatus = Episode::EPISODE_STATUS_CONTINUED;
                        } else if ($results[0] =="284") {
                            $episodeStatus = "0";
                        }
                        
                        $objEpisode->setStatus($episodeStatus);
                        $objEpisode->setEndDate(date('Y-m-d'));
                        $objEpisode->save();
                    }
                }
            
            } else {
                
                $arrTemp = array();
                
                if (! empty($results)) {
                    
                    $arrTemp[0] = $results;
                
                } else {
                    
                    $arrTemp[0] = array();
                }
                
                $arrTemp[1] = array($inputCategoryCode);
                $arrCheckBoxes[$inputCode] = $arrTemp;
            
            }
        }
        
        //Saving the check boxes
        foreach ($arrCheckBoxes as $key => $chekbox) {
            
            $inputCode = $key;
            $objStdResultCheckbox = new StdResult();
            
            if (! empty($chekbox)) {
                
                //If the user has selected the check boxes
                

                $resultCheckboxes = $chekbox[0];
                $inputCategoryCode = $chekbox[1][0];
                
                //Check the exisiting selected check boxes of a input in particular episode
                $arrCheckedInputs = $objStdResultCheckbox->getStdEpisodeResulArraytWithoutResultCode($patientNo, $episodeNo, $inputCode);
                
                if (count($arrCheckedInputs) >0) {
                    //To update an existing record, delet the curerent record
                    //Save it as a new record
                    $objStdResultCheckbox->deleteStdEpisodeResult($patientNo, $episodeNo, $inputCode);
                
                }
                
                //Save the record
                foreach ($resultCheckboxes as $checkResult) {
                    
                    $objStdResult = new StdResult();
                    $objStdResult->setPatientNo($patientNo);
                    $objStdResult->setEpisodeNo($episodeNo);
                    $objStdResult->setInputCode($inputCode);
                    $objStdResult->setResultCode($checkResult);
                    $objStdResult->setInputCategoryCode($inputCategoryCode);
                    $objStdResult->save();
                    
                    $episode = new Episode();
                    $episode->setPatientNo($patientNo);
                    $episode->setEpisodeNo($episodeNo);
                   $objEpisode = $episode->getCurrentEpisodeObject();
                   
                    if($checkResult != 234) {
                        $objEpisode->setStdPositive('Yes');
                    }else {
                        $objEpisode->setStdPositive('No');
                    }
                    
                    if($checkResult != 234 && $checkResult == 235) {
                        $objEpisode->setHivPositive('Yes');
                    }else {
                        
                    }
                    
                    $objEpisode->save();
                }
            
            } else {
                
                // If the user has not selected any check box for a particular input
                // All the previous selected items must be removed
                $objStdResultCheckbox->deleteStdEpisodeResult($patientNo, $episodeNo, $inputCode);
            }
        
        }
        
        $this->getUser()->setFlash('notice', 'Record updated successfully');
        $this->redirect('consultancy/viewEpisodeHistory?mode=view&eid=' .$episodeNo ."&pid=" .$patientNo);
    }

     /**
     * Cancel an episode
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeCancelEpisode(sfWebRequest $request) {

        $patientNo = $request->getParameter('pid');
        $episodeNo = $request->getParameter('eid');
        
        $episode = new Episode();
        $episode->setEpisodeNo($episodeNo);
        $episode->setPatientNo($patientNo);
        $currentEpisode = $episode->getCurrentEpisodeObject();
        
        $currentEpisode->setEndDate(date('Y-m-d'));
        $currentEpisode->setStatus(Episode::EPISODE_STATUS_CANCELED);
        $currentEpisode->save();
        
        //Update the episode status
        $stdResult = new StdResult();
        $stdResult->setPatientNo($patientNo);
        $stdResult->setEpisodeNo($episodeNo);
        $stdResult->setInputCode('INC051');
        $obj = $stdResult->getStdEpisodeResultObjectWithoutResultCode();      
        
        if(count($obj)>0) {
            $obj[0]->setResultCode(284);
            $obj[0]->save();
        }else {
             $stdResult->setInputCategoryCode('SIC004');
             $stdResult->setInputCode('INC051');
             $stdResult->setResultCode(284);
             $stdResult->save();
        }
        
        $this->getUser()->setFlash('notice', 'Record updated successfully');        
        $this->redirect('registration/showPatientDetails?id='.$patientNo);
        
    }

    /**
     * Search default patients
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeTraceDefaultPatients(sfWebRequest $request) {

    }

    /**
     * Display default patients
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewDefaultPatients(sfWebRequest $request) {

        $fromDate = $request->getParameter('txtFromDate');
        $toDate = $request->getParameter('txtToDate');
        $noOfDays = $request->getParameter('txtNo');
        
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
            $temp[3] = $objDefaultPatient->getEpisodeNo();
            $temp[4] = $objDefaultPatient->getVisitNo();
            $temp[5] = $objDefaultPatient->getAppointed_date();
            $temp[6] = $this->_dateDiff($objDefaultPatient->getAppointed_date(), $toDate);
            
            $defaultPatients[] = $temp;
        }
        
        $this->fromDate = $fromDate;
        $this->toDate = $toDate;
        $this->defaultPatients = $defaultPatients;
    
    }

    //Get the date difference 
    private function _dateDiff($start, $end) {

        $start_ts = strtotime($start);
        $end_ts = strtotime($end);
        
        $diff = $end_ts -$start_ts;
        
        return round($diff /86400);
    
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
            $temp[3] = $objDefaultPatient->getEpisodeNo();
            $temp[4] = $objDefaultPatient->getVisitNo();
            $temp[5] = $objDefaultPatient->getAppointed_date();
            $temp[6] = $this->_dateDiff($objDefaultPatient->getAppointed_date(), $toDate);
            
            $defaultPatients[] = $temp;
        }
        
        $header = array('Patient No', 'Name', "Gender", "Episode No", "Visit No", "App Date", "Delayed Days");
        $data = $defaultPatients;
        $reportTitle = "Default Patients from " .$fromDate ." to " .$toDate;
        $fileName = "Defalut Patients" .$fromDate ."_" .$toDate;
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName, 25);
        }
        die();
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
     * Trace contact
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeTraceContacts(sfWebRequest $request) {

    }

     /**
     * View trace contact patient
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewTracedPatient(sfWebRequest $request) {

        $objPatient = new Patient();
        $objPatient->setPatientNo($request->getParameter('pno'));
        $patient = $objPatient->getPatientObject();
        
        if ($patient instanceof Patient) {
            
            $arrPatintInfo = array();
            $arrPatintInfo[] = $patient->getPatientNo();
            $arrPatintInfo[] = base64_decode($patient->getFirstName());
            $arrPatintInfo[] = base64_decode($patient->getLastName());
            $arrPatintInfo[] = base64_decode($patient->getCurrentAddress());
            $arrPatintInfo[] = base64_decode($patient->getPermanentAddress());
            $arrPatintInfo[] = base64_decode($patient->getContactAddress());
            $arrPatintInfo[] = $patient->getSex() =="1" ? "Male" : "Female";
            
            $episode = new Episode();
            $currentEpisodeNo = $episode->getLastEpisodeNoOfPatient($request->getParameter('pno'));
            
            $stdResult = new StdResult();
            $stdResult->setPatientNo($request->getParameter('pno'));
            $stdResult->setEpisodeNo($currentEpisodeNo);
            $stdResult->setInputCode('INC048');
            $objResult = $stdResult->getStdEpisodeResultObjectWithoutResultCode();
            
            $objContact = new TraceContact();
            $arrContatDetails = $objContact->getTracedContatDetails($request->getParameter('pno'));
            
            $this->objResult = $objResult;
            $this->arrPatintInfo = $arrPatintInfo;
            $this->arrStdResults = $arrStdResults;
            $this->arrContatDetails = $arrContatDetails;
        }
    }

     /**
     * Add contact details
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddContact(sfWebRequest $request) {

        $this->patientNo = $request->getParameter('pno');
    }

     /**
     * Save contact details
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeSaveContact(sfWebRequest $request) {

        $patientNo = $request->getParameter('pno');
        $slipNo = $request->getParameter('txtSlipNo');
        $contactDetails = $request->getParameter('txtContactDet');
        
        $traceContact = new TraceContact();
        $traceContact->setPatientNo($patientNo);
        $traceContact->setSlipNo($slipNo);
        $traceContact->setContactDetails($contactDetails);
        $traceContact->save();
        
        $this->getUser()->setFlash('notice', 'Record saved successfully');
        $this->redirect('consultancy/viewTracedPatient?pno=' .$patientNo);
    
    }    

}
