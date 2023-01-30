<?php

namespace App\Controllers;

use App\Models\Teacher;

class TeacherController extends CoreController
{

    /**
     * Méthode s'occupant de la page listant les profs
     *
     * @return void
     */
    public function list()
    {
        // On commence par récupérer tous les Models Teachers
        $items = Teacher::findAll();

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show(
            'teacher/list',
            [
                'teachersList' => $items,
                // Le token anti-CSRF
                'token' => $this->generateToken()
            ]
        );
    }

    /**
     * Méthode s'occupant de la page d'ajout d'un prof
     *
     * @return void
     */
    public function add()
    {
        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show(
            'teacher/add-update',
            [
                // Pour pré-remplir, on doit fournir un Model teacher, avec des valeurs vides
                'teacher' => new Teacher(),
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
        // On récupère les données
        $firstname = filter_input(INPUT_POST, 'firstname');
        $lastname = filter_input(INPUT_POST, 'lastname');
        $job = filter_input(INPUT_POST, 'job');
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

        // On valide ces données
        $errorList = [];

        if (empty($firstname)) {
            $errorList[] = 'Le prénom saisi est vide';
        }
        if (empty($lastname)) {
            $errorList[] = 'Le nom saisi est vide';
        }
        if (empty($job)) {
            $errorList[] = 'Le titre saisi est vide';
        }
        if ($status < 0 || $status > 2) {
            $errorList[] = 'Le statut sélectionné est incorrect';
        }

        // S'il n'y a aucune erreur dans les données
        if (empty($errorList)) {
            // On crée un nouveau Model
            $teacher = new Teacher();

            // On met à jour les propriétés
            $teacher->setFirstname($firstname);
            $teacher->setLastname($lastname);
            $teacher->setJob($job);
            $teacher->setStatus($status);

            // On sauvergarde en DB
            if ($teacher->save()) {
                // Si la sauvegarde a fonctionné, on redirige
                $this->redirectToRoute('teacher-update', ['id' => $teacher->getId()]);
            } else {
                // On ajoute un message d'erreurs
                $errorList[] = 'La sauvegarde a échoué';
            }
        }

        // S'il y a des erreurs
        if (!empty($errorList)) {
            // On réaffiche le formulaire
            // On a besoin de renseigner un Model Teacher pour pré-remplir le formulaire
            $teacher = new Teacher();
            $teacher->setFirstname($firstname);
            $teacher->setLastname($lastname);
            $teacher->setJob($job);
            $teacher->setStatus($status);

            // En pré-remplissant les données
            $this->show(
                'teacher/add-update',
                [
                    // On pré-remplit les input avec les données BRUTES en POST, stockées dans le Model
                    'teacher' => $teacher,
                    // On transmet aussi le tableau d'erreurs
                    'errorList' => $errorList,
                    // Et le token anti-CSRF
                    'token' => $this->generateToken()
                ]
            );
        }
    }

    /**
     * Méthode s'occupant de la page de mise à jour d'un prof
     *
     * @param string $teacherId ID du teacher, fourni par AltoDispatcher
     * @return void
     */
    public function update($teacherId)
    {
        // On commence par récupérer le Model Teacher
        $item = Teacher::find($teacherId);

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show(
            'teacher/add-update',
            [
                // Le Model Teacher
                'teacher' => $item,
                // Et l'id du teacher
                'teacherId' => $teacherId,
                // Et le token anti-CSRF
                'token' => $this->generateToken()
            ]
        );
    }

    /**
     * Méthode s'occupant des données envoyées en POST par le formulaire de modification
     *
     * @param string $teacherId ID du teacher, fourni par AltoDispatcher
     * @return void
     */
    public function updatePost($teacherId)
    {
        // On récupère les 2 données
        $firstname = filter_input(INPUT_POST, 'firstname');
        $lastname = filter_input(INPUT_POST, 'lastname');
        $job = filter_input(INPUT_POST, 'job');
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);

        // On valide ces données
        $errorList = [];

        if (empty($firstname)) {
            $errorList[] = 'Le prénom saisi est vide';
        }
        if (empty($lastname)) {
            $errorList[] = 'Le nom saisi est vide';
        }
        if (empty($job)) {
            $errorList[] = 'Le titre saisi est vide';
        }
        if ($status < 0 || $status > 2) {
            $errorList[] = 'Le statut sélectionné est incorrect';
        }

        // S'il n'y a aucune erreur dans les données
        if (empty($errorList)) {
            // On récupère le Model Teacher pour le modifier
            $teacher = Teacher::find($teacherId);

            // Si le teacher ayant cet ID existe
            if (!empty($teacher)) {
                // Alors on peut modifier en DB
                // On met à jour les propriétés
                $teacher->setFirstname($firstname);
                $teacher->setLastname($lastname);
                $teacher->setJob($job);
                $teacher->setStatus($status);

                // On sauvergarde en DB
                if ($teacher->save()) {
                    // Si la sauvegarde a fonctionné, on redirige
                    $this->redirectToRoute('teacher-update', ['id' => $teacher->getId()]);
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
            // On a besoin de renseigner un Model Teacher pour pré-remplir le formulaire
            $teacher = new Teacher();
            $teacher->setFirstname($firstname);
            $teacher->setLastname($lastname);
            $teacher->setJob($job);
            $teacher->setStatus($status);

            // En pré-remplissant les données
            $this->show(
                'teacher/add-update',
                [
                    // On pré-remplit les input avec les données BRUTES en POST, stockées dans le Model
                    'teacher' => $teacher,
                    // On transmet aussi le tableau d'erreurs
                    'errorList' => $errorList,
                    // Et l'id du teacher
                    'teacherId' => $teacherId,
                    // Et le token anti-CSRF
                    'token' => $this->generateToken()
                ]
            );
        }
    }

    /**
     * Méthode s'occupant de la suppression d'un prof
     *
     * @param string $teacherId ID du teacher, fourni par AltoDispatcher
     * @return void
     */
    public function delete($teacherId)
    {
        // On commence par récupérer le Model Teacher
        $teacher = Teacher::find($teacherId);

        // Si le teacher ayant cet ID existe
        if (!empty($teacher)) {
            // Alors on peut supprimer
            $teacher->delete();

            // Puis rediriger sur la page "liste"
            $this->redirectToRoute('teacher-list');
        } else {
            // On récupère tous les Models Teachers
            $items = Teacher::findAll();

            // On affiche la page liste
            $this->show(
                'teacher/list',
                [
                    'teachersList' => $items,
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
