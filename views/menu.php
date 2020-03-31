	<div id="menu">
		<?php if ($user_has_photo) { ?>
			<div id="photo_profil">
				<img src="/index.php/evenements/photos_get/<?= $logged_user->numUser ?>?thumbnail" alt="photo de profil">
			</div>
		<?php } ?>
		<div id="info_compte">
			<p> <span class="user_menu">Prénom : </span><span class="info_user_menu"> <?= $logged_user->prenom ?></span>
				<p>
					<p><span class="user_menu">Nom :</span><span class="info_user_menu"> <?= $logged_user->nom ?></span>
						<p>
							<ul>
								<li class="lien_menu"><a href="/index.php/evenements/mon_compte">Mon compte</a></li>
								<li class="lien_menu"><a href="/index.php/sessions/sessions_destroy">Se déconnecter</a></li>
							</ul>
		</div>

		<div id="Lien_tableau_de_bord">
			<a href="/index.php" class="cat_menu">Tableau de bord</a></li>
		</div>

		<div id="menu_reunion">
			<p class="cat_menu"> Réunion <p>
					<ul id="navigation">
						<li class="lien_menu"><a href="/index.php/evenements/sondages_new">Créer une réunion</a></li>
						<li class="lien_menu"><a href="/index.php/evenements/reunions_a_venir">Réunions à venir</a></li>
						<li class="lien_menu"><a href="/index.php/evenements/reunions_en_sondages">Réunions en sondages</a></li>
						<li class="lien_menu"><a href="/index.php/evenements/reunions_passees">Réunion passées</a></li>
					</ul>
		</div>

		<div id="menu_groupe">
			<p class="cat_menu"> Groupes <p>
					<ul id="navigation">
						<li class="lien_menu"><a href="/index.php/evenements/creer_un_groupe">Créer un groupe</a></li>
						<li class="lien_menu"><a href="/index.php/evenements/voir_les_groupes">Voir les groupes</a></li>
					</ul>
		</div>
	</div>