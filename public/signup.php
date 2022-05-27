<?php 

// Inclusion des dépendances 
include '../lib/functions.php';

// Initialisation pour les pages phtml
$titlePage = 'Compte utilisateur';
$template = 'signup';

// Initialisations
$firstname = '';
$lastname = '';
$email = '';
$hash = '';

$errors = [];

// Si le formulaire est soumis...
if (!empty($_POST)) {

    // On récupère les données du formulaire
    $firstname = trim($_POST['firstname']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $password_confirm = trim($_POST['password_confirm']);

    // On valide les données (titre et contenu obligatoires)
    if (!$firstname) {
        $errors['firstname'] = 'Le champ "Prénom" est obligatoire';
    }

    if (!$lastname) {
        $errors['lastname'] = 'Le champ "Nom" est obligatoire';
    }

    if (!$email) {
        $errors['email'] = 'Le champ "Email" est obligatoire';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email']  = 'Format de mail invalide';
    }
    elseif (emailExists($email)) {
        $errors['email']= 'Cet email est déjà enregistré';
    }
    
    if (!$password) {
        $errors['password'] = 'Le champ "Mot de passe" est obligatoire';
    }
    elseif (strlen($password) < 8) {
        $errors['password']= 'Mot de passe au moins 8 caractères';
    }

    if (!$password_confirm) {
        $errors['password_confirm'] = 'Le champ "Confirmation de mot de passe" est obligatoire';
    }
    elseif ($password != $password_confirm) {
        $errors['password']= 'les mots de passe sont différents';
    }

    // Si tout est OK (pas d'erreurs)...
    if (empty($errors)) {

        // hasher le mot de passe avant d'enregistrer l'utilisateur
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // On enregistre l'utilisateur
        addUser($firstname, $lastname, $email, $hash);

        // On redirige l'internaute (pour l'instant vers une page de confirmation)
        header('Location: signup.php');
        exit;
    }
}


// Affichage: inclusion du template
include '../templates/base.phtml';