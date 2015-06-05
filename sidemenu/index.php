<?php

include('../pages.php');

$page="intro";
extract($_GET);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="Dependancy and Version tracking tools for OpenDaylight project">

<title>DnvTools</title>


<link rel="stylesheet" href="css/pure-min.css">

  
<!--[if lte IE 8]>
    <link rel="stylesheet" href="css/layouts/side-menu-old-ie.css">
<![endif]-->
<!--[if gt IE 8]><!-->
    <link rel="stylesheet" href="css/layouts/side-menu.css">
<!--<![endif]-->
  

</head>
<body>

<div id="layout">
    <!-- Menu toggle -->
    <a href="#menu" id="menuLink" class="menu-link">
        <!-- Hamburger icon -->
        <span></span>
    </a>

    <div id="menu">
        <div class="pure-menu">
            <a class="pure-menu-heading" href="<?php echo $pages["intro"]; ?>">Tools</a>

            <ul class="pure-menu-list">
                <li class="pure-menu-item"><a href="<?php echo $pages["dependancy"]; ?>" class="pure-menu-link">Dependancy<br>Graph</a></li>
                <li class="pure-menu-item"><a href="<?php echo $pages["version_skew_report"]; ?>" class="pure-menu-link">Version Skew</a></li>
                <li class="pure-menu-item"><a href="<?php echo $pages["database"]; ?>" class="pure-menu-link">Current<br>Database</a></li>
            </ul>
        </div>
    </div>

    <div id="main">
        <?php
            if($page==="version_skew_report" || $page==="database" || $page==="dependancy"){
                include($page_locs[$page]);
            } else {
                include($page_locs["intro"]);
            }
        ?>
    </div>
</div>

<script src="js/ui.js"></script>
<script src="js/jquery.js"></script>
<script src="js/process.php"></script>


</body>
</html>
