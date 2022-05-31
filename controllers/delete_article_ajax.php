<?php 

// Vérification du rôle
if (!hasRole(ROLE_ADMIN)) {
    http_response_code(403);
    echo 'Accès interdit';
    exit;
}

// Validation et récupération de l'id de l'article à supprimer dans l'URL
if (!array_key_exists('id', $_GET) || !$_GET['id']) {

    http_response_code(404);
    echo 'Article introuvable';
    exit; // Si pas d'id dans l'URL => message d'erreur et on arrête tout ! 
}

// On récupère l'id de l'article à afficher depuis la chaîne de requête
$idArticle = $_GET['id'];

// On va chercher l'article correspondant
$articleModel = new ArticleModel();
$article = $articleModel->getOneArticle($idArticle);

// On vérifie qu'on a bien récupéré un article, sinon => 404
if (!$article) {

    http_response_code(404);
    echo 'Article introuvable';
    exit; // Si pas d'article => message d'erreur et on arrête tout ! 
}

// Suppression de l'article
$articleModel->deleteArticle($idArticle);

// On retourne l'id de l'article qui a été supprimé en JSON
echo json_encode(['id' => $idArticle]);
exit;

