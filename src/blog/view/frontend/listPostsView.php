<?php $title = 'Mon blog'; ?>

<?php ob_start(); ?>

<h1>Mon super blog !</h1>

<p>Derniers billets du blog :</p>


<?php

foreach ($posts as $post)
{
?>
    <div class="news">

        <h3>

            <?= htmlspecialchars($post->title()) ?>

            <em>le <?= $post->dateCreation() ?></em>

        </h3>
        
        <p>
            <?= nl2br(htmlspecialchars($post->content())) ?>

            <br />

            <em><a href="index.php?action=post&amp;id=<?= $post->id() ?>">Commentaires</a></em>

        </p>

    </div>
    
<?php
}
?>
<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>