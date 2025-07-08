<?php
session_start();
$pid = base64_decode($_GET['pid']);
include($pid);
?>
