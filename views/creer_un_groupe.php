<script type="text/javascript" src="/assets/js/script_gestion_des_groupes.js"></script>

<div class="container">

	<?php if (isset($error)) { ?>
		<div class="alert alert-warning" role="alert"><?= $error ?></div>
	<?php } ?>

	<div>
		<fieldset>
			<p>Ajouter des personnes : </p>
			<input id="input_personne" name="personne" type="text" onkeyup="chercherDesPersonnes(this, <?= $logged_user->numUser ?>)" list="list_personnes"/>  
		</fieldset>
	</div>

	<!-- remplis avec fonction js -->
	<div id="display_persons">
		<table id="tab_persons" name="tab_persons"></table> 
	</div>

	<!-- un input avec les membres du groupe est rajouté avec fonction js -->
	<div id="div_creer_groupe">
		<fieldset>
			<form method="post" action="/index.php/evenements/ajout_groupe_bd" onsubmit="return ajouterGroupeALaBd(this)">
				<p>Entrer le nom du groupe : </p>
				<input id="nom_groupe" name="nom_groupe" type="text" required>  
				<input type="hidden" name="proprietaire" value="<?=$logged_user->numUser ?>">
				<button>Creer le groupe</button>
			</form>
		</fieldset>

		<!-- remplis avec fonction js -->
		<div id="display_persons_ajoutes">
			<table id="tab_persons_ajoutes" name="tab_persons"></table> 
		</div>
	</div>

</div>