<?php

    include "connect.php";  //connect

    //Routes

    $tpl  = "includes/templates/";    // Template Directory
    $lang = "includes/languages/"; // Language Directory
    $func = "includes/functions/"; // Function Directory
    $css  = "layout/css/";           // Css Directory
    $js   = "layout/js/";           // Js Directory

    // Include The Important Files

    include $func . "functions.php";
    include $lang . "eng.php";
    include $tpl . "header.php";

      //Include Navbar On All Pages Expect The One Whit $noNavbar Vairable

    if(!isset($noNavbar)) { include $tpl . "navbar.php"; }
