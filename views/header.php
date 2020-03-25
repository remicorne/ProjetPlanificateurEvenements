<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title><?=$title?></title>

    <script type="text/javascript" src="/assets/js/fonctions_communes.js"></script>
    <!-- les scripts jquery peuvent être ajoutées avant la balise </body> pour reduire le temps de chargement. -->
    <script type="text/javascript" src="/assets/js/jquery-1.12.4.js"></script>
    <script type="text/javascript" src="/assets/js/jquery-ui.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/js/bootstrap-multiselect.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js"></script>  
  
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-multiselect/0.9.13/css/bootstrap-multiselect.css" />

        <link rel="stylesheet" type="text/css" href="/assets/css/style.css"/>
        <link rel="stylesheet" type="text/css" href="/assets/css/sondages_new.css"/>
        <link rel="stylesheet" type="text/css" href="/assets/css/mon_compte.css"/>
        <link rel="stylesheet" type="text/css" href="/assets/css/creer_un_groupe.css"/>
        <link rel="stylesheet" type="text/css" href="/assets/css/voir_les_groupes.css"/>
        <link rel="stylesheet" type="text/css" href="/assets/css/ajouter_participants.css"/>
        <link rel="stylesheet" type="text/css" href="/assets/css/reunions_en_sondages.css"/>
     </head>

  <body onload="init()">
  <h1 id="titre"><?=$title?></h1>
  
  <?php if (isset($error)) { ?>
  <div class="alert alert-warning" role="alert"><?= $error ?></div>
<?php } ?>