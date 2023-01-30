<?php

namespace App\Controllers;

use App\Models\AppUser;

class AppUserController extends CoreController
{

    /**
     * Méthode s'occupant de la page listant les users
     *
     * @return void
     */
    public function list()
    {
        // On commence par récupérer tous les Models AppUsers
        $items = AppUser::findAll();

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show(
            'appuser/list',
            [
                'appusersList' => $items
            ]
        );
    }

    /**
     * Méthode s'occupant de la page de connexion au BackOffice
     *
     * @return void
     */
    public function signin()
    {
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show(
            'appuser/signin',
            [
                // Pour ne pas afficher la navigation (car user pas connecté)
                'hideNav' => true,
                // On pré-remplit les input avec du vide
                'email' => '',
                'password' => '',
                // Et le token anti-CSRF
                'token' => $this->generateToken()
            ]
        );
    }

    /**
     * Méthode s'occupant des données envoyées en POST par le formulaire de connexion
     *
     * @return void
     */
    public function signinPost()
    {
        // On récupère les 2 données
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $password = filter_input(INPUT_POST, 'password');

        // On valide ces données
        $errorList = [];

        if (empty($email)) {
            $errorList[] = 'L\'adresse email saisie est vide ou incorrecte';
        }
        if (empty($password)) {
            $errorList[] = 'Le mot de passe saisi est vide';
        }
        if (strlen($password) < 6) {
            $errorList[] = 'Le mot de passe saisi doit faire au moins 6 caractères';
        }

        // S'il n'y a aucune erreur dans les données
        if (empty($errorList)) {
            // On récupère l'objet AppUser pour l'email fourni
            $appUser = AppUser::findByEmail($email);

            // Si on n'a pas trouvé de user pour cet email
            if (empty($appUser)) {
                // On ajoute un message d'erreur
                $errorList[] = 'Les identifiants ne sont pas reconnus';
            } else { // Sinon, on a bien trouvé un user
                // Alors on peut tester la correspondance du mot de passe avec le mot de passe hashé en DB
                // Si c'est le bon mot de passe
                if (password_verify($password, $appUser->getPassword())) {
                    // Si l'utilisateur est bien "actif"
                    if ($appUser->getStatus() == 1) {
                        // Alors on connecte l'utilisateur
                        $_SESSION['connectedUser'] = $appUser;
                        $_SESSION['connectedUserId'] = $appUser->getId();

                        // Puis on redirige vers l'accueil
                        $this->redirectToRoute('main-home');
                    } else { // sinon, l'utilisateur est désactivé
                        // On ajoute un message d'erreur
                        $errorList[] = 'Cet utilisateur n\'est pas autorisé à se connecter';
                    }
                } else {
                    // Alors on ajoute un message d'erreur
                    $errorList[] = 'Les identifiants ne sont pas reconnus';
                }
            }
        }

        // S'il y a des erreurs
        if (!empty($errorList)) {
            // On réaffiche le formulaire
            // En pré-remplissant les données
            $this->show(
                'appuser/signin',
                [
                    // Pour ne pas afficher la navigation (car user pas connecté)
                    'hideNav' => true,
                    // On pré-remplit les input avec les données BRUTES en POST
                    'email' => filter_input(INPUT_POST, 'email'),
                    'password' => filter_input(INPUT_POST, 'password'),
                    // On transmet aussi le tableau d'erreurs
                    'errorList' => $errorList,
                    // Et le token anti-CSRF
                    'token' => $this->generateToken()
                ]
            );
        }
    }

    /**
     * Méthode s'occupant de déconnecter l'utilisateur
     *
     * @return void
     */
    public function logout()
    {
        // On supprime les données en session
        unset($_SESSION['connectedUser']);
        unset($_SESSION['connectedUserId']);

        // On redirige vers la page de connexion
        $this->redirectToRoute('appuser-signin');
    }

    /**
     * Méthode s'occupant de la page d'ajout d'un user
     *
     * @return void
     */
    public function add()
    {
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show(
            'appuser/add-update',
            [
                // Pour pré-remplir, on doit fournir un Model appuser, avec des valeurs vides
                'appuser' => new AppUser(),
                // Et le token anti-CSRF
                'token' => $this->generateToken()
            ]
        );
    }

    /**
     * Méthode s'occupant des données envoyées en POST par le formulaire d'ajout
     *
     * @return void
     */
    public function addPost()
    {
        // On récupère les 2 données
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $name = filter_input(INPUT_POST, 'name');
        $password = filter_input(INPUT_POST, 'password');
        $role = filter_input(INPUT_POST, 'role');
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

        // On valide ces données
        $errorList = [];

        if (empty($email)) {
            $errorList[] = 'L\'adresse email est vide ou incorrecte';
        }
        if (empty($name)) {
            $errorList[] = 'Le nom saisi est vide';
        }
        if (empty($password)) {
            $errorList[] = 'Le mot de passe est vide';
        }
        if (strlen($password) < 6) {
            $errorList[] = 'Le mot de passe doit faire au moins 6 caractères';
        }
        if ($role != 'admin' && $role != 'user') {
            $errorList[] = 'Le role sélectionné est incorrect';
        }
        if ($status < 0 || $status > 2) {
            $errorList[] = 'Le statut sélectionné est incorrect';
        }

        // S'il n'y a aucune erreur dans les données
        if (empty($errorList)) {
            // On crée un nouveau Model
            $appuser = new AppUser();

            // On met à jour les propriétés
            $appuser->setEmail($email);
            $appuser->setName($name);
            $appuser->setNewPassword($password);
            $appuser->setRole($role);
            $appuser->setStatus($status);

            // On sauvergarde en DB
            if ($appuser->save()) {
                // Si la sauvegarde a fonctionné, on redirige
                $this->redirectToRoute('appuser-update', ['id' => $appuser->getId()]);
            } else {
                // On ajoute un message d'erreurs
                $errorList[] = 'La sauvegarde a échoué';
            }
        }

        // S'il y a des erreurs
        if (!empty($errorList)) {
            // On réaffiche le formulaire
            // On a besoin de renseigner un Model AppUser pour pré-remplir le formulaire
            $appuser = new AppUser();
            $appuser->setEmail($email);
            $appuser->setName($name);
            $appuser->setNewPassword($password);
            $appuser->setRole($role);
            $appuser->setStatus($status);

            // En pré-remplissant les données
            $this->show(
                'appuser/add-update',
                [
                    // On pré-remplit les input avec les données BRUTES en POST, stockées dans le Model
                    'appuser' => $appuser,
                    // On transmet aussi le tableau d'erreurs
                    'errorList' => $errorList,
                    // Et le token anti-CSRF
                    'token' => $this->generateToken()
                ]
            );
        }
    }

