<div id="tabs" class="div-tabs" style="width:720px">
 <ul>
        <li><a href="#tabs-1">Episodes History of Patient No : <strong><?php echo $patientNo;?></strong></a></li>
    </ul>
<?php if(count($allEpsidoes)>0) {?>
    <table class="normal-table" cellspacing="0" style="margin-left: 3px;margin-top: 10px; ">
        <thead>
        <th scope="col"   class="normal-list-head">Episode No</th>
        <th scope="col"   class="normal-list-head">Start Date</th>
        <th scope="col"   class="normal-list-head">End Date</th>
        <th scope="col"   class="normal-list-head">Status</th>
        <th scope="col"   class="normal-list-head"></th>
        </thead>
            <?php
           
            foreach ($allEpsidoes as $episode) {
                ?>
        <tr>
            <td class="normal-lsit-data"><?php echo $episode['episode_no']; ?></td>
            <td class="normal-lsit-data"><?php $startDate = $episode['start_date'];
                        if(!empty($startDate)) {
                            echo $startDate;
                        }else {
                            echo "---";
                        } ?></td>
            <td class="normal-lsit-data"><?php $endDate = $episode['end_date'];
                        if(!empty($endDate)) {
                            echo $endDate;
                        }else {
                            echo "---";
                        } ?></td>
            <td class="normal-lsit-data">
                        <?php

                        if($episode['status'] == "0") {
                            echo "Open";
                        }else if($episode['status'] == "1") {
                            echo "Completed";
                        }else if($episode['status'] == "2") {
                            echo "Referred";
                        }else if($episode['status'] == "3") {
                            echo "Defaulted";
                        }else if($episode['status'] == "4") {
                            echo "To be continued";
                        }else if($episode['status'] == "5") {
                            echo "Canceled";
                        }
                        ?>
            </td>

            <td class="normal-lsit-data"><a href="<?php echo url_for('registration/showCurrentVisit?eid='.$episode['episode_no'].'&pid='.$episode['patient_no']) ?>" class="td-link">Show Visits</a> </td>
        </tr>
                <?php } ?>
    </table>
    <?php } else { ?>
    	No records found
    <?php }?>
<br />
<table border="0">
            <tr>
                <td width="124"></td>
                 <td width="124"></td>                  
                <td width="350" valign="top">
                    <div class="demo">                      
                         <button id="btnBack">Back</button>
                    </div>
              </td>
            </tr>
        </table>
</div>
<script type="text/javascript">

    $(document).ready(function() {

        //Validate the form
        $("#frmInputCategory").validate({

            rules: {
                txtInputCategory: { required: true }
            },
            messages: {
                txtInputCategory: "Category name is required"
            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmInputCategory').submit();
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('registration/showMarkAndVisit') ?>";
        });

        $(function() {
            $("button", ".demo").button();

        });
	
        
		$(function() {
            $("#tabs").tabs({ selected: 0 });
        });
        //When click reset buton
        $("#btnClear").click(function() {
            document.forms[0].reset('');
        });

    });
</script>