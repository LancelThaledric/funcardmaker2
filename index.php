<?php require_once('include/functions.php'); ?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SMF Funcard Maker 2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="design/style.css" media="all">
    <script src="js/lib/jquery-1.12.2.min.js"></script>
    <script src="js/lib/jquery.imgareaselect-0.9.10/scripts/jquery.imgareaselect.pack.js"></script>
    <script src="js/main.php"></script>
    <link rel="stylesheet" href="design/font/font-awesome-4.5.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="js/lib/jquery.imgareaselect-0.9.10/css/imgareaselect-default.css" />
</head>
<body class="index">
	<?php include 'template/header.php'; ?>
    <div class="fcm-wrapper">
	    <?php include 'template/menu.php'; ?>
        <?php include 'template/generator.php'; ?>
    </div>
    
</body>
</html>