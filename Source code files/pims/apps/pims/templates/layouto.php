<?php 

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
$_SESSION['arrModules'] = $arrayModules;

?>


<!-- apps/frontend/templates/layout.php -->
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
 "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<title>PIMS</title>
<link rel="shortcut icon" href="/favicon.ico" />
<?php include_javascripts() ?>
<?php include_stylesheets() ?>
</head>
<body>

<div id="container">

<div id="header">
<div class="content">

<div id="sub_header">

<h1>PIMS</h1>

<p>National STD Control Program</p>

<?php if(isset($_SESSION['login']) && $_SESSION['login'] == true) { ?>


<ul id="sddm">

	<li>
		<a href="<?php echo url_for('home/') ?>/showDashboard/" onmouseout="mclosetime()">Home</a>
	</li>
		
<?php if(isset($arrRights[Admin]['view'])) {?>

	<li>
    	<a href="<?php echo url_for('admin/showUserGroupList') ?>" onmouseover="mopen('m1')" onmouseout="mclosetime()">Admin</a>
    	<div id="m1" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
        	<a href="<?php echo url_for('admin/showUserGroupList') ?>">User Groups</a>         	
        	<a href="<?php echo url_for('admin/showUserList') ?>">Users</a>        	
        	<a href="<?php echo url_for('auth/logout') ?>">Log Out</a>
    	</div>
	</li>
	
<?php }?>

<?php if(isset($arrRights[Registration]['view'])) {?>

	<li>
		<a href="#" onmouseover="mopen('m2')" onmouseout="mclosetime()">Registration</a>
		<div id="m2" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
			<a href="#">ASP Dropdown</a> <a href="#">Pulldown menu</a> <a href="#">AJAX	dropdown</a>
			<a href="#">DIV dropdown</a>
		</div>
	</li>
<?php }?>

<?php if(isset($arrRights[Consultancy]['view'])) {?>
	<li>
		<a href="#" onmouseover="mopen('m3')" onmouseout="mclosetime()">Consaltant</a>
		<div id="m3" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
			<a href="#">Visa Credit Card</a> <a href="#">Paypal</a>
		</div>
	</li>
<?php }?>

<?php if(isset($arrRights[Inquiry]['view'])) {?>
	<li>
		<a href="#" onmouseover="mopen('m4')" onmouseout="mclosetime()">Inquiry</a>
		<div id="m4" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
			<a href="#">Download Help File</a> <a href="#">Read online</a>
		</div>
	</li>
<?php }?>

<?php if(isset($arrRights[Report]['view'])) {?>
	<li>
		<a href="#" onmouseover="mopen('m5')" onmouseout="mclosetime()">Report</a>
		<div id="m5" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
			<a href="#">E-mail</a> <a href="#">Submit Request Form</a> <a href="#">Call Center</a>
		</div>
	</li>
<?php }?>

	<li>
	<a href="#" onmouseover="mopen('m6')" onmouseout="mclosetime()">Help</a>	
    	<div id="m6" onmouseover="mcancelclosetime()" onmouseout="mclosetime()">
    		<a href="#">E-mail</a> <a href="#">Submit Request Form</a> <a href="#">Call	Center</a>
    	</div>
	</li>	
	<li><a href="<?php echo url_for('auth/logout/') ?>" onmouseout="mclosetime()">Log Out</a>
</ul>
<div style="clear: both"></div>
<div style="clear: both"></div>
<hr />
<?php }?></div>
</div>
</div>

<div class="content"><?php echo $sf_content ?></div>
</div>

<div id="footer">
<div class="content"></div>
</div>
</div>
<script type="text/javascript">
    <!--
        if (document.getElementById && document.createElement) {
            roundBorder('outerbox');
        }
    -->
</script>
</body>
</html>
