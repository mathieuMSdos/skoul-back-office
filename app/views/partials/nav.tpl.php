    <nav class="navbar sticky-top navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="<?= $router->generate('main-home') ?>">Skoule</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav mr-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('main-home') ?>">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('teacher-list') ?>">Profs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('student-list') ?>">Etudiants</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('appuser-list') ?>">Utilisateurs</a>
                    </li>
                    <?php if (!empty($_SESSION['connectedUserId'])) : ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= $router->generate('appuser-logout') ?>">Se déconnecter</a>
                    </li>
                    <?php endif ?>
                </ul>
            </div>
        </div>
    </nav>