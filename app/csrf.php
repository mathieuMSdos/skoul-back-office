<?php

// fichier contenant la liste des routes avec vérification d'attaques CSRF

// Ajout check token anti-CSRF en POST
$csrfTokenToCheckInPost = [
    'teacher-add-post',
    'teacher-update-post',
    'student-add-post',
    'student-update-post',
    'appuser-signin-post'
];
// Ajout check token anti-CSRF en GET
$csrfTokenToCheckInGet = [
    'teacher-delete',
    'student-delete'
];
