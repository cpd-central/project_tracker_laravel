<?php

  function check_project_box($type, $typeArray) {
    if(isset($typeArray)) {
      if(in_array($type, $typeArray)) {
        echo 'checked';
      }
    }
  }

  

?>











