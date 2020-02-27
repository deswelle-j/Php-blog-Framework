<?php $title = 'Erreurs'; ?>

<?php ob_start(); ?>

<h1>Erreurs</h1>

<strong><?= $errorMessage ?></strong>

<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?> 
