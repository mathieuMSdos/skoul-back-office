<?php

// fichier contenant la liste des routes avec accès restreints

// On définit la liste des permissions pour les routes nécessitant une connexion utilisateur
$acl = [
    'main-home' => ['admin', 'user'],

    // TEACHERS
    'teacher-list' => ['admin', 'user'],
    'teacher-add' => ['admin'],
    'teacher-add-post' => ['admin'],
    'teacher-update' => ['admin'],
    'teacher-update-post' => ['admin'],
    'teacher-delete' => ['admin'],

    // STUDENTS
    'student-list' => ['admin', 'user'],
    'student-add' => ['admin', 'user'],
    'student-add-post' => ['admin', 'user'],
    'student-update' => ['admin', 'user'],
    'student-update-post' => ['admin', 'user'],
    'student-delete' => ['admin', 'user'],
    // APP USERS
    'appuser-list' => ['admin'],
    'appuser-add' => ['admin'],
    'appuser-add-post' => ['admin'],
    'appuser-update' => ['admin'],
    'appuser-update-post' => ['admin'],
    'appuser-delete' => ['admin'],
];
