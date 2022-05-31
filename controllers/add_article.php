<?php 


// Initialisation pour les pages phtml
$template = 'add_article';
$titlePage = 'Formulaire ajout article';

// Initialisations
$errors = [];

// Si le formulaire est soumis...
if (!empty($_POST)) {

    // On récupère les données du formulaire
    $title = trim($_POST['title']);
    $abstract = trim($_POST['abstract']);
    $content = trim($_POST['content']);
    $image = trim($_POST['image']);

    // On valide les données (titre et contenu obligatoires)
    if (!$title) {
        $errors['title'] = 'Le champ "Titre" est obligatoire';
    }

    if (!$content) {
        $errors['content'] = 'Le champ "Contenu" est obligatoire';
    }

    // Si tout est OK (pas d'erreurs)...
    if (empty($errors)) {

        // On enregistre l'article 
        // addArticle($title, $abstract, $content, $image);
        $fkUserId = getUserId();
        $fkCategoryId = 1;

        $articleModel = new ArticleModel();
        $articleModel->addArticle($title, $abstract, $content, $image, $fkUserId, $fkCategoryId);

        // On redirige l'internaute (pour l'instant vers une page de confirmation)
       //  header('Location: admin.php');

        header('Location: ' . buildUrl('admin'));
        exit;
    }
}

// Inclusion du template
include '../templates/base_admin.phtml';