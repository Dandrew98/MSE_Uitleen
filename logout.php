<?php
// attach to the session
session_start();

// remove all session data
unset($_SESSION);

// destroy the session
session_destroy();

// send to the homepage
header('Location:login.php');
