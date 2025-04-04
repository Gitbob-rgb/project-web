<?php
session_start();

function checkAccess($roles = []) {
    if (!isset($_SESSION['role']) || !in_array($_SESSION['role'], $roles)) {
        header("Location: acces_refuse.php");
        exit;
    }
}
?>
