        <?php if (!empty($errorList)) : ?>
        <div class="alert alert-danger">
            <?php foreach ($errorList as $currentError) : ?>
            <div><?= $currentError ?></div>
            <?php endforeach ?>
        </div>
        <?php endif ?>