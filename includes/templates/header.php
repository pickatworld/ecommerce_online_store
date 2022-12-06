<!DOCTYPE html>
<html>
<header>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?php getTitle() ?></title>

    <link rel="stylesheet" href="<?php echo $css; ?>bootstrap.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>font-awesome.min.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery-ui.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>jquery.selectBoxIt1.css" />
    <link rel="stylesheet" href="<?php echo $css; ?>Frontend.css" />
</header>

<body>
    <div class="upper-bar">
        <div class="container">

            <?php 
            if (isset($_SESSION['user'])) {
                echo '<strong>Welcome</strong>' ;
                $userStatus = checkUserStatus($_SESSION['user']);
                if($userStatus == 1) {
                    echo ' Your User Need To Active By Admin ';
                }

            ?>
            <span class="pull-right">
                <div class="btn-group" role="group">
                <img class="img-responsive img-thumbnail circular_imag" src="layout\images\appel-13.jpg" alt="" />
                    <button id="btnGroupDrop1" type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                    <?php echo $_SESSION['user'] ?>
                    </button>
                    <ul class="dropdown-menu" aria-labelledby="btnGroupDrop1">
                    <li><a class="dropdown-item" href="Profile.php">Edit Profile</a></li>
                    <li><a class="dropdown-item" href="newad.php">Create New Item</a></li>
                    <li><a class="dropdown-item" href="profile.php#my-items">My Items</a></li>
                    <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>
                </div>
                
                </div>
            </span>
            <?php
                
            } else {
            ?>

            <a href="login.php">
                <span class="pull-right"> Login / Signup </span>
            </a>
            <?php } ?>
        </div>
    </div>
    
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php"><?php echo lang("Home_Page") ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav navbar-right me-auto mb-2 mb-lg-0">

                    <?php
                    foreach (getCat() as $cat) {
                        echo 
                        '<li>
                                <a class="nav-link" href="categories.php?pageid=' . $cat['ID'] .'">
                                ' . $cat['Name'] . '
                                </a>
                        </li>';
                    }
                    ?>

                    
                </ul>

            </div>
        </div>
    </nav>