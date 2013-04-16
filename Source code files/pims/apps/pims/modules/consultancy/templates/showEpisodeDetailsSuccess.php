<?php 
$rights = $_SESSION['arrRights'];
$modNames = $_SESSION['modName'];
?>
<div id="tabs" class="div-tabs">
    <div >

        <ul>
            <li><a href="#tabs-1">Episode Details</a></li>
        </ul>
        <div class="message">
            <?php if ($sf_user->hasFlash('notice')): ?>
                <div class="flash_notice"><?php echo $sf_user->getFlash('notice') ?></div>
            <?php endif ?> <?php if ($sf_user->hasFlash('error')): ?>
                    <div class="flash_error"><?php echo $sf_user->getFlash('error') ?></div>
            <?php endif ?>
                </div>
                <br />
                <form name="frmSearchBox" id="frmSearchBox" method="post" action="<?php echo url_for('consultancy/viewEpisodeHistory') ?>">
            <input type="hidden" name="mode" value="search" />
            <table width="487" >
                <tr>
                    <td width="125">Patient No<span class="required">*</span></td>
                    <td width="350"> <input type="text" size="20" name="txtPatientNo" id="txtPatientNo" value=""   />   </td>
                </tr>               

            </table>

        </form>
         <?php if(!empty($rights[$modNames['Consultancy']]['edit'])) { ?>
        <table border="0">
            <tr>
                <td width="124"></td>
                <td width="350" valign="top">
                    <div class="demo">
                        <button id="btnSearch" >Search</button>
                        <button id="btnClear" >Clear</button>
                    </div>
                </td>
            </tr>
        </table>
        <?php }?>
    </div>
</div>



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
        $("#btnSearch").click(function() {
            $('#frmSearchBox').submit();
        });

        $(function() {
            $("#tabs").tabs({ selected: 0 });
        });

        $(function() {
            $("button", ".demo").button();

        });

        //When click reset buton
        $("#btnClear").click(function() {
            document.forms[0].reset('');
        });

    });
</script>