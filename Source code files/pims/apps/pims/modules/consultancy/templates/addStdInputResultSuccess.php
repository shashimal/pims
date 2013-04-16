<script type="text/javascript">

    function displayAddLayer() {

        if(document.getElementById('result').style.display == "none") {
            document.getElementById('result').style.display = "block";
            document.getElementById('state').value = "new";
        }
    }

    function hideAddLayer() {

        if(document.getElementById('result').style.display == "block") {
            document.getElementById('txtInputResult').value = "";
            document.getElementById('result').style.display = "none";
        }
    }

    function editResult(resultId, resultName) {

        if(document.getElementById('result').style.display == "none") {
            document.getElementById('result').style.display = "block";
        }

        document.getElementById('state').value = "edit";
        document.getElementById('txtInputResult').value = resultName;
        document.getElementById('resultId').value = resultId;
    }

    function returnDelete() {
        $check = 0;
        with (document.frmDelete) {
            for (var i=0; i < elements.length; i++) {
                if ((elements[i].type == 'checkbox') && (elements[i].checked == true) && (elements[i].name == 'chkLocID[]')){
                    $check = 1;
                }
            }
        }

        if ($check == 1){

            var res = confirm("Are you sure want to delete this record ?");

            if(!res) return;
            document.frmDelete.submit();
        }else{
            alert("Please select a record to delete");
            return;
        }
    }

</script>

<div id="tabs" class="div-tabs">
  <div >
        <ul>
            <li><a href="#tabs-1">Assign to Input Results</a></li>
        </ul>
		<div id="divConfirmationDialog" title="Please answer this question">
            Are you sure you want to delete this record?
        </div>
		<div class="message">
        <?php if ($sf_user->hasFlash('notice')): ?>
        <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
        <?php endif ?>

        <?php if ($sf_user->hasFlash('error')): ?>
        <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
        <?php endif ?>
    </div>
		<br />
        <table width="331" border="0" >
          <tr>
            <td width="164">Category Name </td>
            <td width="151"><?php echo $stdInput->getStdInputCategory();?></td>
          </tr>
		  <tr>
            <td>Input Name </td>
            <td><?php echo $stdInput->getInputName();?></td>
          </tr>
        </table>
  </div>
</div>

<div id="tabs1" class="div-tabs">
  <div >
        <ul>
            <li><a href="#tabs-1">Assign Results to Input</a></li>
        </ul>
		
		<br />
		<form action="<?php echo url_for('consultancy/deleteStdInputResult')?>" method="post" id="frmDelete" name="frmDelete">

                <input type="hidden" name="hdnTxtInput" value="<?php echo $stdInput->getInputCode();?>">
                <input type="hidden" name="hdnTxtInputCategory" value="<?php echo $stdInput->getInputCategoryCode();?>">
        <table cellspacing="0" cellpadding="5" border="0" width="100%" class="">
                <tbody>
                    <tr>
                      <td width="6%" ><input type='checkbox'  id="allCheck" name='allCheck' value='' class='checkbox innercheckbox'  /></td>
                        <td width="94%" ><strong>Results</strong></td>     
                    </tr>
                    <?php foreach ($stdInput->getStdInputResult() as $objStdResult)  { ?>
                    <tr>
                        <td ><input type='checkbox' name='chkLocID[]' class='checkbox innercheckbox' 
                                      value='<?php echo $objStdResult->getInputResultCode();?>' /></td>
                        <td > <?php
                                echo "<a class='td-link' href='#' onclick='editResult({$objStdResult->getInputResultCode()},\"{$objStdResult-> getResultDescription()}\");'>{$objStdResult-> getResultDescription()}</a>";
                                ?></td>
                       
                    </tr>
                    <?php }?>
                </tbody>
            </table>			
		</form>
		<table border="0">
			<tr>
				<td width="127"></td>
			    <td width="350" valign="top">
				<div class="demo">
					<button id="btnAdd" onclick="displayAddLayer();">Add</button>
					<button id="btnDelete">Delete</button>
					<button id="btnBack">Back</button>
				</div>
				</td>
			</tr>
		</table>
  </div>
</div>

<div id="result" style="display: none;">
<div id="tabs2" class="div-tabs">
  <div >
        <ul>
            <li><a href="#tabs-1">New Input Result</a></li>
        </ul>	
		<br />
		<form action="<?php echo url_for('consultancy/saveStdInputResult')?>" method="post" id="frmResult" name="frmResult">

                   <input type="hidden" name="txtInputCategory" value="<?php echo $stdInput->getInputCategoryCode();?>">
                    <input type="hidden" name="txtInput" value="<?php echo $stdInput->getInputCode();?>">
                    <input type="hidden" name="state" id="state" value="">
                    <input type="hidden" name="resultId" id="resultId" value="">
        <table width="331" border="0" >
          <tr>
            <td width="164">Result</td>
            <td width="151"><input type="text"class="form-input"  id="txtInputResult" name="txtInputResult" value="" tabindex="1"/></td>
          </tr>        
        </table>
		</form>
		<table border="0">
			<tr>
				<td width="127"></td>
			    <td width="350" valign="top">
				<div class="demo">
					<button id="btnSave">Save</button>
					<button id="btnReset" onclick="hideAddLayer();" >Cancel</button>					
				</div>
				</td>
			</tr>
		</table>
  </div>
</div>
</div>


<script type="text/javascript">

    $(document).ready(function() {

        //Validate the form
        $("#frmResult").validate({

            rules: {
                txtInputResult: { required: true }
            },
            messages: {
                txtInputResult: "Result name is required"
            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmResult').submit();
        });

        // When Click Main Tick box
        $("#allCheck").change(function() {
            if ($('#allCheck').attr('checked')) {
                $('.innercheckbox').attr('checked','checked');
            }else{
                $('.innercheckbox').removeAttr('checked');
            }

        });

        //When click reset buton
        $("#resetBtn").click(function() {
            document.forms[0].reset('');
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('consultancy/showStdInputList') ?>";
        });

        $(function() {
            $("button", ".demo").button();

        });
         //When click on the delete button
        $('#divConfirmationDialog').dialog({
            autoOpen: false,
            resizable: false,
            width: 350,
            buttons: {
                "Ok": function() {
                    $("#frmDelete").submit();
                },
                "Cancel": function() {
                    $(this).dialog("close");
                }
            }

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
		
        $('#btnDelete').click(function() {
            $('#divConfirmationDialog').dialog('open');
        });
    });
</script>