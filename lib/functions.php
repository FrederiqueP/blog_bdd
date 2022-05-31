<?php 

// Constantes
const ROLE_USER = 'USER';
const ROLE_ADMIN = 'ADMIN';

//////////////// FONCTIONS ////////////////

function buildUrl (string $page,array $params=[]) :string{
    $url = 'index.php?page='.urlencode($page);
    foreach($params as $paramName => $paramValue) {
        $url.='&'.urlencode($paramName).'='.urlencode($paramValue);
    }   
    
    // ou bien
    // if ($params) {
    //      $url .= '&'.http_build_query($params);
    // }
    return $url;
}



/**
 * Créer l'objet PDO et le retourne
 */
function getPDOConnection()
{
    $bdd= DB_NAME;
    // var_dump($bdd);

    // Connexion à la base de données avec PDO
    $dsn = 'mysql:dbname='.DB_NAME.';host='.DB_HOST.';charset=utf8'; // DSN : Data Source Name (informations de connexion à la BDD)
    $user = DB_USER; // Utilisateur
    $password = DB_PASS; // Mot de passe
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, // Pour afficher les erreurs SQL
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC // Mode de récupération des résultats
    ];

    $pdo = new PDO($dsn, $user, $password, $options); // Création d'un objet de la classe PDO

    return $pdo;
}


/**
 * Récupère l'intégralité des articles ou un tableau vide
 * @return array - Le tableau d'articles
 */

function getAllArticles(): array
{
    $sql = 'SELECT *
            FROM article AS A
            ORDER BY A.createdAt DESC';

    $db = new Database();

    return $db->getAllResults($sql);
}

/////////////////////////////////////////
////////////// COMMENTS /////////////////
/////////////////////////////////////////

function insertComment(string $content, int $idUser, int $idArticle)
{
    // Connexion à la base de données
    $pdo = getPDOConnection();

    // Préparation de la requête
    $sql = 'INSERT INTO comment (content, createdAt,fkArticleId,fkUserId) VALUES (?,NOW(),?,?)';
    $pdoStatement = $pdo -> prepare($sql);
    $pdoStatement->execute([$content,$idArticle,$idUser]);

    // pas de fetch car c'est une requête d'action (et non une selection)

}


function getCommentsByArticleId(int $idArticle) {

    // Connexion à la base de données
    $pdo = getPDOConnection();

    // Préparation de la requête
    $sql = 'SELECT content, C.createdAt, firstname, lastname
            FROM comment  AS C
            INNER JOIN user AS U
            ON C.fkUserId = U.idUser
            WHERE fkArticleId = ?
            ORDER BY C.createdAt DESC';

    $pdoStatement = $pdo -> prepare($sql);
    $pdoStatement->execute([$idArticle]);
    // On récupère plusieurs lignes avec fetchAll()
    $comments = $pdoStatement->fetchAll();
    return $comments;

}

 
/**
 * Ajoute un article
 * @param string $title Le titre de l'article
 * @param string $abstract Le résumé de l'article
 * @param string $content Le contenu de l'article
 * @param string $title Le nom du fichier image de l'article
 * @return void
 */
function addArticle(string $title, string $abstract, string $content, string $image)
{
    // On commence par récupérer tous les articles
    $articles = getAllArticles();

    // Pour ajouter la date de création de l'article, on peut créer la date du jour au format américain yyyy-mm-dd grâce à la classe DatetimeImmutable et à sa méthode format()
    $today = new DateTimeImmutable();

    // On regroupe les informations du nouvel article dans un tableau associatif
    $article = [
        'id' => sha1(uniqid(rand(), true)),
        'title' => $title,
        'abstract' => $abstract,
        'content' => $content,
        'image' => $image,
        'createdAt' => $today->format('Y-m-d')
    ];

    // On ajoute le nouvel article au tableau d'articles
    $articles[] = $article;

    // On enregistre les articles à nouveua dans le fichier JSON
    saveJSON(FILENAME, $articles);
}


/**
 * Récupère UN article à partir de son identifiant
 * @param string $idArticle - L'identifiant de l'article à récupérer
 * @return null|array - null si l'id n'existe pas, sinon retourne l'
 */

