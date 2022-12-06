
<?php 

        session_start();
        $pageTitle = 'Categories';

        include "init.php";
 ?>
 
        <div class="container">
            <h1 class="text-center"><?php echo $pageTitle?></h1>
       <div class="row">
      <?php
                foreach(getItems('Cat_ID', $_GET['pageid']) as $item) {
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

<?php include $tpl . "footer.php"; ?>
