--------------------------------------------
 Factorisation des classes de modèles
--------------------------------------------
Est-ce que des choses se répètent dans toutes les méthodes des classes de modèles ? 
Est-ce que des choses se répètent dans toutes les classes de modèles ? 
On va essayer de profiter de la puissance de la POO (propriétés, constructeur, héritage, etc) pour optimiser notre code ! 

On se concentre pour aujourd'hui sur : 
* page d'accueil
* page Article
On aura donc besoin des classes de modèles : 
- ArticleModel (avec les méthodes getAllArticles() et getOneArticle())
- CommentModel (avec les méthodes insertComment() et getCommentsByArticleId())


Je vous laisserai travailler vendredi à distance sur la mise à jour des autre pages du blog : 
- connexion
- création de compte
- dashboard admin
- ajout d'article
- modification d'article
- suppression d'article 

On crée la classe AbstractModel pour isoler et mutualiser l'objet Database dans une propriété $db dont vont hériter les classes de modèles : ArticleModel, CommentModel, etc
<?php 

abstract class AbstractModel {

    protected Database $db;

    public function __construct()
    {
        $this->db = new Database();
    }
}
On ajoute le extends AbstractModel dans les classes de modèle, par exmeple : 
class ArticleModel extends AbstractModel {
Et on peut faire maintenant appel directement à cette propriété $db sans qu'elle soit déclarée dans toutes les classes de modèles mais uniquement dans le classe AbstractModel. 
    function getAllArticles(): array
    {
        $sql = 'SELECT *
                FROM article AS A
                ORDER BY A.createdAt DESC';

        return $this->db->getAllResults($sql);
    }
 
Attention : pour l'instant on est obligé d'inclure tous nos fichiers de classes, par exemple dans le home.php : 
// Inclusion des dépendances
include '../app/config.php';
include '../src/Core/Database.php';
include '../src/Core/AbstractModel.php';
include '../src/Model/ArticleModel.php';
include '../lib/functions.php';
Idem dans tous les fichiers de contôleurs...

Dans la classe Database, idem, on va isoler l'objet PDO qu'on crée à chaque requête dans une propriété $pdo : 
<?php 

class Database {

    private PDO $pdo;

    public function __construct()
    {
        $this->pdo = $this->getPDOConnection();
    }
Et du coup dans la méthode  executeQuery() on fait directement appel à cette propriété $pdo : 
    public function executeQuery(string $sql, array $values = []): PDOStatement
    {
        // Préparation 
        $pdoStatement = $this->pdo->prepare($sql);


Pour tester le nombre d'instances de PDO créées sur chaque page, on ajoute une propriété statique (dont la valeur sera la même pour tous les objets de la classe) à la classe Database : 
<?php 

class Database {

    private PDO $pdo;

    static private int $countPDO = 0;

    public function __construct()
    {
        $this->pdo = $this->getPDOConnection();
        self::$countPDO++;
    }

    static public function getCountPDO()
    {
        return self::$countPDO;
    } 
Puis à la fin des contrôleurs on teste le nombre d'instances de PDO créées en appelant la méthode statique getCountPDO() de la classe Database, par exemple sur le home.php : 
// Test du nombre d'instances de PDO
var_dump(Database::getCountPDO());

// Affichage : inclusion du template
$template = 'home';
include '../templates/base.phtml';
Rappel : tout ce qui est statique se rapporte à la classe elle-même. On y fait appel avec l'opérateur : :
Classe::CONSTANTE
Classe::$propstatic
Classe::methodStatique()
Sans surprise, sur la page d'accueil on a une seule instance ( on appelle qu'une seule méthode, d'une seule classe de modèle)
Image
Mais sur la page Article, on fait appel à 2 classes de modèles, on appelle 2 méthodes et du coup on a 2 instances de PDO :
Image
On va voir quelle solution on choisit pour éviter de créer plusieurs fois la connexion à la base de données...


Pour éviter de créer plusieurs instances de PDO (inutile) on passe la propriété $pdo de la classe Database en static. Elle aura donc la même valeur pour tous les objets de la classe Database. On l'initilaise à null et dans le constructeur, si la propriété statique $pdo est null, alors on l'initialise, et seulement dans ce cas-là. 
class Database {

    static private ?PDO $pdo = null;

    public function __construct()
    {
        if (self::$pdo == null) {
            self::$pdo = $this->getPDOConnection();
        }
    }
Remarque : on utilise une propriété statique $pdo dans un contexte "non statique" dans le constructeur. C'est possible !
Remarque : il existe un autre mot-clé qui peut remplacer self, c'est static. Il y a une subtile différence que je vous laisse le soin d'aller voir si ça vous intéresse.
Ce qu'on vient de faire est une adaptation du pattern singleton.

ATTENTION : créez un fichier .gitignore à la racine du blog (fichiers et dossiers ignorés par git) et mettez dedans : 
vendor
Le dossier vendor ne doit pas être commité sur votre dépôt github !
Je vous ai ajouté sur classroom un document qui résumé composer et l'installation de la librairie var-dumper.
Blog POO > Composer (initiation)
La librairie symfony/var-dumper vous donne accès à 2 fonctions : dump() et dd()
dump() est l'équivalent du var_dump(), dd() arrête l'exécution du script après le dump() : dd = dump and die
