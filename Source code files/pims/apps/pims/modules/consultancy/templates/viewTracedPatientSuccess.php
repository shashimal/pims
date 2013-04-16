<div id="tabs" class="div-tabs" style="width:960px">
    <ul>
        <li><a href="#tabs-1">Patient Information</a></li>
    </ul>
    <div class="message">
        <?php if ($sf_user->hasFlash('notice')): ?>
            <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
        <?php endif ?> <?php if ($sf_user->hasFlash('error')): ?>
                <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
        <?php endif ?>
  </div>

    <?php 
    $patientNo = "";
    if (isset($arrPatintInfo)) {
        
        $patientNo = $arrPatintInfo[0];
 ?>
			
                    <div >
                        <table width="771"  cellspacing="0" style="margin-left: 3px;margin-top: 10px;">
                            <thead>
                            <th width="128" ></th>
                            <th width="262" ></th>
                            <th width="141" ></th>
                            <th width="205" ></th>

                            <td width="23"></thead>
                            <tr>
                                <td><strong>Patient No</strong></td>
                                <td><?php echo $arrPatintInfo[0]; ?></td>
                                <td><strong>Sex</strong></td>
                                <td><?php echo  $arrPatintInfo[6];?></td>
                            </tr>
                            <tr>
                                <td ><strong>First Name</strong></td>
                                <td><?php echo $arrPatintInfo[1]; ?></td>
                                <td ><strong>Last Name</strong></td>
                                <td ><?php echo $arrPatintInfo[2]; ?></td>
                            </tr>
                            <tr>
                                <td ><strong>Current Address</strong></td>
                                <td ><?php echo  $arrPatintInfo[3]; ?></td>
                                <td ><strong>Permanent Address</strong></td>
                                <td><?php echo  $arrPatintInfo[4];?></td>
            </tr>
        </table>
    </div>
</div>

<div id="tabs1" class="div-tabs" style="width:960px">
    <ul>
        <li><a href="#tabs-1">Diagnosis Details</a></li>
    </ul>
	<br />
	<?php  if(count($objResult)>0) {?>
	<?php foreach ($objResult as $result) {?>
	
	<?php 
	    $str = explode(".", $result->getStdInputResult()->getResultDescription());
	?>
	<input type="checkbox"></input><?php echo $str[1];?>
	<br />
	<?php }
}else {
    echo "No records found";
}
	?>
	
</div>
<div id="tabs2" class="div-tabs" style="width:960px">
    <ul>
        <li><a href="#tabs-1">Contact Details</a></li>
    </ul>
    <?php if(!empty($arrContatDetails)) {?>
    <table class="normal-table" cellspacing="0" style="margin-left: 3px;margin-top: 10px; " >
            <tr>
                <th scope="col"   class="normal-list-head">Slip No</th>
                <th scope="col" class="normal-list-head">Contact</th>
                
            </tr>
                <?php
                foreach ($arrContatDetails  as $contact) {
                    ?>
            <tr>
                <td class="normal-lsit-data"><?php echo  $contact['slip_no']; ?></td>
               
                <td class="normal-lsit-data"> <?php echo  $contact['contact_details']; ?></td>
            </tr>
                    <?php

                }
                
                ?>
        </table>
        <?php }else {
            echo "No records found";
        }
            ?>
	<br />
	
</div>
<table border="0">
			<tr>
				<td width="276"></td>
			    <td width="201" valign="top">
				<div class="demo">
				<?php  if(count($objResult)>0) {?>
					<button id="btnAdd">Add Contact</button>	
				<?php }?>				
					<button id="btnBack">Back</button>
				</div>
			  </td>
			</tr>
		</table>
<?php }else {

    echo "No records found";
}?>
<script type="text/javascript">

    $(document).ready(function() {

        //Validate the form
        $("#frmSearchBox").validate({

            rules: {
                txtPatientNo: { required: true }
            },
            messages: {
                txtPatientNo: "Patient no is required"
            }
        });

        // When click edit button
        $("#btnAdd").click(function() {
        	location.href = "<?php echo url_for('consultancy/addContact?pno='.$patientNo) ?>";
        });

        // When click edit button
        $("#btnSearch").click(function() {
            $('#frmSearchBox').submit();
        });

        //When click reset buton
        $("#btnBack").click(function() {
        	location.href = "<?php echo url_for('consultancy/traceContacts') ?>";
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
		
        $(function() {
            $("#tabs1").tabs({ selected: 0 });
        });
		
        $(function() {
            $("#tabs2").tabs({ selected: 0 });
        });

        $(function() {
            $("button", ".demo").button();

        });

        $(function() {
            $("#accordion").accordion({ active: false,autoHeight: false,collapsible: true });
        });

    });
</script>