<?php 
include_once(__DIR__ . "/../../Classes/log.php");

session_start();
if ($_SERVER['HTTP_HOST'] != 'localhost')
{
    log::debug("login check" . $_SESSION['userName']);
    if(!isset($_SESSION['userName'])){
        log::error("login error", $_SESSION['userName']);


        header( "Location: /timeout.php" );
        exit();
    } 
    // if( $_SESSION['userDiv'] == 2){
    //     log::error("login error", $_SESSION['userDiv']);
    //     header( "Location: /index.php" );
    //     exit();

    // }
}

?>
