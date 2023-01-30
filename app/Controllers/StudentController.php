<?php

namespace App\Controllers;

use App\Models\Student;
use App\Models\Teacher;

class StudentController extends CoreController
{

    /**
     * Méthode s'occupant de la page listant les étudiants
     *
     * @return void
     */
    public function list()
    {
        // On commence par récupérer tous les Models Student
        $items = Student::findAll();

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show(
            'student/list',
            [
                'studentsList' => $items,
                // Et le token anti-CSRF
                'token' => $this->generateToken()
            ]
        );
    }

    /**
     * Méthode s'occupant de la page d'ajout d'un étudiant
     *
     * @return void
     */
    public function add()
    {
        // On récupère tous les Teacher (pour le menu déroulant)
        $teachers = Teacher::findAll();

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show(
            'student/add-update',
            [
                // Les Models Teacher
                'teachersList' => $teachers,
                // Pour pré-remplir, on doit fournir un Model student, avec des valeurs vides
                'student' => new Student(),
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
        $firstname = filter_input(INPUT_POST, 'firstname');
        $lastname = filter_input(INPUT_POST, 'lastname');
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
        $teacherId = filter_input(INPUT_POST, 'teacher', FILTER_VALIDATE_INT);

        // On valide ces données
        $errorList = [];

        if (empty($firstname)) {
            $errorList[] = 'Le prénom saisi est vide';
        }
        if (empty($lastname)) {
            $errorList[] = 'Le mot de passe saisi est vide';
        }
        if (empty($teacherId)) {
            $errorList[] = 'Veuillez sélectionner un prof';
        }
        if ($status < 0 || $status > 2) {
            $errorList[] = 'Le statut sélectionné est incorrect';
        }

        // S'il n'y a aucune erreur dans les données
        if (empty($errorList)) {
            // On crée un nouveau Model
            $student = new Student();

            // On renseigne les propriétés
            $student->setFirstname($firstname);
            $student->setLastname($lastname);
            $student->setTeacherId($teacherId);
            $student->setStatus($status);

            // On sauvergarde en DB
            if ($student->save()) {
                // Si la sauvegarde a fonctionné, on redirige
                $this->redirectToRoute('student-update', ['id' => $student->getId()]);
            } else {
                // On ajoute un message d'erreurs
                $errorList[] = 'La sauvegarde a échoué';
            }
        }

        // S'il y a des erreurs
        if (!empty($errorList)) {
            // On récupère tous les Teacher (pour le menu déroulant)
            $teachers = Teacher::findAll();

            // On réaffiche le formulaire
            // On a besoin de renseigner un Model Student pour pré-remplir le formulaire
            $student = new Student();
            $student->setFirstname($firstname);
            $student->setLastname($lastname);
            $student->setTeacherId($teacherId);
            $student->setStatus($status);

            // En pré-remplissant les données
            $this->show(
                'student/add-update',
                [
                    // On pré-remplit les input avec les données BRUTES en POST, stockées dans le Model
                    'student' => $student,
                    // Les Models Teacher
                    'teachersList' => $teachers,
                    // On transmet aussi le tableau d'erreurs
                    'errorList' => $errorList,
                    // Et l'id du student vide
                    'studentId' => null,
                    // Et le token anti-CSRF
                    'token' => $this->generateToken()
                ]
            );
        }
    }

    /**
     * Méthode s'occupant de la page de mise à jour d'un étudiant
     *
     * @param string $studentId ID du student, fourni par AltoDispatcher
     * @return void
     */
    public function update($studentId)
    {
        // On commence par récupérer le Model Student
        $item = Student::find($studentId);

        // On récupère tous les Teacher (pour le menu déroulant)
        $teachers = Teacher::findAll();

        // On appelle la méthode show() de l'objet courant
        // En argument, on fournit le fichier de Vue
        // Par convention, chaque fichier de vue sera dans un sous-dossier du nom du Controller
        $this->show(
            'student/add-update',
            [
                // Le Model Student
                'student' => $item,
                // Les Models Teacher
                'teachersList' => $teachers,
                // Et l'id du student
                'studentId' => $studentId,
                // Et le token anti-CSRF
                'token' => $this->generateToken()
            ]
        );
    }

    /**
     * Méthode s'occupant des données envoyées en POST par le formulaire de modification
     *
     * @param string $studentId ID du student, fourni par AltoDispatcher
     * @return void
     */
    public function updatePost($studentId)
    {
        // On récupère les 2 données
        $firstname = filter_input(INPUT_POST, 'firstname');
        $lastname = filter_input(INPUT_POST, 'lastname');
        $status = filter_input(INPUT_POST, 'status', FILTER_VALIDATE_INT);
        $teacherId = filter_input(INPUT_POST, 'teacher', FILTER_VALIDATE_INT);

        // On valide ces données
        $errorList = [];

        if (empty($firstname)) {
            $errorList[] = 'Le prénom saisi est vide';
        }
        if (empty($lastname)) {
            $errorList[] = 'Le mot de passe saisi est vide';
        }
        if (empty($teacherId)) {
            $errorList[] = 'Veuillez sélectionner un prof';
        }
        if ($status < 0 || $status > 2) {
            $errorList[] = 'Le statut sélectionné est incorrect';
        }

        // S'il n'y a aucune erreur dans les données
        if (empty($errorList)) {
            // On récupère le Model Student pour le modifier
            $student = Student::find($studentId);

            // Si le student ayant cet ID existe
            if (!empty($student)) {
                // Alors on peut modifier en DB
                // On met à jour les propriétés
                $student->setFirstname($firstname);
                $student->setLastname($lastname);
                $student->setTeacherId($teacherId);
                $student->setStatus($status);

                // On sauvergarde en DB
                if ($student->save()) {
                    // Si la sauvegarde a fonctionné, on redirige
                    $this->redirectToRoute('student-update', ['id' => $student->getId()]);
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
            // On récupère tous les Teacher (pour le menu déroulant)
            $teachers = Teacher::findAll();

            // On réaffiche le formulaire
            // On a besoin de renseigner un Model Student pour pré-remplir le formulaire
            $student = new Student();
            $student->setFirstname($firstname);
            $student->setLastname($lastname);
            $student->setTeacherId($teacherId);
            $student->setStatus($status);

            // En pré-remplissant les données
            $this->show(
                'student/add-update',
                [
                    // On pré-remplit les input avec les données BRUTES en POST, stockées dans le Model
                    'student' => $student,
                    // Les Models Teacher
                    'teachersList' => $teachers,
                    // On transmet aussi le tableau d'erreurs
                    'errorList' => $errorList,
                    // Et l'id du student
                    'studentId' => $studentId,
                    // Et le token anti-CSRF
                    'token' => $this->generateToken()
                ]
            );
        }
    }

    /**
     * Méthode s'occupant de la suppression d'un étudiant
     *
     * @param string $studentId ID du student, fourni par AltoDispatcher
     * @return void
     */
    public function delete($studentId)
    {
        // On commence par récupérer le Model Student
        $student = Student::find($studentId);

        // Si le student ayant cet ID existe
        if (!empty($student)) {
            // Alors on peut supprimer
            $student->delete();

            // Puis rediriger sur la page "liste"
            $this->redirectToRoute('student-list');
        } else {
            // On récupère tous les Models Students
            $items = Student::findAll();

            // On affiche la page liste
            $this->show(
                'student/list',
                [
                    'studentsList' => $items,
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
