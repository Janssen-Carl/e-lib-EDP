<?php
/**
 * Logout Handler
 * Clears session and redirects to login
 */
session_start();
session_destroy();

// Clear cookies
setcookie('accessToken', '', time() - 3600, '/');
setcookie('userData', '', time() - 3600, '/');

// Redirect to login
header('Location: /pages/login.php');
exit;
