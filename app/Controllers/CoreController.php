<?php

namespace App\Controllers;

abstract class CoreController
{

    /**
     * Contructeur de la classe CoreController
     * Comme toutes les méthodes du CoreController, cette méthode est héritée par les enfants
     */
    public function __construct()
    {
        // La variable $match contient les infos sur la route courante
        global $match;
        // dump($match);exit;

        // ------------- ACL -------------

        // On récupère le nom de la route courante
        $routeName = $match['name'];

        // On définit la liste des permissions pour les routes nécessitant une connexion utilisateur
        // Mais ici on applique une bonne pratique de séparation de concepts :
        //   - ce CoreController fait partie du code "central", donc de notre framework
        //   - on ne veut pas le modifier pour chaque projet
        //   - donc les parties "à modifier" car spécifiques à chaque projet, on les place dans un fichier à part
        //   - pour les ACL, ce sera app/acl.php
        require __DIR__ . '/../acl.php';

        // Si la route actuelle est dans la liste des ACL
        if (!empty($acl) && array_key_exists($routeName, $acl)) {
            // Alors on récupère le tableau des roles autorisés
            $authorizedRoles = $acl[$routeName];

            // Puis on utilie la méthode checkAuthorization($roles) pour vérifier les permissions
            $this->checkAuthorization($authorizedRoles);
        }
        // Sinon, on ne fait rien, on laisse la suite du script se faire (afficher)

        // ------------- CSRF -------------

        // On définit la liste des routes nécessitant une vérification d'attaques CSRF
        // Même bonne pratique que pour les ACL
        // le code spécifique à chaque projet/site est placer dans un fichier à part : app/csrf.php
        require __DIR__ . '/../csrf.php';

        // Si la route actuelle nécessite la vérification d'un token anti-CSRF en POST
        if (!empty($csrfTokenToCheckInPost) && in_array($routeName, $csrfTokenToCheckInPost)) {
            // On récupère le token en POST
            $token = isset($_POST['token']) ? $_POST['token'] : '';
            // $token = filter_input(INPUT_POST, 'token');
            // $token = $_POST['token'] ?? '';

            $this->getCsrfToken($token);
        }
        // Sinon, on ne fait rien, il n'y a rien à vérifier

        // Si la route actuelle nécessite la vérification d'un token anti-CSRF en GET
        if (!empty($csrfTokenToCheckInGet) && in_array($routeName, $csrfTokenToCheckInGet)) {
            // On récupère le token en GET
            $token = isset($_GET['token']) ? $_GET['token'] : '';
            // $token = filter_input(INPUT_GET, 'token');
            // $token = $_GET['token'] ?? '';

            $this->getCsrfToken($token);
        }
        // Sinon, on ne fait rien, il n'y a rien à vérifier
    }

    /**
     * Méthode permettant de vérifier si un token correspond au token en session, puis d'agir en conséquence
     * Méthode créée afin de ne pas répéter ce code de vérification en GET et en POST
     *
     * @param string $token
     * @return void
     */
    protected function getCsrfToken(string $token)
    {
        // On récupère le token en SESSION
        $sessionToken = isset($_SESSION['token']) ? $_SESSION['token'] : '';
        // $sessionToken = $_SESSION['token'] ?? '';

        // S'ils ne sont pas égaux ou vide
        if ($token !== $sessionToken || empty($token)) {
            // Alors on affiche une 403
            $this->err403();
            exit;
        } else { // Sinon, tout va bien
            // On supprime le token en session
            // Ainsi, on ne pourra pas soumettre plusieurs fois le même formulaire, ni réutiliser ce token
            unset($_SESSION['token']);
        }
    }

