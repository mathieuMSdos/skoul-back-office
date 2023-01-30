        <a href="<?= $router->generate('student-list') ?>" class="btn btn-success float-right">Retour</a>
        <h2><?php if (!empty($studentId)) : ?>Mettre à jour<?php else : ?>Ajouter<?php endif ?> un étudiant</h2>
        
        <form action="" method="POST" class="mt-5">
            <?php
            // Pour afficher les messages d'erreurs
            include __DIR__ . '/../partials/errors.tpl.php';
            ?>
            <input type="hidden" name="token" value="<?= $token ?>">
            <div class="form-group">
                <label for="firstname">Prénom</label>
                <input type="text" class="form-control" name="firstname" id="firstname" placeholder="" value="<?= $student->getFirstname() ?>">
            </div>
            <div class="form-group">
                <label for="lastname">Nom</label>
                <input type="text" class="form-control" name="lastname" id="lastname" placeholder="" value="<?= $student->getLastname() ?>">
            </div>
            <div class="form-group">
                <label for="teacher">Prof</label>
                <select name="teacher" id="teacher" class="form-control">
                    <option value="0">-</option>
                    <?php foreach ($teachersList as $currentTeacher) : ?>
                    <option value="<?= $currentTeacher->getId() ?>"<?php if ($currentTeacher->getId() == $student->getTeacherId()) : ?> selected<?php endif ?>><?= $currentTeacher->getFirstname() ?> <?= $currentTeacher->getLastname() ?> - <?= $currentTeacher->getJob() ?></option>
                    <?php endforeach ?>
                </select>
            </div>
            <div class="form-group">
                <label for="status">Statut</label>
                <select name="status" id="status" class="form-control">
                    <option value="0">-</option>
                    <option value="1"<?php if ($student->getStatus() == 1) : ?> selected<?php endif ?>>actif</option>
                    <option value="2"<?php if ($student->getStatus() == 2) : ?> selected<?php endif ?>>désactivé</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary btn-block mt-5">Valider</button>
        </form>