<?php

  function lang( $phrase){

    static $lang = array(
          // Navbar Link

        "Home_Admin"        => "Admin Area",
        "sections"          => "Categories",
        "Producer"          => "Items",
        "Members"           => "Users Edit",
        "COMMENTS"          => "Comments",
        "Statistics"        => "View Stats",
        "Logs"              => "Record",
        "Visit Shop"        => "Visit Web",
        ""                  =>    "",
    );

    return $lang[$phrase];
  }
