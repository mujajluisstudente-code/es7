<?php
session_start();
session_destroy();
header("Location: login.html?msg=Logout+effettuato+con+successo&color=green");
exit;
?>