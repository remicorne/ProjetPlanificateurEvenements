<script type="text/javascript" src="/assets/js/script_gestion_des_groupes.js"></script>

<div class="container">

	<div id="div_personnes_cherche" class="div_personnes_cherche_view_creer_un_groupe">
		<p><b>Ajouter des personnes :</b> </p>
		<input id="input_personne" type="text" maxlength="20" onkeyup="remplirTabPersonsCherches('tab_persons', this, <?= $logged_user->numUser ?>)" />
		<!-- remplis avec fonction js 'remplirTabPersonsCherches' -->
		<div id="display_persons">
			<table id="tab_persons" class="table table-striped tab_persons_view_creer_un_groupe"></table>
		</div>
	</div>

	<!-- un input avec les membres du groupe est rajouté avec fonction js -->
	<div id="div_creer_groupe" class="div_creer_groupe_view_creer_un_groupe">
		<form method="post" action="/index.php/evenements/ajout_groupe_bd" onsubmit="return ajouterGroupeALaBd(this)">
			<p><b>Entrer le nom du groupe :</b> </p>
			<input id="nom_groupe" name="nom_groupe" type="text" maxlength="10" required>
			<input type="hidden" name="proprietaire" value="<?= $logged_user->numUser ?>">
			<button>Creer le groupe</button>
		</form>
		<!-- remplis avec fonction js -->
		<div id="display_persons_ajoutes">
			<table id="tab_persons_ajoutes" class="tab_persons_ajoutes_view_creer_un_groupe"></table>
		</div>
	</div>

</div>