 <br /><br />
  <div class="container" style="width:600px;">
   <br /><br />
   <form action="/index.php/evenements/documents_add" method="post" enctype="multipart/form-data" >
    <div class="form-group">
      <div>
        <label>Ajouter des documents</label>
     </div>
     <select id="participants" name="participants[]" multiple class="form-control" required >

     <?php foreach($users_informations as $user_informations){ 

       if($user_informations['numUser'] === $logged_user->numUser){?>
        
        <option value=<?=$user_informations['numUser']?> selected="selected"><?=$user_informations['nom']?> <?=$user_informations['prenom']?> ( <?=$user_informations['email']?> ) [Organisateur]</option>
       <?php } else { ?>
        <option value=<?=$user_informations['numUser']?>><?=$user_informations['nom']?> <?=$user_informations['prenom']?> ( <?=$user_informations['email']?> )</option>
      <?php }} ?>
     </select>
    </div>

    <div class="form-group">
     <input type="submit" class="btn btn-info" name="submit" value="Terminer" />
    </div>

   </form>
   <br/>
  </div>
</body>
</html>


<script>
$(document).ready(function(){
 $('#participants').multiselect({
  nonSelectedText: 'Select participants',
  enableFiltering: true,
  enableCaseInsensitiveFiltering: true,
  buttonWidth:'400px'
 });
 
 $('#submit').on('submit', function(event){
  event.preventDefault();
  var form_data = $(this).serialize();

  
  $.ajax({
   url:"/index.php/evenements/participants_add",
   method:"POST",
   data:form_data,
   success:function(data)
   {
    $('#participants option:selected').each(function(){
     $(this).prop('selected', false);
    });
    $('#participants').multiselect('refresh');
    alert(data);
   }
  });
 });
 
 
});
</script>