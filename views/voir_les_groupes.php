<script type="text/javascript" src="/assets/js/script_gestion_des_groupes.js"></script>

<div class="container">
	<?php if ($groupes != null) { ?>
		<div id="div_tab_groupes" class="div_tab_groupes_view_voir_groupes">
			<table id="tab_groupes" class="table table-bordered tab_groupes_view_voir_groupes">
				<tr>
					<th>Nom du groupe</th>
					<th>Nb membres</th>
				</tr>
				<?php foreach ($groupes as $groupe) { ?>
					<tr>
						<input type="hidden" value=<?= $groupe['numGroupe'] ?>>
						<td> <?= $groupe['nomGroupe'] ?> </td>
						<td> <?= $groupe['nbMembre'] ?> </td>
						<td><button onclick="afficherLesMembresGroupe(this.parentNode.parentNode.firstElementChild.value, this.parentNode.parentNode.children[1].innerHTML)">voir</button></td>
					</tr>
				<?php } ?>
			</table>
		</div>
		<!-- remplis avec fonction js -->
		<div id="div_groupe" class="div_groupe_view_voir_groupes">
			<p id="p_membres"></p>
			<div id="div_mdf_g"></div>
			<table id="tab_groupe" class="table table-bordered tab_groupe_view_voir_groupes"></table>
		</div>
	<?php } else { ?>
		<div>
			<h3>Vous n'avez pas créé de groupe pour le moment.</h3>
		</div>
	<?php } ?>

</div>