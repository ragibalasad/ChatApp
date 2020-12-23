<?php
include 'includes/session.inc.php';
include 'includes/model.inc.php';
date_default_timezone_set("Asia/Dhaka");
echo date("d M Y h:ia", strtotime("now"));
?>