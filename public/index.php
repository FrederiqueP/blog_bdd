<?php 

// Inclusion du fichier d'autoload de composer
require '../vendor/autoload.php';

// On démarre la session pour être certain qu'elle est démarrée
session_start();

// Inclusion des dépendances
include '../app/config.php';
include '../src/Core/Database.php';
include '../src/Core/AbstractModel.php';
include '../src/Model/ArticleModel.php';
include '../src/Model/CommentModel.php';
include '../src/Model/UserModel.php';
//include '../src/Model/UserModel.php';
include '../lib/functions.php';
// car dans routes.php se termine par un return $routes
$routes = include '../app/routes.php';


// Routing: quel contrôleur aller chercher ?
// $page = "home";
// if (isset($_GET['page'])) {
//     $page = $_GET['page'];
// }

$page = $_GET['page'] ?? 'home';

// switch ($page) {
//     case 'home' :
//         include '../controllers/home.php';
//         break;
// }

// include '../controllers/'.$routes[$page];

// dump($page);

// Routing : appeler un contrôleur spécifique à la page qu'on souhaite afficher en fonction de l'information contenue dans l'URL

// Si la page n'existe pas on fait une erreur 404

if (!array_key_exists($page, $routes)) {
    http_response_code(404);
    echo "Page introuvable";
    exit;
}

// On va chercher le contrôleur associé à la page
$controllerFile = $routes[$page];
include '../controllers/'.$controllerFile;




