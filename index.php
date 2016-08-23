<?php require_once('include/functions.php'); ?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>SMF Funcard Maker 2</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Créez en toute simplicité des funcards HD pour le jeu Magic: The Gathering.">
    <link rel="stylesheet" href="design/style.css" media="all">
    <script src="js/lib/jquery-1.12.2.min.js"></script>
    <script src="js/lib/jquery.imgareaselect-0.9.10/scripts/jquery.imgareaselect.pack.js"></script>
    <script src="js/main.php"></script>
    <link rel="stylesheet" href="design/font/font-awesome-4.5.0/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="js/lib/jquery.imgareaselect-0.9.10/css/imgareaselect-default.css" />

    <!-- Merci Real Favicon Generator ! http://realfavicongenerator.net/ -->
    <link rel="apple-touch-icon" sizes="180x180" href="/apple-touch-icon.png">
    <link rel="icon" type="image/png" href="/favicon-32x32.png" sizes="32x32">
    <link rel="icon" type="image/png" href="/favicon-194x194.png" sizes="194x194">
    <link rel="icon" type="image/png" href="/android-chrome-192x192.png" sizes="192x192">
    <link rel="icon" type="image/png" href="/favicon-16x16.png" sizes="16x16">
    <link rel="manifest" href="/manifest.json">
    <link rel="mask-icon" href="/safari-pinned-tab.svg" color="#c74141">
    <meta name="msapplication-TileColor" content="#c74141">
    <meta name="msapplication-TileImage" content="/mstile-144x144.png">
    <meta name="theme-color" content="#c74141">
</head>
<body class="index">
    
    <?php
    if(MAINTENANCE_MODE):
        require('template/maintenance.php');
    else:
        
        include 'template/header.php'; ?>
        <div class="fcm-wrapper">
            <?php include 'template/menu.php'; ?>
            <?php include 'template/generator.php'; ?>
        </div>
    
    <?php endif; ?>
    
</body>
</html>