<?php
session_start();
session_destroy();
header("Location: index.php?controller=auth&action=login");
exit();
?>