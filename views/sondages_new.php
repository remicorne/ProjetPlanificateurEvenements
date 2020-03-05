<div id="sondage_new">
	<form role="form" action="/index.php/evenements/sondages_add" method="post">
		<p>Titre :</p>
		<input class="form-control"  name="titre" type="text"  required/>
		<p>Lieu :</p>
		<input class="form-control"  name="lieu" type="text">
		<p>Ajouter une description : </p>
		<textarea name="message" rows="8" cols="45">  Description (facultative)</textarea><br>
		<div>
		  <p>Dates et horaires de la réunion :</p><br>
		  <input type="date" id="date1" name="date1" value= "<?=date('Y-m-d')?>" min= "<?=date('Y-m-d')?>" max="2050-12-31">&nbsp;&nbsp;&nbsp;
		  <input type="time" id="horaireD1" name="horaireD1" min="09:00" max="18:00" required> &nbsp;&nbsp;&nbsp;
		  <input type="time" id="horaireF1" name="horaireF1" min="09:00" max="18:00" required>
		</div>

			  
		<div>
			<input type="date" id="date2" name="date2" value=<?=date('Y-m-d')?> min=<?= date('Y-m-d')?> max="2050-12-31">&nbsp;&nbsp;&nbsp;
			<input type="time" id="horaireD2" name="horaireD2" min="09:00" max="18:00" required> &nbsp;&nbsp;&nbsp;
			<input type="time" id="horaireF2" name="horaireF2" min="09:00" max="18:00" required>
		</div>
	    <div>
		   <div>
			   <input type="date" id="date3" name="date3" value=<?= date('Y-m-d')?> min=<?= date('Y-m-d')?>  max="2050-12-31">&nbsp;&nbsp;&nbsp;
			   <input type="time" id="horaireD3" name="horaireD3" min="09:00" max="18:00" required> &nbsp;&nbsp;&nbsp;
			   <input type="time" id="horaireF3" name="horaireF3" min="09:00" max="18:00" required>
		   </div>
		 </div><br>
	     <input type="submit" class="btn btn-lg btn-primary btn-block" value="Terminer">
  	</form>
</div>