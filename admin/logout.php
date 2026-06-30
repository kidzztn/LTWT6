<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

session_unset();
session_destroy();

$scriptDir = isset($_SERVER['SCRIPT_NAME'])
    ? rtrim(str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'])), '/')
    : '';
$adminBasePath = $scriptDir !== '' && $scriptDir !== '.' ? $scriptDir : '/LTWT6/admin';

header('Location: ' . $adminBasePath . '/login.php');
exit;
