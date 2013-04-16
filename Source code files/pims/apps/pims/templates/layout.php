<?php 
session_start();
$loginUser = $_SESSION['login_user'];
$assignedModules = $_SESSION['assignedModules'];

$arrayModules = array();
$arrayModules['MOD001'] = "Admin"; 
$arrayModules['MOD002'] = "Registration"; 
$arrayModules['MOD003'] = "Consultancy"; 
$arrayModules['MOD004'] = "Inquiry"; 
$arrayModules['MOD005'] = "Report"; 

define('Admin', 'MOD001');
define('Registration', 'MOD002');
define('Consultancy', 'MOD003');
define('Inquiry', 'MOD004');
define('Report', 'MOD005');

$arrRights=array();
$arrMod = array('Admin' => 'MOD001', 'Registration'=> 'MOD002',  'Consultancy'=> 'MOD003', 'Inquiry'=> 'MOD004', 'Report'=> 'MOD005' );

foreach ($assignedModules as $module) {

    switch ($module['module_id']) {
        case Admin:
            $arrRights[Admin] = $module;
            break;
        case Registration:
            $arrRights[Registration] = $module;
            break;
        case Consultancy:
            $arrRights[Consultancy] = $module;
            break;
        case Inquiry:
            $arrRights[Inquiry] = $module;
            break;
        case Report:
            $arrRights[Report] = $module;
            break;
        default:
            break;
    }
}
$_SESSION['arrRights'] = $arrRights;
//echo "<pre>";
//print_r($assignedModules);//die;
$_SESSION['arrMod'] = $arrMod;
$assignModName = array();
foreach ($assignedModules as $mod) {
    if(array_key_exists($mod['module_id'],$arrayModules)) {
        //$assignModName[$mod['module_id']] = $mod['module'];
        $assignModName[$mod['module']] = $mod['module_id'];
    }
}
//print_r($assignModName);
$_SESSION['modName'] = $assignModName;
//print_r($arrRights);//die;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    <head>
        <title>PIMS</title>

        <link rel="stylesheet" type="text/css" href="/css/ddsmoothmenu.css" />
        <?php include_stylesheets() ?>
        <?php include_javascripts() ?>
        <script type="text/javascript" src="/js/ddsmoothmenu.js"></script>
        <script type="text/javascript">
