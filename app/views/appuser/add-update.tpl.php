        <a href="<?= $router->generate('appuser-list') ?>" class="btn btn-success float-right">Retour</a>
        <h2><?php if (!empty($appuserId)) : ?>Mettre à jour<?php else : ?>Ajouter<?php endif ?> un utilisateur</h2>
        
        <form action="" method="POST" class="mt-5">
            <?php
            // Pour afficher les messages d'erreurs
            include __DIR__ . '/../partials/errors.tpl.php';
            ?>
            <input type="hidden" name="token" value="<?= $token ?>">
            <div class="form-group">
                <label for="email">Adresse email</label>
                <input type="email" class="form-control" name="email" id="email" placeholder="" value="<?= $appuser->getEmail() ?>">
            </div>
            <div class="form-group">
                <label for="name">Nom</label>
                <input type="text" class="form-control" name="name" id="name" placeholder="" value="<?= $appuser->getName() ?>">
            </div>
            <?php if (empty($appuserId)) : // Si ajout, pas modification ?>
            <div class="form-group">
                <label for="password">Mot de passe</label>
                <input type="password" class="form-control" name="password" id="password" placeholder="" value="">
            </div>
            <?php endif ?>
            <div class="form-group">
                <label for="role">Role</label>
                <select name="role" id="role" class="form-control">
                    <option value="user"<?php if ($appuser->getRole() == 'user') : ?> selected<?php endif ?>>Utilisateur</option>
                    <option value="admin"<?php if ($appuser->getRole() == 'admin') : ?> selected<?php endif ?>>Administrateur</option>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Statut</label>
                <select name="status" id="status" class="form-control">
                    <option value="0">-</option>
                    <option value="1"<?php if ($appuser->getStatus() == 1) : ?> selected<?php endif ?>>actif</option>
                    <option value="2"<?php if ($appuser->getStatus() == 2) : ?> selected<?php endif ?>>désactivé</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
        </form>