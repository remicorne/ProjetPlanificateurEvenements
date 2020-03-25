	<div id="menu">
		<?php if($user_has_photo){ ?>
		<div id="photo_profil">
			<img src="/index.php/evenements/photos_get/<?=$logged_user->numUser?>?thumbnail" alt="photo de profil">
		</div>
		<?php } ?>
		<div id="info_compte">
			<p>prenom : <?= $logged_user->prenom ?>  <p>
			<p>nom : <?= $logged_user->nom ?>  <p>
			<ul>
				<li><a href="/index.php/evenements/mon_compte">mon compte</a></li>
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
			  <li><a href="/index.php/evenements/reunions_en_sondages">réunions en sondages</a></li>
			  <li><a href="#">réunions passées</a></li>
			</ul>
		</div>

	<div id="menu_groupe">
		<p> Groupes <p>
		<ul id="navigation">
		<li><a href="/index.php/evenements/creer_un_groupe">Créer un groupe</a></li>
		<li><a href="/index.php/evenements/voir_les_groupes">Voir les groupes</a></li>
		</ul>
	</div>
</div>          

