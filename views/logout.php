<?php
require_once '../classes/Session.php';
$session = new Session();
    $session->destroy();
    header("Location: ../index.php");
    exit();
?>  