<?php if ($user_is_logged){ ?>
	<div id="menu">
		<div id="photo_profil">
			<img src="" alt="photo de profil">
		</div>
		<div class="info_compte">
			<p>prenom : <?= $logged_user->prenom ?>  <p>
			<p>nom : <?= $logged_user->nom ?>  <p>
			<ul class="info_compte">
				<li><a href="/index.php/evenements/monCompte">mon compte</a></li>
				<li><a href="/index.php/sessions/sessions_destroy">se déconnecter</a></li>
			</ul>
		</div>
		<div id="Lien_tableau_de_bord">
			<a href="/index.php">Tableau de bord</a></li>
		</div>
		<div id="menu_reunion">
			<p> Réunion <p>
			<ul id="navigation">
			  <li><a href="/index.php/evenements/sondages_new">créer une réunion</a></li>
			  <li><a href="#">réunions à venir</a></li>
			  <li><a href="#">réunion en sondage</a></li>
			  <li><a href="#">réunion passées</a></li>
			</ul>
		</div>

		<div id="menu_groupe">
			<p> Groupes <p>
			<ul id="navigation">
			  <li><a href="#">Créer un groupe</a></li>
			  <li><a href="#">Voir les groupes</a></li>
			</ul>
		</div>
	</div>          
<?php } ?>
<div class="container">