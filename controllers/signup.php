<?php 

// Initialisations
$errors = [];
$firstname = '';
$lastname = '';
$email = '';
$titlePage = 'Inscription';

// Création de l'objet UserModel
$userModel = new UserModel();

// @TODO je ne devrais pas être ici si je suis connecté

// Si le formulaire est soumis...
if (!empty($_POST)) {

    // On récupère les données du formulaire
    $firstname = strip_tags(trim($_POST['firstname']));
    $lastname = strip_tags(trim($_POST['lastname']));
    $email = strip_tags(trim($_POST['email']));
    $password = $_POST['password'];
    $passwordConfirm = $_POST['password_confirm'];

    // On valide les données (titre et contenu obligatoires)
    if (!strlen($firstname)) {
        $errors['firstname'] = 'Le champ "Prénom" est obligatoire';
    }

    if (!strlen($lastname)) {
        $errors['lastname'] = 'Le champ "Nom" est obligatoire';
    }

    if (!strlen($email)) {
        $errors['email'] = 'Le champ "Email" est obligatoire';
    }
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Email invalide';
    }
    elseif ($userModel->getUserByEmail($email)) {
        $errors['email'] = 'Un compte existe déjà avec cet email';
    }

    if (strlen($password) < 8) {
        $errors['password'] = 'Le mot de passe doit comporter au moins 8 caractères';
    }
    elseif ($password != $passwordConfirm) {
        $errors['password-confirm'] = 'Le mot de passe de confirmation ne correspond pas';
    }

    // Si tout est OK (pas d'erreurs)...
    if (empty($errors)) {

        // Hashage du mot de passe
        $hash = password_hash($password, PASSWORD_DEFAULT);

        // On enregistre l'article
        $userModel->addUser($firstname, $lastname, $email, 'USER', $hash);
        // @TODO stocker 'USER' dans une constante de configuration 

        // On redirige l'internaute (pour l'instant vers une page de confirmation)
        header('Location: ' . buildUrl('home'));
        exit;
    }
}

// Inclusion du template
$template = 'signup';
include "../templates/base.phtml";