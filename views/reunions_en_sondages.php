<div class="container">
	<?php if (isset($error)) { ?>
		<div class="alert alert-warning" role="alert"><?= $error ?></div>
	<?php } ?>
	<div id="div_evenements" >
     	<p>sondages en cours</p>
    	<table id="tab_evenements">
    		<tr> 
    			<th>nom événement</th> 
    			<th>description</th> 
    			<th>votre statut</th>
    			<th>voté</th>
    			<th>voir sondages</th>
    		</tr>
			<?php foreach ($events as $evt) { ?>
			<tr>
				<td><?= $evt['titre'] ?></td>
				<td><?= $evt['descri'] ?></td>
				<td><?= $evt['statut'] ?></td>
				<td><?= $evt['aVote'] ?></td>
				<form method="post" action="/index.php/evenements/reunions_en_sondages"> 
					<input type="hidden" name="numEvent" value=<?=$evt['numEvent']?> >
					<td><button type="submit">voir</button></td>
				</form>
			</tr>
    		<?php } ?>    	
    	</table>
	</div>

<?php if($events!=null) { ?>
	<div id="div_sondages" >
     	<h3>Evenement : <?=$event_visu['titre']?> </h3>
    	<p>description : </p>
    	<table id="tab_sondages">
    		<tr> 
    			<th>Participants</th>
    			<!-- on affiche les dates de sondages. -->
    			<?php foreach ($sondages_event as $s) { ?>
    			<th>
    				<?=$s['date_sond']?> <br>
    				<?=$s['heureD']?> - <?=$s['heureF']?>
    			</th>
    			<?php } ?> 
    		</tr>
    		<!-- La première ligne est pour le vote de l'utilisateur.  -->
    		<tr> 
    			<td><b>vous</b></td>
    			<form method="post" action="/index.php/evenements/vote_reunion_en_sondages/<?=$event_visu['numEvent']?>/<?=$numPart?>">
	    			
	    			<?php if($event_visu['statut']=='createur') { ?>

	    				<?php for ($i=0; $i<count($sondages_event) ; $i++) { ?>
	    						<th> <input type="radio" name="radio" value="<?=$sondages_event[$i]['numSond']?>" > </th>
	    				<?php } ?> 
	    					<th> <button type="submit">choisir la date de l'evenement</button> </th>
	    			
	    			<?php }else{ ?>

	    				<?php for ($i=0; $i<count($sondages_event) ; $i++) { ?>
	    					<?php if($repUser[$i]) { ?>
	    						<th> <input type="checkbox" name="checkbox[]" value="<?=$sondages_event[$i]['numSond']?>" checked> </th>
	    					<?php }else{ ?>
	    						<th> <input type="checkbox" name="checkbox[]" value="<?=$sondages_event[$i]['numSond']?>" > </th>
	    					<?php } ?>
	    				<?php } ?> 
	    					<th> <button type="submit">soumettre vote</button> </th>
	    			<?php } ?>

    			</form>
    		</tr>

    		<?php for ($i=0; $i<$nbPart; $i++) { ?>
    		<tr>	<!-- Si numPart = numPart de l'utilisateur on n'affiche pas. --> 
    			<?php if($sondages_event[0]['reps'][$i]['numPart'] == $numPart) continue;  ?>
    				<td><?= $sondages_event[0]['reps'][$i]['prenom'] ?> </td>
    				<?php foreach ($sondages_event as $rep) { ?>
    					<td> <?= $rep['reps'][$i]["reponse"]?></td>
    				<?php } ?>
    		</tr>
    		<?php } ?>
    	</table>
	</div>
<?php } ?>
</div>