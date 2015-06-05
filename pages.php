<?php
	
	$SERVER="http://localhost/gsoc/dnvtools/";
	$SERVER_LOC="/var/www/html/gsoc/dnvtools/";

	//links to pages
	$pages["intro"]=$SERVER."sidemenu?page=intro";
	$pages["depandancy"]=$SERVER."sidemenu?page=depandancy";
	$pages["database"]=$SERVER."sidemenu?page=database";
	$pages["version_skew_report"]=$SERVER."sidemenu?page=version_skew_report";

	//actual addresses on the server
	$page_locs["intro"]=$SERVER_LOC."sidemenu/intro.php";;
	$page_locs["depandancy"]=$SERVER_LOC."sidemenu/depandancy.php";;
	$page_locs["database"]=$SERVER_LOC."sidemenu/database.php";;
	$page_locs["version_skew_report"]=$SERVER_LOC."sidemenu/version_skew_report.php";;

?>