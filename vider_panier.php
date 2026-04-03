<?php
session_start();

unset($_SESSION["panier"]);

header("Location: commander.php");
exit();