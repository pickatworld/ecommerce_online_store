<?php


  $do = isset($_Get['do']) ? $_Get['do'] :'Manage';

    //If The Page Is Main Page

    if ($do == 'Mange') {

      echo 'welcome You Are In Manage Page';
      echo '<a href="?do=Insert">Add New Category +</a>';

    } elseif ($do == 'Add') {

      echo 'Welcome You Are In Add Page';

    } elseif ($do == 'Insert') {

      echo 'welcome You Are In Insert Category Page';

    } else {

      echo 'Error There\'s No Page Whit This Name';
    }



 ?>
