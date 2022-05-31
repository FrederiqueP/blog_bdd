Une fois mis en place votre tableau de routes et le routing dnas l'index.php, vous pouvez réfléchir à la fonction buildUrl() qui permettra de construire les URLs des pages du site. Tous les liens devront ensuite être modifiés.
function buildUrl(string $page, array $params = [])
{

}
Remarque : n'oubliez pas de prendre en compte le cas où le paramètre page n'existe pas dans les routes définies...

Comme d'habitude on peut faire les choses à la main par nous-même, avec une boucle foreach() par exemple, mais PHP a prévu des fonctions pour nous aider, par exemple la fonction http_build_query() permet de construire une chaîne de requête !
https://www.php.net/manual/fr/function.http-build-query

- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -
Pour la suite, il nous restera à voir les namespaces et à les intégrer à notre code.
On se servira enfin de l'autoloader de composer pour nos propres classes en respectant la norme PSR-4 !
- - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - - -

Une fois la fonction buildQuery() construite on va l'utiliser partout où on construit des liens vers d'autres pages : dans les liens des templates phtml, dans les redirections PHP, etc
Par exemple sur la page d'accueil pour les liens "Lire la suite" on aura dans le fichier de template home.phtml : 

<a href="<?=buildUrl('article', ['id' => $article['idArticle']]);?>">Lire la suite</a>

Ou bien pour la redirection vers le dashboard admin après la création d'un nouvel article : 
header('Location: ' . buildUrl('admin'));
exit;


Pensez à modifier : 
- les liens href=""
- les redirections header()
- les actions des formulaires