<?php if(isset($_SESSION['login']) && $_SESSION['login'] == true) { ?>
            ddsmoothmenu.init({
                mainmenuid: "smoothmenu1", //menu DIV id
                orientation: 'h', //Horizontal or vertical menu: Set to "h" or "v"
                classname: 'ddsmoothmenu', //class added to menu's outer DIV
                //customtheme: ["#1c5a80", "#18374a"],
                contentsource: "markup" //"markup" or ["container_id", "path_to_menu_file"]
            })
    <?php }?>
        </script>
    </head>
    <body>
        <?php if(isset($_SESSION['login']) && $_SESSION['login'] == true) { ?>
        <div><img src="/images/header.jpg"  /></div>
        <div id="smoothmenu1" class="ddsmoothmenu">
            <ul>
                <li><a href="">Home</a></li>

                    <?php if(isset($arrRights[Admin]['view'])) {?>
                <li><a href="<?php echo url_for('admin/showUserGroupList') ?>">Admin</a>
                    <ul>
                      <li><a href="<?php echo url_for('admin/showUserGroupList') ?>">User Groups</a></li>
                      <li><a href="<?php echo url_for('admin/showUserList') ?>">User Accounts</a></li>                           
                      <li><a href="<?php echo url_for('auth/showChangePassword') ?>">Change Password</a></li>
                      <li><a href="<?php echo url_for('auth/logout') ?>">Log Out</a></li>
                    </ul>
                </li>
                        <?php }?>

                    <?php if(isset($arrRights[Registration]['view'])) {?>
                <li><a href="#">Registration</a>
                    <ul>
                        <li><a href="<?php echo url_for('registration/showPatientList') ?>">Patient Registration</a></li>
                        <li><a href="<?php echo url_for('registration/showPatientCategoryList') ?>">Patient Category</a></li>
                        <li><a href="<?php echo url_for('registration/showAppoinments') ?>">Appoinment List</a></li>
                        <li><a href="<?php echo url_for('registration/showMarkAndVisit') ?>">Mark / Create Visit</a></li>
                        <li><a href="<?php echo url_for('registration/showAttendanceReport') ?>">Attendance List</a></li>
                    </ul>
                </li>
                        <?php }?>

                    <?php if(isset($arrRights[Consultancy]['view'])) {?>
                <li><a href="#">Consultancy</a>
                    <ul>
                        <li><a href="<?php echo url_for('consultancy/showEpisodeDetails') ?>">STD Episode</a></li>
                        <li><a href="<?php echo url_for('consultancy/traceDefaultPatients') ?>">Trace Default Patients</a></li>
                        <li><a href="<?php echo url_for('consultancy/traceContacts') ?>">Interview & contact Tracing</a></li>
                        <li><a href="#">Episode Questions/Answers</a>
                            <ul>
                                <li><a href="<?php echo url_for('consultancy/showStdInputCategoryList') ?>">Input Category</a></li>
                                <li><a href="<?php echo url_for('consultancy/showStdInputList') ?>">Input / Input Results</a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                        <?php }?>
<!--
                    <?php if(isset($arrRights[Inquiry]['view'])) {?>
                <li><a href="#">Inquiry</a>
                    <ul>
                        <li><a href="#">Sub Item 2.1</a></li>
                    </ul>
                </li>
                      <?php }?>  -->
                    <?php if(isset($arrRights[Report]['view'])) {?>
                <li><a href="#">Report</a>
                    <ul>
                    	 
                        
                         <li><a href="#">Clinic</a>
                            <ul>
                            	<li><a href="<?php echo url_for('report/createClinicAttendeeReport/'); ?>">Clinic Attendees</a></li>
                            	<li><a href="<?php echo url_for('report/createAppointmentReport/'); ?>">Appointments Of Patient</a></li>
                            	<li><a href="<?php echo url_for('report/createDefaultPatientReport/'); ?>">Defaulted Patients</a></li> 
                            	<li><a href="<?php echo url_for('report/createCswtReport/'); ?>">Clinic Attendance Of Sex Workers</a></li>
                            	<li><a href="<?php echo url_for('report/createReasonForAttendanceReport/'); ?>">Reasons For Attendance</a></li>
                            	<li><a href="<?php echo url_for('report/createMaritalStatusReport/'); ?>">Marital Status</a></li>
                        		<li><a href="<?php echo url_for('report/createOccupationReport/'); ?>">Occupation</a></li>                          		                 			
                        		<li><a href="<?php echo url_for('report/createNationalityReport/'); ?>">Nationality</a></li>                                                 		
                        		
                            </ul>
                        </li>
                        
                        <li><a href="#">Consultancy</a>
                            <ul>
                               <li><a href="<?php echo url_for('report/createNewStdEpisodeReport/'); ?>">New STD  Episodes</a></li>
                               <li><a href="<?php echo url_for('report/createNewEpisodeOfCswReport/'); ?>">New STD  Episodes Of Sex Workers </a></li>
                               <li><a href="<?php echo url_for('report/createHivPositiveReport/'); ?>">HIV Positive </a></li>
                               <li><a href="<?php echo url_for('report/createHivPositiveDetailedReport/'); ?>">HIV Positive Patient Details </a></li>
                               <li><a href="<?php echo url_for('report/createHivDistributionReport/'); ?>">HIV Positive Distribution </a></li>
                               <li><a href="<?php echo url_for('report/createStdEpisodeOfForeignersReport/'); ?>">STD  Episodes Of Foreigners</a></li>
                               
                            </ul>
                        </li>
                        
                        
                        <li><a href="<?php //echo url_for('admin/showUserGroupList') ?>">Graphs</a>
                            <ul>
                                <li><a href="<?php echo url_for('report/createOccupationGraphRport') ?>">Occupational Categories of Clinic Attendees</a></li>
                                <li><a href="<?php echo url_for('report/createMaritalStatusGraphReport') ?>">Marital Status of Clinic Attendees</a></li>
                                <li><a href="<?php echo url_for('report/createStdDistributionReport') ?>">STD Distribution </a></li>
                                <li><a href="<?php echo url_for('report/createReasonForAttendanceGraphReport') ?>">Reason For Attendance </a></li>
                            </ul>
                        </li>
                    </ul>
                </li>
                        <?php }?>
                <?php if(!$arrRights[Admin]['edit'] ) {?>
                <li><a href="<?php echo url_for('auth/showChangePassword/') ?>">Change Password</a></li>
                        <?php }?>
                 <li><a href="<?php echo url_for('auth/showChangePassword/') ?>">Help</a></li>
                <li><a href="<?php echo url_for('auth/logout/') ?>">Log Out</a></li>
                    
               
            </ul>
            <br style="clear: left" />
        </div>
            <?php }?>

        <div class="content">
        <?php echo $sf_content ?>
        
        </div>

    </body>
</html>