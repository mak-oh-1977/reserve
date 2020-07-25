<?php
phpinfo();
session_start();

echo "save_handler=" . ini_get("session.save_handler") . "\n";
echo "save_path=" . ini_get("session.save_path") . "\n";
echo "session_id=" . session_id() . "\n";

$_SESSION['libname'] = "PhpRedis";
?>
