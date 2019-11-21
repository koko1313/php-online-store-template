<?php session_start(); ob_start(); ?>

<!DOCTYPE html>
<html>
<head>
	<title><?php echo $title ?></title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- Ако сме сетнали $directory_in значи сме папка навътре и го слагаме, за да заредим стиловете правилно -->
	<link rel="stylesheet" href="<?php if(isset($directory_in)) echo $directory_in ?>css/bootstrap.min.css">
	<link rel="stylesheet" href="<?php if(isset($directory_in)) echo $directory_in ?>css/jquery-ui.min.css">
	<link rel="stylesheet" href="<?php if(isset($directory_in)) echo $directory_in ?>css/fontawesome-all.min.css">
	<link rel="stylesheet" href="<?php if(isset($directory_in)) echo $directory_in ?>css/style.css">
	
	<script src="<?php if(isset($directory_in)) echo $directory_in ?>js/jquery-3.3.1.min.js"></script>
	<script src="<?php if(isset($directory_in)) echo $directory_in ?>js/jquery-ui.min.js"></script>
	<script src="<?php if(isset($directory_in)) echo $directory_in ?>js/bootstrap.min.js"></script>
	
	<!-- froala editor
	<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/css/froala_editor.min.css' rel='stylesheet' type='text/css' />
	<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/css/froala_style.min.css' rel='stylesheet' type='text/css' />
	<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/css/plugins/code_view.min.css' rel='stylesheet' type='text/css' />
	<link href='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/css/plugins/table.min.css' rel='stylesheet' type='text/css' />
	
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/js/froala_editor.min.js'></script>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/js/plugins/align.min.js'></script>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/js/plugins/code_view.min.js'></script>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/js/plugins/link.min.js'></script>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/js/plugins/lists.min.js'></script>
	<script type='text/javascript' src='https://cdnjs.cloudflare.com/ajax/libs/froala-editor/2.7.6/js/plugins/table.min.js'></script>
	-->
</head>
<body>

<?php include "nav.php" ?>

<?php include "components/carousel/carousel.php" ?>

<!-- Main container div -->
<div class="container-fluid">