    /**
     * Méthode s'occupant de la page de mise à jour d'un user
     *
     * @param string $appuserId ID du appuser, fourni par AltoDispatcher
     * @return void
     */
    public function update($appuserId)
    {
        // On commence par récupérer le Model AppUser
        $item = AppUser::find($appuserId);

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show(
            'appuser/add-update',
            [
                // Le Model AppUser
                'appuser' => $item,
                // Et l'id du appuser
                'appuserId' => $appuserId,
                // Et le token anti-CSRF
                'token' => $this->generateToken()
            ]
        );
    }

    /**
     * Méthode s'occupant des données envoyées en POST par le formulaire de modification
     *
     * @param string $appuserId ID du appuser, fourni par AltoDispatcher
     * @return void
     */
    public function updatePost($appuserId)
    {
        // On récupère les 2 données
        $email = filter_input(INPUT_POST, 'email', FILTER_VALIDATE_EMAIL);
        $name = filter_input(INPUT_POST, 'name');
        // On ne permet pas la modification du mot de passe
        // $password = filter_input(INPUT_POST, 'password');
        $role = filter_input(INPUT_POST, 'role');
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

        // On valide ces données
        $errorList = [];

        if (empty($email)) {
            $errorList[] = 'L\'adresse email est vide ou incorrecte';
        }
        if (empty($name)) {
            $errorList[] = 'Le nom saisi est vide';
        }
        /*if (empty($password)) {
            $errorList[] = 'Le mot de passe est vide';
        }
        if (strlen($password) < 6) {
            $errorList[] = 'Le mot de passe doit faire au moins 6 caractères';
        }*/
        if ($role != 'admin' && $role != 'user') {
            $errorList[] = 'Le role sélectionné est incorrect';
        }
        if ($status < 0 || $status > 2) {
            $errorList[] = 'Le statut sélectionné est incorrect';
        }

        // S'il n'y a aucune erreur dans les données
        if (empty($errorList)) {
            // On récupère le Model AppUser pour le modifier
            $appuser = AppUser::find($appuserId);

            // Si le appuser ayant cet ID existe
            if (!empty($appuser)) {
                // Alors on peut modifier en DB
                // On met à jour les propriétés
                $appuser->setEmail($email);
                $appuser->setName($name);
                // $appuser->setNewPassword($password);
                $appuser->setRole($role);
                $appuser->setStatus($status);

                // On sauvergarde en DB
                if ($appuser->save()) {
                    // Si la sauvegarde a fonctionné, on redirige
                    $this->redirectToRoute('appuser-update', ['id' => $appuser->getId()]);
                } else {
                    // On ajoute un message d'erreurs
                    $errorList[] = 'La sauvegarde a échoué';
                }
            } else {
                // Alors on ajoute un message d'erreurs
                $errorList[] = 'L\'id fourni est erroné';
            }
        }

        // S'il y a des erreurs
        if (!empty($errorList)) {
            // On réaffiche le formulaire
            // On a besoin de renseigner un Model AppUser pour pré-remplir le formulaire
            $appuser = new AppUser();
            $appuser->setEmail($email);
            $appuser->setName($name);
            // On ne permet pas la modification du mot de passe
            // $appuser->setNewPassword($password);
            $appuser->setRole($role);
            $appuser->setStatus($status);

            // En pré-remplissant les données
            $this->show(
                'appuser/add-update',
                [
                    // On pré-remplit les input avec les données BRUTES en POST, stockées dans le Model
                    'appuser' => $appuser,
                    // On transmet aussi le tableau d'erreurs
                    'errorList' => $errorList,
                    // Et l'id du appuser
                    'appuserId' => $appuserId,
                    // Et le token anti-CSRF
                    'token' => $this->generateToken()
                ]
            );
        }
    }

    /**
     * Méthode s'occupant de la suppression d'un user
     *
     * @param string $appuserId ID du appuser, fourni par AltoDispatcher
     * @return void
     */
    public function delete($appuserId)
    {
        // On commence par récupérer le Model AppUser
        $appuser = AppUser::find($appuserId);

        // Si le appuser ayant cet ID existe
        if (!empty($appuser)) {
            // Alors on peut supprimer
            $appuser->delete();

            // Puis rediriger sur la page "liste"
            $this->redirectToRoute('appuser-list');
        } else { // Sinon
            // On récupère tous les Models AppUsers
            $items = AppUser::findAll();

            // On affiche la page liste
            $this->show(
                'appuser/list',
                [
                    'appusersList' => $items,
                    // Avec un message d'erreur
                    'errorList' => [
                        'Impossible de supprimer, id inexistant'
                    ],
                    // Et le token anti-CSRF
                    'token' => $this->generateToken()
                ]
            );
        }
    }
}