//function getOneArticle(string $idArticle): bool | array

function getOneArticle(string $idArticle)
{

    $sql = 'SELECT *
    FROM article AS A
    WHERE idArticle = ?';

    $db = new Database();

    return $db->getOneResult($sql,[$idArticle]);
}


/**
 * Modifie un article
 * @param string $title Le titre de l'article
 * @param string $abstract Le résumé de l'article
 * @param string $content Le contenu de l'article
 * @param string $title Le nom du fichier image de l'article
 * @return void
 */
function editArticle(string $title, string $abstract, string $content, string $image, string $idArticle)
{
    // On récupère tous les articles
    $articles = getAllArticles();

    // On parcours le tableau d'articles à la recherche de l'article à modifier
    foreach ($articles as $index => $article) {

        // Si l'id de l'article courant est le bon...
        if ($article['idArticle'] == $idArticle) {

            // On modifie la case du tableau contenant l'article à modifier
            $articles[$index]['title'] = $title;
            $articles[$index]['abstract'] = $abstract;
            $articles[$index]['content'] = $content;
            $articles[$index]['image'] = $image;
            break;
        }
    }

    // On enregistre les articles à nouveau dans le fichier JSON
    saveJSON(FILENAME, $articles);
}


/**
 * Supprime un article à partir de son identifiant
 * @param string $idArticle - L'identifiant de l'article à supprimer
 */
function deleteArticle(string $idArticle)
{
    // On récupère tous les articles
    $articles = getAllArticles();

     // Initialisation d'une variable qui stockera l'indice de l'élément à supprimer
     $indexToDelete = null;

    // On parcours le tableau d'articles à la recherche de l'article à supprimer
    foreach ($articles as $index => $article) {
        // Si l'id de l'article courant est le bon...
        if ($article['idArticle'] == $idArticle) {
             // Je stocke l'indice de l'élément à supprimer
            $indexToDelete = $index;
            break;       
        }
    }      
    // SI j'ai bien trouvé l'élémentà supprimer...
    if (!is_null($indexToDelete)) {
        // ... je le supprime !
        array_splice($articles, $indexToDelete, 1);
    }
    
    // On enregistre les articles à nouveau dans le fichier JSON
    saveJSON(FILENAME, $articles);
}


/**
 * Ajoute un utilisateur
 * @param string $firstname Le nom de l'utilisateur
 * @param string $lastname Le prénom de l'utilisateur
 * @param string $email Le email de l'utilisateur
 * @param string $password Le mot de passe de l'utilisateur hashé
 * @return void
 */
function addUser(string $firstname, string $lastname, string $email, string $hash)
{
    // On commence par récupérer tous les utilisateurs
    $users = getAllUsers();

    // Pour ajouter la date de création de l'article, on peut créer la date du jour au format américain yyyy-mm-dd grâce à la classe DatetimeImmutable et à sa méthode format()
    $today = new DateTimeImmutable();

    // On regroupe les informations du nouvel article dans un tableau associatif
    $user = [
        'id' => sha1(uniqid(rand(), true)),
        'firstname' => $firstname,
        'lastname' => $lastname,
        'email' => $email,
        'password' => $hash,
        'createdAt' => $today->format('Y-m-d'),
        'role' => ROLE_USER
    ];

    // On ajoute le nouvel article au tableau d'articles
    $users[] = $user;

    // On enregistre les articles à nouveua dans le fichier JSON
    saveJSON(FILENAME2, $users);
}


/**
 * Vérifie qu'un email est présent dans le fichier des abonnés
 * @param string $email
 * @return bool
 */
function emailExists(string $email): bool
{
    // On commence par récupérer tous les utilisateurs
    $users = getAllUsers();
    foreach ($users as $user):
         if ($user['email'] == $email){
            return true;
        }
    endforeach;
    return false;
}


function getUserByEmail(string $email) 
{
    // Connexion à la base de données
    $pdo = getPDOConnection();

    // Exécution de la requête de sélection des users
    $sql = 'SELECT * FROM user WHERE email = ?';
    $pdoStatement = $pdo -> prepare($sql);
    $pdoStatement->execute([$email]);
    $user = $pdoStatement->fetch();
   return $user;
}




