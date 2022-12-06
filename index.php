<?php
     ob_start();
     session_start();

     $pageTitle = 'Home Page';

     include "init.php";

     ?>
      <div class="container top-page">
       <div class="row">
      <?php
               $allItems = getAllFrom('items', 'Item_ID', 'where Approve = 1');
                foreach($allItems as $item) {
                    echo '<div class="col-sm-9 col-md-3">';
                        echo '<div class="thumbnail item-box">';
                        echo '<span class="price-tag">$' . $item['Price'] . '</span>';        
                        echo '<img src="layout\images\appel-13.jpg" alt="" />';
                        echo '<div class="caption">';
                        echo '<h3><a href="itemsinfo.php?itemid='. $item['Item_ID'] .'">'  . $item['Name'] . '</a></h3>';
                        echo '<p>' . $item['Description'] .'</p>';
                        echo '<div class="date">' . $item['Add_Date'] .'</div>';
                        echo'</div>';
                        echo'</div>';
                    echo'</div>';
                    
                }
     ?>
        </div>
    </div>
      <?php

     include $tpl . "footer.php";
     ob_end_flush();
?>
