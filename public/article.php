<?php

// Inclusion des dépendances
include '../app/config.php';
include '../lib/functions.php';
include '../src/Core/Database.php';
include '../src/Core/AbstractModel.php';
include '../src/Model/ArticleModel.php';
include '../src/Model/CommentModel.php';

// Initialisation pour les pages phtml
$template = 'article';
$titlePage = 'Accueil - Bienvenue';

// Traitements : récupérer l'article à afficher
$idArticle = $_GET['id'];

// Validation du paramètre id de l'URL
if (!array_key_exists('id', $_GET) || !$_GET['id'] || !ctype_digit($_GET['id'])) {

    http_response_code(404);
    echo 'Article introuvable';
    exit; // Si pas d'id dans l'URL => message d'erreur et on arrête tout ! 
}

// On récupère l'id de l'article à afficher depuis la chaîne de requête
// Convertir l'id en entier car pour la BDD c'est un entier et l'url est une chaine de caractères
$idArticle = (int) $_GET['id'];

// On va chercher l'article correspondant
// $article = getOneArticle($idArticle);
$articleModel = new ArticleModel();
$article = $articleModel->getOneArticle($idArticle);

//var_dump($article);

// On vérifie qu'on a bien récupéré un article, sinon => 404
if (!$article) {

    http_response_code(404);
    echo 'Article introuvable';
    exit; // Si pas d'article => message d'erreur et on arrête tout ! 
}


// Traitements : récupérer le commentaire
$errors = [];

if (!empty($_POST)) {

    $content = trim($_POST['content']);

    if (strlen($content) === 0) {
        $errors['content'] = 'Vous devez écrire un commentaire';
    }

    // S'il n'y a pas d'erreurs
    if (empty($errors)) {
        // Pour l'instant on met l'id du user en dur... 
        $idUser = getUserId();

        // Appel de la fonction insertComment()
        // insertComment($content, $idUser, $idArticle);

        $commentModel = new CommentModel();
        $commentModel->insertComment($content, $idUser, $idArticle);

        // @TODO redirection
        // On redirige l'internaute sur la même page ce qui va générer un GET (on perd les données du POST)
        header('Location: article.php?id='.$idArticle);
        exit;
    }
}



// On va chercher les commentaires correspondants à l'article 
// $comments = getCommentsByArticleId($idArticle);
$commentModel = new CommentModel();
$comments = $commentModel->getCommentsByArticleId($idArticle);


// Test du nombre d'instances de PDO
// var_dump(Database::getCountPDO());

// Affichage : inclusion du fichier de template
include '../templates/base.phtml';

