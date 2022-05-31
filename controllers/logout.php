<?php 

// On déconnecte l'utilisateur
logout();

// On le redirige vers l'accueil
header('Location: ' . buildUrl('home'));
exit;

