<?php

  function lang( $phrase){

    static $lang = array(
          // Navbar Link

        "Home_Page"  => "Home",
        "sections"    => "Categories",
        "Producer"    => "Items",
        "Members"     => "Users Edit",
        "COMMENTS"     => "Comments",
        "Statistics"  => "View Stats",
        "Logs"        => "Record",
        ""            =>    "",
    );

    return $lang[$phrase];
  }
