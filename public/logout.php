<?php
    session_start();
    
    // Inclusion des dépendances 
    include '../lib/functions.php';

    logout();
    header('location: home.php');
    exit;
?>