<?php
/**
 * Registration actions.
 * All the functions of the registration module are handled by this class.
 * @package    pims
 * @subpackage registration
 * @author     Shashimal Warakagoda
 */
class registrationActions extends sfActions {

    public function executeIndex(sfWebRequest $request) {

        $this->redirect('registration/showPatientList');
    }

    /**
     * List patient categories
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeShowPatientCategoryList(sfWebRequest $request) {

        $patientCategory = new PatientCategory();
        $this->patientCategories = $patientCategory->showPatientCategoryList();
    
    }

    /**
     * Show patient category form
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeAddPatientCategory(sfWebRequest $request) {

    }

    /**
     * Save patient category object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeSavePatientCategory(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $idGenService = new UniqueIdGenerator();
            $idGenService->setEntityTable('PatientCategory');
            
            $patientCategory = new PatientCategory();
            
            //Check for duplicate category names
            if ($patientCategory->isPatientCategoryExist($idGenService->getNextID(false), $request->getParameter('txtPatientCategory'))) {
                
                $this->getUser()->setFlash('error', 'Duplicate record');
                $this->redirect('registration/showPatientCategoryList');
            
            } else {
                
                $categoryCode = $idGenService->getNextID();
                $patientCategory->setCategoryId($categoryCode);
                $patientCategory->setPatientCategory($request->getParameter('txtPatientCategory'));
                $patientCategory->setDescription($request->getParameter('txtDescription'));
                
                $patientCategory->savePatientCategory();
                
                $this->getUser()->setFlash('notice', 'Record saved successfully');
                $this->redirect('registration/showPatientCategoryList');
            }
        }
    }

    /**
     * View patient category object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewPatientCategory(sfWebRequest $request) {

        $patientCategory = new PatientCategory();
        $patientCategory->setCategoryId($request->getParameter('id'));
        $this->patientCategory = $patientCategory->viewPatientCategory();
    
    }

    /**
     * Update patient category object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeUpdatePatientCategory(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $objPatientCategory = new PatientCategory();
            $objPatientCategory->setCategoryId($request->getParameter('txtPatientCategoryCode'));
            
            if ($objPatientCategory->isPatientCategoryExist($request->getParameter('txtPatientCategoryCode'), $request->getParameter('txtPatientCategory'))) {
                
                $this->getUser()->setFlash('error', 'Duplicate record');
                $this->redirect('registration/showPatientCategoryList');
            
            } else {
                
                $this->patientCategory = $objPatientCategory->getPatientCategoryObject();
                $this->patientCategory->setPatientCategory($request->getParameter('txtPatientCategory'));
                $this->patientCategory->setDescription($request->getParameter('txtDescription'));
                $this->patientCategory->savePatientCategory();
                
                $this->getUser()->setFlash('notice', 'Record saved successfully');
                $this->redirect('registration/showPatientCategoryList');
            
            }
        
        }
    }

    /**
     * Delete patient category object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeDeletePatientCategory(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) >0) {
            
            $patientCategory = new PatientCategory();
            $patientCategory->deletePatientCategory($request->getParameter('chkLocID'));
            
            $this->getUser()->setFlash('notice', 'Successfully deleted');
        
        } else {
            
            $this->getUser()->setFlash('notice', 'Select at least one record to delete');
        }
        
        $this->redirect('registration/showPatientCategoryList');
    }

    /**
     * Show the registered patients list
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeShowPatientList(sfWebRequest $request) {

        $objPatient = new Patient();
        $this->patients = $objPatient->showPatientsList();
    
    }

    /**
     * Search a patient information
     * @param sfWebRequest $request
     */
    public function executeShowPatientDetails(sfWebRequest $request) {

        $objPatient = new Patient();
        $objPatientCategory = new PatientCategory();
        
        $patientNo = $request->getParameter('id');
        
        $this->search = true;
        $this->patient = $objPatient->searchPatient(trim($patientNo));
        $this->patientCategories = $objPatientCategory->getPatientCategories();
        
        if (count($this->patient) >0) {
            $this->recordCount = count($this->patient);
        }
    
    }

