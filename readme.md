MVC

Là-dessus vous pouvez faire le cours de Mathieu Nebra qui s'est beaucoup inspiré de ce que je propose je trouve... en moins bien of course. 
https://openclassrooms.com/fr/courses/4670706-adoptez-une-architecture-mvc-en-php


---------------------------
    Classes de modèles 
---------------------------
On va laisser la classe Database dans sa version actuelle (1.0) pour le moment.
On va créer maintenant des classes de modèles : on va regrouper ensemble toutes les fonctions qui concernent une table de la base de données. 
Par exemple on va créer dans un dossier src/Model une classe ArticleModel dans laquelle on va regrouper toutes les fonctions qui concernent les articles.
Idem pour les utilisateurs, pour les commentaires, etc

La classe ArticleModel peut être abordée dans un premier temps comme un regroupement de fonctions : 
- getAllArticles()
- getOneArticle()
- addArticle()
- editArticle()
- deleteArticle()
Les 4 dernières sont à mettre à jour avec l'utilisation de la classe Database, comme dans getAllArticles()
On aura au final par exemple dans le fichier home.php : 
$articleModel = new ArticleModel();
$articles = $articleModel->getAllArticles();
On se demandera ensuite comment factoriser ce qui se répète dans toutes les méthodes de la classe ArticleModel. 
On appliquera ça à la page d'accueil puis à la page article.
Pour les commentaires on pourra créer une classe CommentModel de la même manière...
Remarque : parfois on appelle les modèles des "manager"
La convention est donc : 1 table de la BDD <=> 1 classe de modèle

Remarque : on peut en profiter pour ajouter l'utilisateur connecté lors de la création de l'article, exactement comme on a fait pour les commentaires

Lorsque les articles seront associés à un utilisateur, on pourra faire une jointure pour afficher l'auteur des articles !

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