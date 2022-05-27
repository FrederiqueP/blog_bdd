<?php 

// On démarre la session pour être certain qu'elle est démarrée
session_start();

// Inclusion des dépendances 
include '../app/config.php';
include '../lib/functions.php';

// Initialisation pour les pages phtml
$titlePage = 'Connexion Utilisateur';
$template = 'login';

// Initialisations
$email = '';
$error = '';

// Si le formulaire est soumis...
if (!empty($_POST)) {
    // On récupère les données du formulaire
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    // On vérifie le user à l'aide de l'email
    $user = checkUser($email, $password);
    var_dump($user);
    if ($user) {
        $error = 'Trouvé';
        // Enregistrement du user en session
        registerUser($user['idUser'], $user['firstname'], $user['lastname'], $user['email'], $user['role']);

        // Redirection
        header('Location: home.php');
        exit;
    } else {
        $error = 'Identifiants incorrects';
    }
}


// Affichage: inclusion du template
include '../templates/base.phtml';