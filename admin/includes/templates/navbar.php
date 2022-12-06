<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="dashboard.php"><?php echo lang ("Home_Admin") ?></a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
    data-bs-target="#navbarSupportedContent"
    aria-controls="navbarSupportedContent"
    aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>

    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li><a class="nav-link" href="categories.php"><?php echo lang("sections") ?></a></li>
          <li><a class="nav-link" href="items.php"><?php echo lang("Producer") ?></a></li>
          <li><a class="nav-link" href="users.php"><?php echo lang("Members") ?></a></li>
          <li><a class="nav-link" href="comments.php"><?php echo lang("COMMENTS") ?></a></li>
          <li><a class="nav-link" href="#"><?php echo lang("Statistics") ?></a></li>
          <li><a class="nav-link" href="#"><?php echo lang("Logs") ?></a></li>
          <li><a class="nav-link" href="../index.php"><?php echo lang("Visit Shop") ?></a></li>


        <li class="nav-item dropdown ">
          <a class="nav-link  dropdown-toggle" href="#" id="navbarDropdown"
           role="button" data-bs-toggle="dropdown" aria-expanded="false">Amr</a>
          <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
            <li><a class="dropdown-item" href="users.php?do=Edit&UserID=<?php echo $_SESSION['ID'] ?>">Edit Profile</a></li>
            <li><a class="dropdown-item" href="#">settings</a></li>
            <li><hr class="dropdown-divider"></li>
            <li><a class="dropdown-item" href="logout.php">Logout</a></li>
          </ul>
        </li>
      </ul>

    </div>
  </div>
</nav>
