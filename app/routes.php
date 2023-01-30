<?php

// On doit déclarer toutes les "routes" à AltoRouter, afin qu'il puisse nous donner LA "route" correspondante à l'URL courante
// On appelle cela "mapper" les routes
// 1. méthode HTTP : GET ou POST (pour résumer)
// 2. La route : la portion d'URL après le basePath
// 3. Target/Cible : informations contenant
//      - le nom de la méthode à utiliser pour répondre à cette route
//      - le nom du controller contenant la méthode
// 4. Le nom de la route : pour identifier la route, on va suivre une convention
//      - "NomDuController-NomDeLaMéthode"
//      - ainsi pour la route /, méthode "home" du MainController => "main-home"

// On crée une variable pour ne pas répéter le namespace de tous les Controllers
$controllersNamespace = '\App\Controllers\\';

$router->map(
    'GET',
    '/',
    [
        'method' => 'home',
        'controller' => $controllersNamespace . 'MainController'
    ],
    'main-home'
);

// ---- TEACHERS ----

$router->map(
    'GET',
    '/teachers',
    [
        'method' => 'list',
        'controller' => $controllersNamespace . 'TeacherController'
    ],
    'teacher-list'
);
$router->map(
    'GET',
    '/teachers/add',
    [
        'method' => 'add',
        'controller' => $controllersNamespace . 'TeacherController'
    ],
    'teacher-add'
);
$router->map(
    'POST',
    '/teachers/add',
    [
        'method' => 'addPost',
        'controller' => $controllersNamespace . 'TeacherController'
    ],
    'teacher-add-post'
);
$router->map(
    'GET',
    '/teachers/[i:id]/delete',
    [
        'method' => 'delete',
        'controller' => $controllersNamespace . 'TeacherController'
    ],
    'teacher-delete'
);
$router->map(
    'GET',
    '/teachers/[i:id]',
    [
        'method' => 'update',
        'controller' => $controllersNamespace . 'TeacherController'
    ],
    'teacher-update'
);
$router->map(
    'POST',
    '/teachers/[i:id]',
    [
        'method' => 'updatePost',
        'controller' => $controllersNamespace . 'TeacherController'
    ],
    'teacher-update-post'
);

// ---- STUDENTS ----

$router->map(
    'GET',
    '/students',
    [
        'method' => 'list',
        'controller' => $controllersNamespace . 'StudentController'
    ],
    'student-list'
);
$router->map(
    'GET',
    '/students/add',
    [
        'method' => 'add',
        'controller' => $controllersNamespace . 'StudentController'
    ],
    'student-add'
);
$router->map(
    'POST',
    '/students/add',
    [
        'method' => 'addPost',
        'controller' => $controllersNamespace . 'StudentController'
    ],
    'student-add-post'
);
$router->map(
    'GET',
    '/students/[i:id]/delete',
    [
        'method' => 'delete',
        'controller' => $controllersNamespace . 'StudentController'
    ],
    'student-delete'
);
$router->map(
    'GET',
    '/students/[i:id]',
    [
        'method' => 'update',
        'controller' => $controllersNamespace . 'StudentController'
    ],
    'student-update'
);
$router->map(
    'POST',
    '/students/[i:id]',
    [
        'method' => 'updatePost',
        'controller' => $controllersNamespace . 'StudentController'
    ],
    'student-update-post'
);

// ---- APPUSERS ----

$router->map(
    'GET',
    '/appusers',
    [
        'method' => 'list',
        'controller' => $controllersNamespace . 'AppUserController'
    ],
    'appuser-list'
);
$router->map(
    'GET',
    '/appusers/add',
    [
        'method' => 'add',
        'controller' => $controllersNamespace . 'AppUserController'
    ],
    'appuser-add'
);
$router->map(
    'POST',
    '/appusers/add',
    [
        'method' => 'addPost',
        'controller' => $controllersNamespace . 'AppUserController'
    ],
    'appuser-add-post'
);
$router->map(
    'GET',
    '/appusers/[i:id]/delete',
    [
        'method' => 'delete',
        'controller' => $controllersNamespace . 'AppUserController'
    ],
    'appuser-delete'
);
$router->map(
    'GET',
    '/appusers/[i:id]',
    [
        'method' => 'update',
        'controller' => $controllersNamespace . 'AppUserController'
    ],
    'appuser-update'
);
$router->map(
    'POST',
    '/appusers/[i:id]',
    [
        'method' => 'updatePost',
        'controller' => $controllersNamespace . 'AppUserController'
    ],
    'appuser-update-post'
);

// SIGNIN
$router->map(
    'GET',
    '/signin',
    [
        'method' => 'signin',
        'controller' => $controllersNamespace . 'AppUserController'
    ],
    'appuser-signin'
);
$router->map(
    'POST',
    '/signin',
    [
        'method' => 'signinPost',
        'controller' => $controllersNamespace . 'AppUserController'
    ],
    'appuser-signin-post'
);
$router->map(
    'GET',
    '/logout',
    [
        'method' => 'logout',
        'controller' => $controllersNamespace . 'AppUserController'
    ],
    'appuser-logout'
);
