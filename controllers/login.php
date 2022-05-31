<?php 

// @TODO je ne devrais pas être ici si je suis connecté

// Initialisations
$email = '';
$titlePage = 'Connexion';
$error = '';

// Si le formulaire est soumis...
if (!empty($_POST)) {

    // On récupère les données du formulaire
    $email = $_POST['email'];
    $password = $_POST['password'];

    // On vérifie les identifiants
    $user = checkUser($email, $password);

    // On a trouvé l'utilisateur, les identifiants sont corrects...
    if ($user) {

        // Enregistrement du user en session
        registerUser($user['idUser'], $user['firstname'], $user['lastname'], $user['email'], $user['role']);
    
        // Redirection pour le moment vers la page d'accueil du site
        header('Location: ' . buildUrl('home'));
        exit;
    } 
        
    $error = 'Identifiants incorrects';
}

// Inclusion du template
$template = 'login';
include "../templates/base.phtml";