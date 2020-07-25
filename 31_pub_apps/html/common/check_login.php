<?php 
include_once(__DIR__ . "/common.php");
session_start();
if ($_SERVER['HTTP_HOST'] != 'localhost')
{
    log::debug("login check" . $_SESSION['userName']);
    if(!isset($_SESSION['userName'])){
        log::error("login error", $_SESSION['userName']);


        header( "Location: /timeout.php" );
        exit();
    } 

    // }
    $groupID = $_SESSION['groupID'];
    $getGroupID = $_SESSION['GetGroupId'];
}

?>
