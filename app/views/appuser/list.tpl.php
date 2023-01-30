        <a href="<?= $router->generate('appuser-add') ?>" class="btn btn-success float-right">Ajouter</a>

        <h2>Liste des Utilisateurs</h2>
        <?php
        // Pour afficher les messages d'erreurs
        include __DIR__ . '/../partials/errors.tpl.php';
        ?>
        <table class="table table-hover mt-4">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Email</th>
                    <th scope="col">Nom</th>
                    <th scope="col">Role</th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($appusersList as $currentTeacher) : ?>
                <tr>
                    <th scope="row"><?= $currentTeacher->getId() ?></th>
                    <td><?= $currentTeacher->getEmail() ?></td>
                    <td><?= $currentTeacher->getName() ?></td>
                    <td><?= $currentTeacher->getRole() ?></td>
                    <td class="text-right">
                        <a href="<?= $router->generate('appuser-update', ['id' => $currentTeacher->getId()]) ?>" class="btn btn-sm btn-warning">
                            <i class="fa fa-pencil-square-o" aria-hidden="true"></i>
                        </a>
                        <div class="btn-group">
                            <button type="button" class="btn btn-sm btn-danger dropdown-toggle"
                                data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-trash-o" aria-hidden="true"></i>
                            </button>
                            <div class="dropdown-menu">
                                <a class="dropdown-item" href="<?= $router->generate('appuser-delete', ['id' => $currentTeacher->getId()]) ?>?token=<?= $token ?>">Oui, je veux supprimer</a>
                                <a class="dropdown-item" href="#" data-toggle="dropdown">Oups !</a>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach ?>
            </tbody>
        </table>