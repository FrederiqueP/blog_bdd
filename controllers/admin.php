<?php 

// sur toute les pages administrateur (CRUD)
if(!hasRole(ROLE_ADMIN)) 
{
    http_response_code(403);
    echo "Accès interdit";
    exit;
}

// Initialisation pour les pages phtml
$template = 'admin';
$script = '<script src="js/admin.js" defer></script>';
$css = '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">';

// Traitements : récupérer les articles
$articles = getAllArticles();

// on teste tout de suite avec un var_dump 
// avant de voir sur html
// var_dump($articles);

// Affichage: inclusion du template
include '../templates/base_admin.phtml';
