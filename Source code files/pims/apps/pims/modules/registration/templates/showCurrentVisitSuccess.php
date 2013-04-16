<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
*/

//$currentVisits = $currentVisits[0];
//$visits = $currentVisits['Visit'];

?>
<div id="tabs" class="div-tabs" style="width:700px" >
    <ul>
        <li><a href="#tabs-1">Mark / Create Visit</a></li>
    </ul>
    <div class="outerbox">
       

        <div class="message">
        <?php if ($sf_user->hasFlash('notice')): ?>
        <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
        <?php endif ?>

        <?php if ($sf_user->hasFlash('error')): ?>
        <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
        <?php endif ?>
    </div>
        <form name="frmVisit" id="frmVisit" method="post" action="<?php echo url_for('registration/markVisits') ?>/">
            <input type="hidden" name="txtPatientNo" value="<?php echo $patientNo; ?>" />
            <input type="hidden" name="txtEpisodeNo" value="<?php echo $episodeNo; ?>" />
            <input type="hidden" name="txtVisitNo" value="<?php echo count($currentVisits); ?>" />
            <br class="clear"/>
            <div id="episode" style="margin-left: 5px;">
                <strong>Patient No :</strong><?php echo $patientNo; ?><br />
                 <strong>Episode No :</strong><?php echo $episodeNo; ?><br />
            </div>
            <br class="clear"/>
             <table class="normal-table" cellspacing="0" style="margin-left: 3px;margin-top: 10px;">
                <thead >
                <th scope="col"   class="normal-list-head" >Appointed Date</th>
                <th scope="col"   class="normal-list-head" >Visited Date</th>
                </thead>
                <?php

                foreach ($currentVisits  as $vist) {                    
                    if(($vist['visited_date'] == '0000-00-00') && ($vist['next_visit_date'] == '0000-00-00') ) {
                        ?>
                <tr>
                     <td class="normal-lsit-data"><?php echo  $vist['appointed_date']; ?></td>
                    <td class="normal-lsit-data"><input type="text" size="12" name="txtVisitedDate" id="txtVisitedDate" ></td>
                </tr>

                        <?php
                    }else if(($vist['next_visit_date']== '0000-00-00')) { ?>
                <tr>
                     <td class="normal-lsit-data"><?php echo  $vist['appointed_date']; ?></td>
                     <td class="normal-lsit-data"><?php echo  $vist['visited_date']; ?></td>
                </tr>
                <tr>
                     <td class="normal-lsit-data"><input type="text" size="12" name="txtAppointedDate" id="txtAppointedDate" ></td>
                     <td class="normal-lsit-data"></td>
                </tr>

                        <?php } else {?>
                <tr>
                     <td class="normal-lsit-data"><?php echo  $vist['appointed_date']; ?></td>
                     <td class="normal-lsit-data"><?php echo  $vist['visited_date']; ?></td>
                </tr>
                        <?php

                    }
                    ?>
                    <?php

                }
                ?>

            </table>

	<br />
      
        </form>
		<table border="0">
            <tr>
                <td width="124"></td>
                <td width="124"></td>
                <td width="350" valign="top">
                    <div class="demo">
                        <button id="btnSave" style="height:25px;">Save</button>                        
                        <button id="btnBack">Back</button>
                    </div>
              </td>
            </tr>
        </table>
    </div>
</div>

<script type="text/javascript">

    $(document).ready(function() {

        //Validate the form
        $("#frmVisit").validate({

            rules: {
                txtAppointedDate: { required: true,date: true },
                txtVisitedDate: { required: true,date: true }
            },
            messages: {
                txtAppointedDate: "Valid date is required",
                txtVisitedDate: "Valid date is required"
            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmVisit').submit();
        });

		 $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('registration/showMarkAndVisit') ?>";
        });
		
		$(function() {
            $("#txtAppointedDate").datepicker({
                showOn: 'button',
                buttonImage: '/images/calendar.gif',
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: '2000:2030'
            });
        });
		
		$(function() {
                $("button", ".demo").button();

            });
		
		$(function() {
            $("#txtVisitedDate").datepicker({
                showOn: 'button',
                buttonImage: '/images/calendar.gif',
                buttonImageOnly: true,
                dateFormat: 'yy-mm-dd',
                changeMonth: true,
                changeYear: true,
                yearRange: '2000:2030'
            });
        });

    });
</script>