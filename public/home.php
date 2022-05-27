<?php 

session_start();
var_dump('-------  la session ----->>>>>>');
var_dump($_SESSION);

// Inclusion des dépendances 
include '../app/config.php';
include '../lib/functions.php';
include '../src/Core/Database.php';
include '../src/Model/ArticleModel.php';


// Initialisation pour les pages phtml
$css = '<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css" rel="stylesheet">';
$titlePage = 'Accueil - Bienvenue';
$template = 'home';

// Traitements : récupérer les articles

// $articles = getAllArticles();
$articleModel = new ArticleModel();
$articles = $articleModel->getAllArticles();


// on teste tout de suite avec un var_dump 
// avant de voir sur html
// var_dump($articles);

// echo '<pre>';
// var_dump($articles);
// echo '</pre>';

// Affichage: inclusion du template
include '../templates/base.phtml';