    /**
     * Méthode permettant de vérifier si l'utilisateur connecté a le bon role
     *
     * @param array $roles Le trableau des roles autorisés
     * @return bool
     */
    public function checkAuthorization($roles = [])
    {
        // Si le user est connecté
        if (!empty($_SESSION['connectedUser'])) {
            // Alors on récupère l'utilisateur connecté
            $connectedUser = $_SESSION['connectedUser'];

            // Puis on récupère son role
            $userRole = $connectedUser->getRole();

            // si le role fait partie des roles autorisées (fournis en paramètres)
            if (in_array($userRole, $roles)) {
                // Alors on retourne vrai
                return true;
            } else { // Sinon le user connecté n'a pas la permission d'accéder à la page
                // => on envoie le header "403 Forbidden"
                // Puis on affiche la page d'erreur 403
                $this->err403();
                // Enfin on arrête le script pour que la page demandée ne s'affiche pas
                exit;
            }
        } else { // Sinon, l'internaute n'est pas connecté à un compte
            // Alors on le redirige vers la page de connexion
            // Pour cela on a besoin du router
            global $router; // toujours moche, mais on fait avec :(
            header('Location: ' . $router->generate('appuser-signin'));
            exit;
        }
    }

    /**
     * Méthode permettant d'afficher du code HTML en se basant sur les views
     *
     * @param string $viewName Nom du fichier de vue
     * @param array $viewVars Tableau des données à transmettre aux vues
     * @return void
     */
    protected function show(string $viewName, $viewVars = [])
    {
        // On globalise $router car on ne sait pas faire mieux pour l'instant
        global $router;

        // Comme $viewVars est déclarée comme paramètre de la méthode show()
        // les vues y ont accès
        // ici une valeur dont on a besoin sur TOUTES les vues
        // donc on la définit dans show()
        $viewVars['currentPage'] = $viewName;

        // définir l'url absolue pour nos assets
        $viewVars['assetsBaseUri'] = $_SERVER['BASE_URI'] . 'assets/';
        // définir l'url absolue pour la racine du site
        // /!\ != racine projet, ici on parle du répertoire public/
        $viewVars['baseUri'] = $_SERVER['BASE_URI'];

        // On veut désormais accéder aux données de $viewVars, mais sans accéder au tableau
        // La fonction extract permet de créer une variable pour chaque élément du tableau passé en argument
        extract($viewVars);
        // => la variable $currentPage existe désormais, et sa valeur est $viewName
        // => la variable $assetsBaseUri existe désormais, et sa valeur est $_SERVER['BASE_URI'] . '/assets/'
        // => la variable $baseUri existe désormais, et sa valeur est $_SERVER['BASE_URI']
        // => il en va de même pour chaque élément du tableau

        // $viewVars est disponible dans chaque fichier de vue
        require_once __DIR__ . '/../views/layout/header.tpl.php';
        require_once __DIR__ . '/../views/' . $viewName . '.tpl.php';
        require_once __DIR__ . '/../views/layout/footer.tpl.php';
    }

    /**
     * Méthode permettant de rediriger l'internaute vers la page d'une route fournie
     *
     * @param string $routeName Nom de la route
     * @param array $routeParams Paramètre pour générer cette route
     * @return void
     */
    protected function redirectToRoute(string $routeName, $routeParams = [])
    {
        // global, toujours moche, mais pour l'instant, on sait pas faire autrement
        global $router;

        // On génère l'URL
        $url = $router->generate($routeName, $routeParams);

        // On redirige vers cette URL
        header('Location: ' . $url);
        exit;
    }

    /**
     * Méthode gérant l'affichage de la page 403
     *
     * @return void
     */
    protected function err403()
    {
        // On envoie le header 404
        header('HTTP/1.0 403 Forbidden');

        // Puis on gère l'affichage
        $this->show(
            'error/err403',
            [
                // Pour ne pas afficher la navigation (car user pas connecté)
                'hideNav' => true
            ]
        );
    }

    /**
     * Méthode permettant de générer un token aléatoire
     *
     * @return string
     */
    protected function generateToken()
    {
        // génération d'un token aléatoire
        $_SESSION['token'] = md5(getmypid() . '-skouleCSRF*' . time() . 'toto' . mt_rand(1000, 10000000));

        return $_SESSION['token'];
    }
}
