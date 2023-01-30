<div class="card card--signin">
  <div class="card-header">
    Connexion
  </div>
  <div class="card-body">
    <form action="" method="post">
        <?php
        // Pour afficher les messages d'erreurs
        include __DIR__ . '/../partials/errors.tpl.php';
        ?>
        <input type="hidden" name="token" value="<?= $token ?>">
        <div class="form-group">
            <label for="email">Adresse email</label>
            <input type="email" class="form-control" name="email" id="email" placeholder="Saisissez votre adresse email" value="<?= $email ?>">
        </div>
        <div class="form-group">
            <label for="password">Mot de passe</label>
            <input type="password" class="form-control" name="password" id="password" placeholder="Saisissez votre mot de passe" value="<?= $password ?>">
        </div>
        <button type="submit" class="btn btn-primary btn-block mt-4">se connecter</button>
    </form>
  </div>
</div>