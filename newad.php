<?php

     ob_start();
     session_start();

     $pageTitle = 'Create New Item';

     include "init.php";

     if (isset($_SESSION['user'])) {

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {

            $formErrors = array();

            $title      = filter_var($_POST['name'], FILTER_SANITIZE_SPECIAL_CHARS);
            $desc       = filter_var($_POST['description'], FILTER_SANITIZE_SPECIAL_CHARS);
            $price      = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_INT);
            $country    = filter_var($_POST['country'], FILTER_SANITIZE_SPECIAL_CHARS);
            $status     = filter_var($_POST['status'], FILTER_SANITIZE_NUMBER_INT);
            $category   = filter_var($_POST['Category'], FILTER_SANITIZE_NUMBER_INT);

            if(strlen($title) < 4) {
                $formErrors[] = 'Item Name Must Be At Least 4 Characters';
            }
            if(strlen($desc) < 5) {
                $formErrors[] = 'Item Description Must Be At Least 10 Characters';
            }
            if(strlen($country) < 2) {
                $formErrors[] = 'Item Country Must Be At Least 2 Characters';
            }
            if(empty($price)) {
                $formErrors[] = 'Item Price Must Be Not Empty';
            }
            if(empty($status)) {
                $formErrors[] = 'Item Status Must Be Not Empty';
            }
            if(empty($category)) {
                $formErrors[] = 'Item category Must Be Not Empty';
            }
            
            if (empty($formErrors)) {

                // Insert User Info In Database
                $stmt = $con->prepare("INSERT INTO
                                  items(Name, Description, Price, Country_Made, Status,  Add_Date, Users_ID, Cat_ID)
  
                                  VALUES(:aname, :adesc, :aprice, :acountry, :astatus,  now(), :auser, :acat)");
                $stmt->execute(array(
                    'aname'     => $title,
                    'adesc'     => $desc,
                    'aprice'    => $price,
                    'acountry'  => $country,
                    'astatus'   => $status,
                    'auser'     => $_SESSION['uid'],
                    'acat'      => $category
               ));

               if ($stmt) {
                $succesMag = 'Item Added';
               }
  
          }

        }

     ?>
     <h1 class="text-center">Create New Item</h1>
     <div class= "create-ad block">
        <div class="container">   
           <div class="card">
            <div div class="card-header text-white bg-primary">Create New Item</div>
             <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                            <form class="row g-3" action="<?php echo $_SERVER['PHP_SELF']?>" method="POST">
                        <div class="col-md-8">
                            <label class="form-label">Name</label>
                            <input type="text" name="name" class="form-control live" placeholder="Name Of The Item" required="required" data-class=".live-title" />
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control live" placeholder="Description The Item" required="required" data-class=".live-desc" />
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Price</label>
                            <input type="text" name="price" class="form-control live" placeholder="Price Of The Item" required="required" data-class=".live-price" />
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Country</label>
                            <input type="text" name="country" class="form-control live" placeholder="Country Of Made" required="required" data-class=".live-country" />
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Status</label>
                            <select name="status" required>
                                <option value="">...</option>
                                <option value="1">New</option>
                                <option value="2">Like New</option>
                                <option value="1">Used</option>
                                <option value="1">Very Old</option>
                            </select>
                        </div>
                        <div class="col-md-8">
                            <label class="form-label">Category</label>
                            <select name="Category" required>
                                <option value="">...</option>
                                <?php
                                $cats = getAllFrom('categories', 'ID');
                                    foreach ($cats as $cat) {
                                    echo "<option value='" . $cat['ID'] . "'>" . $cat['Name'] . "</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="col-md-8">
                            <button type="submit" class="btn btn-primary">Add Item</button>
                        </div>
                    </form>

                    </div>

                    <div class="col-md-4">
                     <div class="col-sm-6 col-md-8">
                         <div class="thumbnail item-box live-preview">
                         <span class="price-tag">
                             $<span class="live-price">0</span>
                         </span>
                         <img src="layout\images\appel-13.jpg" alt="" />
                         <div class="caption">
                         <h3 class="live-title">Title</h3>
                         <p class="live-desc">Description</p>
                         <h6 class="live-country">Country</h6>
                        </div>
                        </div>
                    </div>

                </div>

                <!--Start LoopIng Through Errors -->
                <?php
                    if (! empty($formErrors)) {
                        foreach ($formErrors as $error) {
                            echo '<div class="alert alert-danger">' . $error .'</div>';
                        }
                    }

                    if (isset($succesMag)) {
                        echo '<div class="alert alert-success ">' . $succesMag .'</div>';
                    }
                ?>
                <!--End LoopIng Through Errors -->

           </div>
          </div>
        </div>
      </div>
     <?php


     } else {
      header('Location: login.php');
      exit();
     }
     include $tpl . "footer.php";
     ob_end_flush();
?>
