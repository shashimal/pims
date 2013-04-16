<div id="tabs1" class="div-tabs" style="width:960px">
 <ul>
        <li><a href="#tabs-1">Episodes History</a></li>
    </ul>

    <table class="normal-table" cellspacing="0" style="margin-left: 3px;margin-top: 10px; ">
        <thead>
        <th scope="col"   class="normal-list-head">Episode No</th>
        <th scope="col"   class="normal-list-head">Start Date</th>
        <th scope="col"   class="normal-list-head">End Date</th>
        <th scope="col"   class="normal-list-head">Status</th>
        <th scope="col"   class="normal-list-head"></th>
        </thead>
            <?php
            print_r($allEpsidoes);
            foreach ($allEpsidoes as $episode) {
                ?>
        <tr>
            <td class="normal-lsit-data"><?php echo $episode['episode_no]']; ?></td>
            <td class="normal-lsit-data"><?php $startDate = $episode['start_date'];
                        if(!empty($startDate)) {
                            echo $startDate;
                        }else {
                            echo "---";
                        } ?></td>
            <td class="normal-lsit-data"><?php $endDate = $episode['end_date'];
                        if(!empty($endDate)) {
                            echo $episode->getEndDate();
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

            <td class="normal-lsit-data"><a href="<?php //echo url_for('consultancy/viewEpisodeHistory?mode=view&eid='.$episode->getEpisodeNo().'&pid='.$patientNo) ?>" class="td-link">Show History</a> </td>
        </tr>
                <?php } ?>
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
            location.href = "<?php echo url_for('consultancy/showStdInputCategoryList') ?>";
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