    /**
     * Register a new patient
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeRegisterPatient(sfWebRequest $request) {

        $objPatientCategory = new PatientCategory();
        $this->patientCategories = $objPatientCategory->getPatientCategories();
        $this->regDate = date("Y-m-d");
    
    }

    /**
     * Save patient information
     * @param sfWebRequest $request
     * @return unknown_type
     */    
    public function executeSavePatientInformation(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            //Create the patient no
            //Patient No is created according to the gender of the patient and the year registered.
            $patientNoTracker = new PatientNoTracker();
            $registeredYear = date("Y");
            $sex = $request->getParameter('cmbSex');
            $patientNo = $patientNoTracker->getNextPatientNo($registeredYear, $sex);
            
            //Save personal details of the patient
            $patient = new Patient();
            $patient->setPatientNo($patientNo);
            $this->_savePatientInformation($patient, $request);
            
            //Create a new episdoe for the patient
            $episode = new Episode();
            $episodeNo = $episode->getCurrentEpisodeNoOfPatient($patientNo);
            $episode->setPatientNo($patientNo);
            $episode->setEpisodeNo($episodeNo);
            $episode->setStatus(Episode::EPISODE_STATUS_OPEN);
            $episode->setComment("");
            $episode->setStartDate(date('Y-m-d'));
            $episode->setEndDate(NULL);
            $episode->save();
            
            //Save contact mode details
            $contactMode = new ContactMode();
            $contactMode->setPatientNo($patientNo);
            $this->_saveContactModeInformation($contactMode, $request);
            
            //Save Clinic reasons
            $clinicReason = new ClinicReason();
            $clinicReason->setPatientNo($patientNo);
            $clinicReason->setEpisodeNo($episodeNo);
            $this->_saveClinicReasonInformation($clinicReason, $request);
            
            //Save the first visiting record
            $visit = new Visit();
            $visit->setPatientNo($patientNo);
            $visit->setEpisodeNo($episodeNo);
            $visit->setVisitNo(1);
            $visit->setAppointedDate(date('Y-m-d'));
            $visit->setVisitedDate(date('Y-m-d'));
            $visit->setNextVisitDate(NULL);
            $visit->save();
        }
        
