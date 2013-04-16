<?php

$inputCategoryCode = $_GET['id'];

$bjStdInput = new StdInput();
$stdInputs = $bjStdInput->getStdInputsByInputCategory($inputCategoryCode);

$val = " <select id='cmbInput' name ='cmbInput' class='formSelect'  onchange='selectProject();' >";

                
                  foreach ($stdInputs as $stdInput) {
               
                	$val = $val. " <option value = ".$stdInput['input_code']. ">" . $stdInput['input_name']."</option>";
              
                       
                    }
                    
              
 $val = $val ."</select>";
 
 echo  $val;
  ?>