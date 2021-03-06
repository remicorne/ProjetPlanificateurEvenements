<script type="text/javascript" src="/assets/js/script_modifier_groupe.js"></script>

<div class="container">

	<?php if (isset($error)) { ?>
		<div class="alert alert-warning" role="alert"><?= $error ?></div>
	<?php } ?>

	<div id="div_persons_cherches" class="div_persons_cherches_view_mdf">
		<p>Ajouter les participants</p>
		<input type="text" id="input_recherche_personnes" onkeyup="remplirTabPersonsCherches('tab_persons', this, <?= $numGroupe ?>)">
		<button class="btn btn-light">Q</button>
		<!-- remplis avec fonction js 'remplirTabPersonsCherches' -->
		<table id="tab_persons" class="table table-bordered tab_persons_view_mdf"></table>
	</div>

	<div id="div_tab_groupe" class="div_tab_groupe_view_mdf">
		<div id="div_suppression" class="div_suppression_view_mdf">
			<form method="post" action="/index.php/evenements/supprimer_groupe/<?= $numGroupe ?>" onsubmit="return alert_suppression()">
				<button>Supprimer le groupe</button>
			</form>
		</div>
		<p>nom du groupe : </p>
		<form method="post" action="/index.php/evenements/modifier_nom_groupe/<?= $numGroupe ?>" onsubmit="return ecrire_ds_cookie_modif()">
			<input type="text" name="nomGroupe" value="<?= $nomGroupe ?>">
			<button>modifier</button>
		</form>
		<br>
		<br>
		<p><b> Membre du groupes :</b> </p>
		<table id="tab_groupe" class="table table-bordered tab_groupe_view_mdf">
			<?php foreach ($membres as $membre) { ?>
				<tr>
					<td> <img class="photo_user_view_mdf" src="/index.php/evenements/photos_get/<?= $membre['numUser'] ?>"></td>
					<td> <?= $membre['nom'] ?> </td>
					<td> <?= $membre['prenom'] ?> </td>
					<td> <?= $membre['email'] ?> </td>
					<?php if ($membre['proprietaire']) continue; ?>
					<form method="post" action="/index.php/evenements/retirer_user_groupe/<?= $membre['numUser'] ?>/<?= $numGroupe ?>">
						<td><button>retirer</button></td>
					</form>
				</tr>
			<?php } ?>
		</table>
	</div>
</div>