        $this->getUser()->setFlash('notice', 'Record saved successfully');
        $this->redirect('registration/showPatientDetails?id=' .$patientNo);
        //$this->redirect('registration/showPatientList');
    }

    /**
     * Edit an existing patient information
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeUpdatePatientInformation(sfWebRequest $request) {

        if ($request->isMethod('post')) {
            
            $patientNo = $request->getParameter('txtPatientNo');
            
            $patientNo = trim($patientNo);
            
            //Get the pateint object of the particular patient no
            $objPatient = new Patient();
            $objPatient->setPatientNo($patientNo);
            $patient = $objPatient->getPatientObject($patientNo);
            
            //Set the changed values of the patient object.
            $this->_savePatientInformation($patient, $request);
            
            //Get the contact mode details object of the particular patient no
            $objContactMode = new ContactMode();
            $objContactMode->setPatientNo($patientNo);
            $contactMode = $objContactMode->getContactObject();
            
            //Set the changed values of the contact mode object.
            $this->_saveContactModeInformation($contactMode, $request);
            
            //Get the reasons details object for the particular patient no
            //echo $patientNo;
            $objReason = new ClinicReason();
            $objReason->setPatientNo($patientNo);
            $clinicReason = $objReason->getReasonObject();
            
            //Set the changed values of the clinic reason object.
            $this->_saveClinicReasonInformation($clinicReason, $request);
            
            $this->getUser()->setFlash('notice', 'Record saved successfully');
           // $this->redirect('registration/showPatientList');
            $this->redirect('registration/showPatientDetails?id=' .$patientNo);
        
        }
    }

    //Set the properties of the patient object
    private function _savePatientInformation(Patient $patient, sfWebRequest $request) {

        //echo $patient->getPatientNo();die;
        //Patient category details
        $patientCategoryCode = $request->getParameter('cmbPatientCategory');
        
        //Personal information
        if ($request->getParameter('txtRegDate') =="") {
            $registeredDate = date("Y-m-d");
        } else {
            $registeredDate = $request->getParameter('txtRegDate');
        }
        
        $firstName = base64_encode($request->getParameter('txtFirstName'));
        $lastName = base64_encode($request->getParameter('txtLastName'));
        $dateOfBirth = $request->getParameter('txtDateOfBirth');
        $maritalStatus = $request->getParameter('cmbMaritalStatus');
        
        if ($request->getParameter('cmbNationality') !="Other") {
            $nationality = $request->getParameter('cmbNationality');
        } else {
            $nationality = $request->getParameter('txtOtherNationallity');
        }
        
        if ($request->getParameter('cmbEducation') !="Other") {
            $educationLevel = $request->getParameter('cmbEducation');
        } else {
            $educationLevel = $request->getParameter('txtOtherEducation');
        }
        
        if ($request->getParameter('cmbOccupation') !="Other") {
            $occupation = $request->getParameter('cmbOccupation');
        } else {
            $occupation = $request->getParameter('txtOtherOccupation');
        }
        
        $sex = $request->getParameter('cmbSex');
        $nic = $request->getParameter('txtNic');
        $comment = $request->getParameter('txtComment');
        
        //Contact details  
        $currentAddress = base64_encode($request->getParameter('txtCurrentAddress'));
        $permanentAddress = base64_encode($request->getParameter('txtPermanentAddress'));
        $contactAddress = base64_encode($request->getParameter('txtContactAddress'));
        $telephone1 = base64_encode($request->getParameter('txtTel1'));
        $telephone2 = base64_encode($request->getParameter('txtTel2'));
        $mobile = base64_encode($request->getParameter('txtMobile'));
        $email = base64_encode($request->getParameter('txtEmail'));
        
        //Validate all the mandotray fields
        if (empty($patientCategoryCode) ||empty($lastName) ||empty($sex) ||empty($occupation)) {
            
            $this->getUser()->setFlash('error', 'Please fill all the mandotray fields ');
            $this->redirect('registration/registerPatient');
        }
        
        //Set personal details properties
        $patient->setCategory($patientCategoryCode);
        $patient->setRegisteredDate($registeredDate);
        $patient->setFirstName($firstName);
        $patient->setLastName($lastName);
        $patient->setDateOfBirth($dateOfBirth);
        $patient->setSex($sex);
        $patient->setMaritalStatus($maritalStatus);
        $patient->setNationality($nationality);
        $patient->setEducation($educationLevel);
        $patient->setOccupation($occupation);
        $patient->setNicPpNo($nic);
        $patient->setComment($comment);
        
        //Set contact details properties   
        $patient->setCurrentAddress($currentAddress);
        $patient->setPermanentAddress($permanentAddress);
        $patient->setContactAddress($contactAddress);
        $patient->setTelephone1($telephone1);
        $patient->setTelephone2($telephone2);
        $patient->setMobile($mobile);
        $patient->setEmail($email);
        
        try{
            $patient->save();
        }catch(Exception $e) {
                throw new Exception($e->getMessage());
         }
    }

    //Set the properties of the contact mode object
    private function _saveContactModeInformation(ContactMode $contactMode, sfWebRequest $request) {

        $contactMethod = $request->getParameter('contactMode');
        
        if ($contactMethod =="no") {
            
            $contactMode->setNoContact(1);
            $contactMode->setEmail(0);
            $contactMode->setLetter(0);
            $contactMode->setTelephone(0);
            $contactMode->setVisit(0);
        
        } else {
            
            $contactMode->setNoContact(0);
            $method = $request->getParameter('method');
            
            if (isset($method['letter'])) {
                $contactMode->setLetter(1);
            } else {
                $contactMode->setLetter(0);
            }
            
            if (isset($method['email'])) {
                $contactMode->setEmail(1);
            } else {
                $contactMode->setEmail(0);
            }
            
            if (isset($method['phone'])) {
                $contactMode->setTelephone(1);
            } else {
                $contactMode->setTelephone(0);
            }
            
            if (isset($method['visit'])) {
                $contactMode->setVisit(1);
            } else {
                $contactMode->setVisit(0);
            }
        
        }
        
        $contactMode->save();
    }

    //Set the properties of the clinic reason object
    private function _saveClinicReasonInformation(ClinicReason $clinicReason, sfWebRequest $request) {

        $reason = $request->getParameter('reason');
        $pno = $clinicReason->getPatientNo();
        
        if (empty($pno)) {
            $clinicReason = new ClinicReason();
            $episode = new Episode();
            $episodeNo = $episode->getLastEpisodeNoOfPatient($request->getParameter('txtPatientNo'));
            
            $clinicReason->setPatientNo($request->getParameter('txtPatientNo'));
            $clinicReason->setEpisodeNo($episodeNo);
        } 
        
        if (isset($reason['opd']) &&$request->getParameter('txtOpd') !="") {
            $clinicReason->setOpd($request->getParameter('txtOpd'));
        } else if ($reason['opd'] =="OPD") {
            $clinicReason->setOpd($reason['opd']);
        } else {
            $clinicReason->setOpd(NULL);
        }
        
        if (isset($reason['ward']) &&$request->getParameter('txtWard') !="") {
            $clinicReason->setWard($request->getParameter('txtWard'));
        } else if ($reason['ward'] =="Ward") {
            $clinicReason->setWard($reason['ward']);
        } else {
            $clinicReason->setWard(NULL);
        }
        
        if (isset($reason['gp']) &&$request->getParameter('txtGp') !="") {
            $clinicReason->setGP($request->getParameter('txtGp'));
        } else if ($reason['gp'] =="GP") {
            $clinicReason->setGP($reason['gp']);
        } else {
            $clinicReason->setGP(NULL);
        }
        
        if (isset($reason['court']) &&$request->getParameter('txtCourt') !="") {
            $clinicReason->setCourt($request->getParameter('txtCourt'));
        } else if ($reason['court'] =="Courts") {
            $clinicReason->setCourt('court');
        } else {
            $clinicReason->setCourt(NULL);
        }
        
        if (isset($reason['bb']) &&$request->getParameter('txtBlood') !="") {
            $clinicReason->setBb($request->getParameter('txtBlood'));
        } else if ($reason['bb'] =="Blood Bank") {
            $clinicReason->setBb($reason['bb']);
        } else {
            $clinicReason->setBb(NULL);
        }
        
        if (isset($reason['contact']) &&$request->getParameter('txtRContact') !="") {
            $clinicReason->setContact($request->getParameter('txtRContact'));
        } else if ($reason['contact'] =="Contact") {
            $clinicReason->setContact($reason['contact']);
        } else {
            $clinicReason->setContact(NULL);
        }
        
        if (isset($reason['voluntary']) &&$request->getParameter('txtVoluntary') !="") {
            $clinicReason->setVolantary($request->getParameter('txtVoluntary'));
        } else if ($reason['voluntary'] =="Voluntary") {
            $clinicReason->setVolantary($reason['voluntary']);
        } else {
            $clinicReason->setVolantary(NULL);
        }
        
        if (isset($reason['clinic']) &&$request->getParameter('txtClinic') !="") {
            $clinicReason->setCf($request->getParameter('txtClinic'));
        } else if ($reason['clinic'] =="Clinic Followup") {
            $clinicReason->setCf($reason['clinic']);
        } else {
            $clinicReason->setCf(NULL);
        }
        
        if (isset($reason['other']) &&$request->getParameter('txtOther') !="") {
            $clinicReason->setOther($request->getParameter('txtOther'));
        } else if ($reason['other'] =="Other") {
            $clinicReason->setOther($reason['other']);
        } else {
            $clinicReason->setOther(NULL);
        }
        
        $clinicReason->save();
    
    }

    /**
     * Delete patient object
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeDeletePatientInformation(sfWebRequest $request) {

        if (count($request->getParameter('chkLocID')) >0) {
            
            $objPatient = new Patient();
            
            $objPatient->deletePatient($request->getParameter('chkLocID'));
            $this->getUser()->setFlash('notice', 'Successfully deleted the record');
        
        } else {
            
            $this->getUser()->setFlash('error', 'Select at least one record to delete');
        }
        
        $this->redirect('registration/showPatientList/');
    }

    /**
     * Create new new patient episode
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeCreateNewEpisode(sfWebRequest $request) {

        $patientNo = $request->getParameter('pid');
        
        $episode = new Episode();
        $episodeNo = $episode->getCurrentEpisodeNoOfPatient($patientNo);
        $episode->setPatientNo($patientNo);
        $episode->setEpisodeNo($episodeNo);
        $episode->setStatus(0);
        $episode->setStartDate(date("Y-m-d"));
        $episode->setEndDate(NULL);
        $episode->setComment("");
        $episode->save();
        
        $visit = new Visit();
        $visit->setPatientNo($patientNo);
        $visit->setEpisodeNo($episodeNo);
        $visit->setVisitNo(1);
        $visit->setAppointedDate(date("Y-m-d"));
        $visit->setVisitedDate(date("Y-m-d"));
        $visit->save();
        
        $this->getUser()->setFlash('notice', 'New episode created');
        $this->redirect('registration/showPatientDetails?id=' .$patientNo);
    
    }

    /**
     * Show the mark and create visit form
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeShowMarkAndVisit(sfWebRequest $request) {

    }

    /**
     * View all the episodes except canceled episodes of a patient  
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewEpisodesOfPatient(sfWebRequest $request) {

        $episode = new Episode();
        $patientNo = $request->getParameter('txtPatientNo');
        $this->allEpsidoes = $episode->getNotCanceledEpisodes($patientNo);
        $this->patientNo = $patientNo;
    
    }

    /**
     * View all the visits of an episode 
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeShowCurrentVisit(sfWebRequest $request) {

        $visit = new Visit();
        $patientNo = $request->getParameter('pid');
        $episodeNo = $request->getParameter('eid');
        $visit->setPatientNo($patientNo);
        $visit->setEpisodeNo($episodeNo);
        
        $visits = $visit->getCurrentEpisodeVisitsOfPatient();
        $this->patientNo = $patientNo;
        $this->episodeNo = $episodeNo;
        $this->currentVisits = $visits;
    
    }

    /**
     * Update the appointment and visited date of clinic
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeMarkVisits(sfWebRequest $request) {

        $patientNo = $request->getParameter('txtPatientNo');
        $episodeNo = $request->getParameter('txtEpisodeNo');
        $visitNo = $request->getParameter('txtVisitNo');
        
        $allVisits = Visit::getAllVisitsOfEpisode($patientNo, $episodeNo); //Get all the visited dates for validation
        $appointedDate = $request->getParameter('txtAppointedDate');
        
        if (! empty($appointedDate)) {
            
            $visit = new Visit();
            
            $visit->setPatientNo($patientNo);
            $visit->setEpisodeNo($episodeNo);
            $visit->setVisitNo($visitNo);
            
            $currentVisit = $visit->getCurrentVisitObject();
            
            if ($this->_validateAppointedDate($allVisits, $appointedDate)) {
                
                $this->getUser()->setFlash('error', 'Please enter a valid date');
                $this->redirect('registration/showCurrentVisit?pid=' .$patientNo ."&eid=" .$episodeNo);
            
            } else {
                
                $currentVisit->setNextVisitDate($appointedDate);
                $currentVisit->save();
            }
            
            //Add a new visit
            $newVisit = new Visit();
            $newVisit->setPatientNo($patientNo);
            $newVisit->setEpisodeNo($episodeNo);
            $newVisit->setVisitNo($visitNo +1);
            $newVisit->setAppointedDate($request->getParameter('txtAppointedDate'));
            $newVisit->save();
        
        } else {
            
            //Update the visited dates
            $visit = new Visit();
            $visit->setPatientNo($patientNo);
            $visit->setEpisodeNo($episodeNo);
            $visit->setVisitNo($visitNo);
            
            $currentVisit = $visit->getCurrentVisitObject();
            $currentVisit->setVisitedDate($request->getParameter('txtVisitedDate'));
            
            if ($this->_validateVisitedDate($allVisits, $request->getParameter('txtVisitedDate'))) {
                
                $this->getUser()->setFlash('error', 'Please enter a valid date');
                $this->redirect('registration/showCurrentVisit?pid=' .$patientNo ."&eid=" .$episodeNo);
            
            } else {
                
                $currentVisit->save();
            }
        
        }
        
        $this->getUser()->setFlash('notice', 'Record saved successfully');
        $this->redirect('registration/showCurrentVisit?pid=' .$patientNo ."&eid=" .$episodeNo);
    
    }

    //Validate the appointed date
    private function _validateAppointedDate($visits, $appointedDate) {

        $date = strtotime($appointedDate);
        $inValidDate = false;
        
        foreach ($visits as $visit) {
            
            if ($date <=strtotime($visit['appointed_date']) ||$date <=strtotime($visit['visited_date']) ||$date <=strtotime($visit['next_visit_date'])) {
                $inValidDate = true;
                break;
            }
        }
        
        return $inValidDate;
    }

    //Validate the visited date
    private function _validateVisitedDate($visits, $visitedDate) {

        $date = strtotime($visitedDate);
        $inValidDate = false;
        
        foreach ($visits as $visit) {
            
            if ($date ==strtotime($visit['appointed_date'])) {
                
                $inValidDate = false;
            
            } else {
                
                if ($date <strtotime($visit['appointed_date']) ||$date <strtotime($visit['visited_date']) ||$date <strtotime($visit['next_visit_date'])) {
                    $inValidDate = true;
                    break;
                }
            }
        
        }
        
        return $inValidDate;
    }

    /**
     * Cancel an episode
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeCancelCurrentEpisode(sfWebRequest $request) {

        $patientNo = $request->getParameter('pid');
        $episodeNo = $request->getParameter('eid');
        
        $episode = new Episode();
        $episode->setEpisodeNo($episodeNo);
        $episode->setPatientNo($patientNo);
        
        $currentEpisode = $episode->getCurrentEpisodeObject();
        $currentEpisode->setStatus(Episode::EPISODE_STATUS_CANCELED);
        $currentEpisode->save();
        
        $this->getUser()->setFlash('notice', 'Record saved successfully');
        $this->redirect('registration/showPatientList?id=' .$patientNo);
    }

    /**
     * show the appointment from
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeShowAppoinments(sfWebRequest $request) {

    }

    /**
     * show appoinment details
     * @param sfWebRequest $request
     * @return unknown_type
     */
    public function executeViewAppoinmentDetails(sfWebRequest $request) {

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
     * Show attendance form
     * @param r $request
     * @return unknown_type
     */
    public function executeShowAttendanceReport(sfWebRequest $request) {

    }

    /**
     * View attendance details
     * @param r $request
     * @return unknown_type
     */
    public function executeViewAttendanceReport(sfWebRequest $request) {

        $gender = $request->getParameter('cmbGender');
        $selection1 = $request->getParameter('cmbSelection1');
        $selection2 = $request->getParameter('cmbSelection2');
        $date = $request->getParameter('txtDate');
        
        $occupation = null;
        $newPatient = null;
        $newEpisode = null;
        
        $objPatient = new Patient();
        $objEpisode = new Episode();
        
        if ($selection1 =='1') {
            $occupation = null;
        } else {
            $occupation = "CSW";
        }
        
        if ($selection2 =='1' ||$selection2 =='2') {
            
            if ($selection2 ==1) {
                $newPatient = null;
            } else {
                $newPatient = true;
            }
            
            $this->attendacneRecords = $objPatient->searchAttendacneDetailsOfPatients($date, $occupation, $newPatient, $gender);
        
        } else if ($selection2 ==3) {
            
            $newEpisode = true;
            $this->attendacneRecords = $objEpisode->searchAttendacneDetailsOfNewEpisodes($date, $occupation, $gender);
        }
        
        $this->date = $date;
        $this->gender = $gender;
        $this->selection1 = $selection1;
        $this->selection2 = $selection2;
    
    }

    /**
     * Export attendance reports
     * @param r $request
     * @return unknown_type
     */
    public function executeExportAttendanceReport(sfWebRequest $request) {

        $gender = $request->getParameter('gender');
        $selection1 = $request->getParameter('selection1');
        $selection2 = $request->getParameter('selection2');
        $date = $request->getParameter('date');
        $reportTye = $request->getParameter('rep');
        
        $occupation = null;
        $newPatient = null;
        $newEpisode = null;
        
        $objPatient = new Patient();
        $objEpisode = new Episode();
        
        if ($selection1 =='1') {
            $occupation = null;
        } else {
            $occupation = "CSW";
        }
        
        if ($selection2 =='1' ||$selection2 =='2') {
            
            if ($selection2 ==1) {
                $newPatient = null;
            } else {
                $newPatient = true;
            }
            
            $this->attendacneRecords = $objPatient->searchAttendacneDetailsOfPatients($date, $occupation, $newPatient, $gender);
        
        } else if ($selection2 ==3) {
            
            $newEpisode = true;
            $this->attendacneRecords = $objEpisode->searchAttendacneDetailsOfNewEpisodes($date, $occupation, $gender);
        }
        
        if ($gender =="1") {
            $gender = "Male";
        } else {
            $gender = "Female";
        }
        
        $header = array('Patient No', 'Name', "Address", "Occupation", "Registered Date");
        $data = array();
        $reportTitle = "Attendance Details of " .$gender ." Patients on " .$date;
        $fileName = "Attendance_" .$gender ."_" .$date;
        
        foreach ($this->attendacneRecords as $attendacneRecord) {
            
            $arrValues = array();
            $arrValues[0] = $attendacneRecord['patient_no'];
            $arrValues[1] = base64_decode($attendacneRecord['first_name']) ." " .base64_decode($attendacneRecord['last_name']);
            $arrValues[2] = base64_decode($attendacneRecord['contact_address']);
            $arrValues[3] = $attendacneRecord['occupation'];
            $arrValues[4] = $attendacneRecord['registered_date'];
            
            $data[] = $arrValues;
        }
        
        if ($reportTye =="CSV") {
            
            $this->_creatCSVFile($header, $data, $fileName .".csv");
        
        } else if ($reportTye =="PDF") {
            
            $this->_createPDFFile($data, $header, $reportTitle, $fileName, 38);
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

}
