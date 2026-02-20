<?php
session_start();
unset($_SESSION['admin_logged']);
header('Location: admin_login.php');
exit;