function checkUser(string $email, string $plainPassword)
{
    $user = getUserByEmail($email);
    if ($user) {
        $ok = password_verify($plainPassword, $user['hash']);
        if ($ok) 
        {
            // on retourne le user et pas true
            return $user;
        }
        else return false;
    }
    return false;
}


function registerUser(string $id, string $firstname, string $lastname, string $email, string $role) 
{
     // On commence par vérifier qu'une session est bien démarrée
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // Puis on enregistre les données de l'utilisateur en session
    $_SESSION['user'] = [
        'id'=>$id,
        'firstname'=>$firstname,
        'lastname'=>$lastname,
        'email'=>$email,
        'role' =>$role
    ];
   
}

/**
 * Détermine si l'utilisateur est connecté ou non
 * @return bool - true si l'utilisateur est connecté, false sinon
 */
function isConnected(): bool
{
    // On commence par vérifier qu'une session est bien démarrée
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    return array_key_exists('user', $_SESSION) && isset($_SESSION['user']);
}

/**
 * Déconnecte l'utilisateur
 */
function logout()
{
    // Si l'utilisateur est connecté...
    if (isConnected()) {

        // On efface nos données en session
        $_SESSION['user'] = null;

        // On ferme la session 
        session_destroy();
    }
}


/**
 * Retourne l'id de l'utilisateur connecté
 */
function getUserId()
{
    // Si l'utilisateur est connecté...
    if (!isConnected()) {
        return null;
    }

    return $_SESSION['user']['id'];
}

/**
 * Retourne le prénom de l'utilisateur connecté
 */
function getUserFirstname()
{
    // Si l'utilisateur est connecté...
    if (!isConnected()) {
        return null;
    }

    return $_SESSION['user']['firstname'];
}

/**
 * Retourne le nom de l'utilisateur connecté
 */
function getUserLastname()
{
    // Si l'utilisateur est connecté...
    if (!isConnected()) {
        return null;
    }

    return $_SESSION['user']['lastname'];
}

/**
 * Retourne l'email de l'utilisateur connecté
 */
function getUserEmail()
{
    // Si l'utilisateur est connecté...
    if (!isConnected()) {
        return null;
    }

    return $_SESSION['user']['email'];
}

/**
 * Retourne le rôle de l'utilisateur connecté
 */
function getUserRole()
{
    // Si l'utilisateur est connecté...
    if (!isConnected()) {
        return null;
    }

    return $_SESSION['user']['role'];
}

/**
 * Vérifie si l'utilisateur possède un rôle particulier
 */
function hasRole(string $role)
{
    if (!isConnected()) {
        return false;
    }

    return getUserRole() == $role;
}

/**
 * Vérifie si l'utilisateur possède un rôle particulier
 */
// function hasRole(string $role)
// {
//     if (!isConnected()) {
//         return false;
//     }

//  ---- utilisation de == qui est un operateur de comparaison 
//  ---- sur l'expression qui produit une valeur booleene
//  ---- retourne faux ou vrai sur cette comparaison
//     return $_SESSION['user']['role'] == $role;
// }




/**
 * Récupère des données stockées dans un fichier JSON
 * @param string $filepath - Le chemin vers le fichier qu'on souhaite lire
 * @return mixed - Les données stockées dans le fichier JSON désérialisées
 */
function loadJSON(string $filepath)
{
    // Si le fichier spécifié n'existe pas on retourne false
    if (!file_exists($filepath)) {
        return false;
    }

    // On récupère le contenu du fichier
    $jsonData = file_get_contents($filepath);

    // On retourne les données désérialisées
    return json_decode($jsonData, true);
}

/**
 * Ecrit des données dans un fichier au format JSON
 * @param string $filepath - Le chemin vers le fichier qu'on souhaite lire
 * @param $data - Les données qu'on souhaite enregistrer dans le fichier JSON
 * @return void
 */
function saveJSON(string $filepath, $data)
{
    // On sérialise les données en JSON
    $jsonData = json_encode($data);

    // On écrit le JSON dans le fichier
    file_put_contents($filepath, $jsonData);
}
