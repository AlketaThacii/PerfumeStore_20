<?php
session_start();
session_unset();
session_destroy();

header("Location: /PerfumeStore_20/index.php");
exit();
?>