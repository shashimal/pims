<?php 
$rights = $_SESSION['arrRights'];
$modNames = $_SESSION['modName'];
?>
<?php if(!empty ($patientCategory)) { ?>
<div id="tabs" class="div-tabs">
    <div >
        <ul>
            <li><a href="#tabs-1">Patient Category</a></li>
        </ul>
        <br />
        <form name="frmPatientCategory" id="frmPatientCategory" method="post" action="<?php echo url_for('registration/updatePatientCategory') ?>/">
            <input type="hidden" id="txtPatientCategoryCode" name="txtPatientCategoryCode" value="<?php  echo $patientCategory[0]['category_id'];?>" />
            <table width="487" >
                <tr>
                    <td width="125">Category Name<span class="required">*</span></td>
                    <td width="350"> <input type="text" id="txtPatientCategory" name="txtPatientCategory"  tabindex="1" value="<?php echo $patientCategory[0]['patient_category'];  ?>" /></td>
                </tr>
                <tr>
                    <td>Description</td>
                    <td><textarea name="txtDescription" id="txtDescription" rows="3" cols="30" tabindex="2"   ><?php  echo $patientCategory[0]['description']; ?></textarea></td>
                </tr>               
            </table>
        </form>
        <table border="0">
            <tr>
                <td width="124"></td>
                <td width="350" valign="top">
                    <div class="demo">
                        <?php if(!empty($rights[$modNames['Registration']]['edit'])) { ?><button id="btnSave" style="height:25px;">Save</button><?php }?>
                        <button id="btnClear" style="height:25px;">Clear</button>
                        <button id="btnBack">Back</button>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</div>
    <?php } ?>
<script type="text/javascript">

    $(document).ready(function() {

        //Validate the form
        $("#frmPatientCategory").validate({

            rules: {
                txtPatientCategory: { required: true }
            },
            messages: {
                txtPatientCategory: "Category name is required"
            }
        });

        // When click edit button
        $("#btnSave").click(function() {
            $('#frmPatientCategory').submit();
        });

        //When Click back button
        $("#btnBack").click(function() {
            location.href = "<?php echo url_for('registration/showPatientCategoryList') ?>";
        });

        function clearFormElements() {

            $("#frmPatientCategory").find(':input').each(function() {

                switch(this.type) {
                    case 'text':
                    case 'textarea':
                        $(this).val('');
                        break;
                }

            });
        }

        $(function() {
            $("button", ".demo").button();

        });


        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });
        //When click reset buton
        $("#btnClear").click(function() {
            clearFormElements();
        });

    });
</script>