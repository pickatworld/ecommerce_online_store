<?php

    /*
    ==============================================
    == Manage Categories page
    == You Can Add | Edit | Delete Users From Here
    ==============================================
    */
    ob_start();   //Output Buffering Start

    session_start();

    $pageTitle = "Categories";

    if(isset($_SESSION["Username"])){

    include "init.php";

    $do = isset($_GET['do']) ? $_GET['do'] :'Mange';

    // Start Manage Page

    if ($do == 'Manage') {

      
    } elseif ($do == 'Add') {


    } elseif ($do == 'Insert') {


    } elseif ($do == 'Edit') {


    } elseif($do == 'Update') {


    } elseif ($do == 'Delete') {


    }


    include $tpl . "footer.php";

  } else {

    header("Location: index.php");
    exit();
  }

  ob_end_flush(); // Release The Output
  
?>
