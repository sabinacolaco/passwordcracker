<?php
include('main.php');
$main = new Main();
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) &&  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
    if (!empty($_POST['token']) && isset($_SESSION['token'])) {
        if ($_POST['token'] != $_SESSION['token'] || !isset($_SESSION['token'])) {
            die("Unauthorized source!");
        }
        else {
            echo $main->crackPassword();
        }
    }
} 
?>