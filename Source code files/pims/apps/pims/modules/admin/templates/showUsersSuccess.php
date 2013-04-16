<style>
    .bg {
        background-color: #ebf4fb;
}

</style>



<input type="checkbox" name="cb1" id="cb1" value="a" /> A<br />

<input type="checkbox" name="cb2" id="cb2" value="b" /> B<br />

<input type="checkbox" name="cb3" id="cb3" value="c" /> C<br />

<input type="checkbox" name="cb4" id="cb4" value="d" /> D<br />

<input type="checkbox" name="cb5" id="cb5" value="e" /> E<br />

<input type="button" id="myButton" value="which are checked?" />
<script type="text/javascript">

		$(document).ready(function() {

    $("#readyTest").corner("round 10px").parent().css('padding', '4px').corner("round 20px")
			//Validate the form
			 $("#frmSave").validate({

				 rules: {
				 	cmbModuleID: { required: true},
				 	chkView: { required: true }
			 	 },
			 	 messages: {
			 		cmbModuleID: "<?php echo "Module is required"?>",
			 		chkView: "<?php echo "View is required"?>"
			 	 }
			 });

			//When click reset buton
			$("#saveBtn").click(function() {
				$('#frmSave').submit();
			 });

			//When click reset buton
			$("#delBtn").click(function() {
				$('#frmDelete').submit();
			 });

			 //When Click back button
			 $("#btnBack").click(function() {
				 location.href = "<?php echo url_for("admin/showUserGroupList")?>";
				});

     oTable = $('#example').dataTable({
       
       "bJQueryUI": true,
		"sPaginationType": "full_numbers",
  
        "aaSorting": [[ 0, "asc" ]],
        "aoColumns": [
            { "sType": "numeric" },
            { "sType": "html" },
            null,
            null,
            null
        ],
        "iDisplayLength": 50,

        "oLanguage": {
            "sSearch": "Search all columns:"
         }
    });
   
    $("thead input").keyup( function () {
        /* Filter on the column (the index) of this element */
        oTable.fnFilter( this.value, $("thead input").index(this) );
    } );

    $('#divConfirmationDialog').dialog({
		autoOpen: false,
  resizable: false,
		width: 350,
		buttons: {
			"Ok": function() {
				location.href = 'http://www.yahoo.com';
			},
			"Cancel": function() {
				$(this).dialog("close");
			}
		}

	});

	$('#btnOpenDialog').click(function() {
		$('#divConfirmationDialog').dialog('open');
	});

$('#myButton').click(function() {

$('input:checkbox:checked').each(function(i) {

alert(this.value);

});

});

		 });
		</script>
<input type="button" id="btnOpenDialog" value="Open Dialog" />

<div id="divConfirmationDialog" title="Please answer this question">
Are you sure you want to go to Yahoo?
</div>
<div class="lsit-heading">User Groups</div>
<div class="list-actionbar">
    <a href="somelink" class="lst-buttons" style="clear:none;"><img class="image-button" src="/images/apply2.png" alt="" />&nbsp;Add New</a>
    <a href="somelink" class="lst-buttons" style="clear:none;"><img class="image-button" src="/images/clear.png" alt="" />Delete</a>
    <div class="messagebar"></div>
</div>
<div >
    <table id="example" class="display" border="0"cellpadding="0" cellspacing="0" >
 
    <thead>

        <tr >
            <th align="left" class="list-table-head"><input type="checkbox" /></th>
            <th width="20%" class="list-table-head">User Id</th>
            <th width="20%"  class="list-table-head">User Name</th>
            <th width="25%" class="list-table-head">Status</th>
            <th width="15%" class="list-table-head">User Group</th>
        </tr>
    </thead>
    <tbody>
        <?php foreach($users as $user) { ?>
        <tr class="gradeA">
            <td ><input type="checkbox" /></td>
            <td class="list-table-data"><a href="<?php echo url_for('admin/viewUserGroup?id='.$user['user_id'])?>" class="td-link"><?php echo $user['user_id']?></a></td>
            <td class="list-table-data"><?php echo $user['user_name'];?></td>
            <td class="list-table-data"><?php echo $user['status'];?></td>
            <td class="list-table-data"><?php echo  $user['user_group_id'];?></td>
        </tr>

            <?php }?>
    </tbody>
   
</table>
</div>