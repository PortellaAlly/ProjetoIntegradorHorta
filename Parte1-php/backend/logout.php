<?php
session_start();
session_destroy();
header("Location: Parte1-php\frontend\pages\login.html");
exit();
?>