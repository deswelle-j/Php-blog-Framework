
<?php $title = 'Mon blog'; ?>

<?php ob_start(); ?>

<h1>Mon super blog</h1>

	<p><a href="index.php">Retour à la liste des billets</a></p>

	<div class="news">

		<h3>
			
			<?= htmlspecialchars($post->title()) ?>

			<em>le <?= $post->dateCreation() ?></em>

		</h3>

		<p>
			
			<?= nl2br(htmlspecialchars($post->content() )) ?>

		</p>
		

	</div>

	<h2>Commentaires</h2>

	<?php

		foreach( $comments as $comment)
		{
	?>

			<p><strong><?= htmlspecialchars($comment->author()) ?></strong> le <?= $comment->commentDate() ?> (<a href="index.php?action=edit&amp;id=<?= $comment->id() ?>&amp;post_id=<?= $comment->postId() ?>">modifier</a>)</p>

			<p><?= $comment->comment() ?></p>

	<?php
		}

	?>


	<form action="index.php?action=addComment&amp;id=<?= $post->id() ?>" method="post">

    <div>

        <label for="author">Auteur</label><br />

        <input type="text" id="author" name="author" required />

    </div>

    <div>

        <label for="comment">Commentaire</label><br />

        <textarea id="comment" name="comment" cols="100" rows="15" required></textarea>

    </div>

    <div>

        <input type="submit" />

    </div>
</form>


<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>








