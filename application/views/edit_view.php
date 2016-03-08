<div class="container">
    <div class="row">
          <div class="span4">
            <h2>User Profile</h2>
			 
			<div class="paragraphs">
			  <div class="row">
				<div class="span4 profImg">
				<?php
					$profile_image = $image;
					if($profile_image==NULL)
						$img = 'images/default-profile.png';
					else
						$img = 'images/avatar/'.$profile_image;
				?>
                <?php echo img(array('src' => $img, 'alt'=> 'alt information', 'class' => 'img-thumbnail' )); ?>
				  <div class="ChImg">
					<?php 
						
						/* echo $this->Form->create('User',array('enctype'=>'multipart/form-data')); 
							echo $this->Form->input('upload', array('type' => 'file'));
							echo $this->Form->button('Upload', array('class' => 'btn btn-info') ); 
						echo $this->Form->end(); */
				
					?>
				  </div>
				  <div class="content-heading userInfo">
						<div style="width:45%! important;">
						
                            <?php echo form_open("user/edit/".$id); ?>
                        
                            
                            <fieldset>
                               <div class="input text required">
                                   <label for="UserName">Name</label>
                                   <input name="name" placeholder="Name" maxlength="50" type="text" value="<?php if(isset($name)) echo $name; ?>" id="UserName" required="required">
                               </div>
                               
                               <div class="input text" style="position:relative">
                                    <label for="birthDate">Birthdate</label>
                                    <input name="birthdate" id="birthDate" type="text" value="<?php echo $birthdate; ?>">
                                    <button type="button" class="Zebra_DatePicker_Icon">Pick a date</button>
                                </div>
                                
                                <div class="input text" style="position:relative">
                                     <label for="birthDate">Gender</label><br />
                                    <input type="hidden" name="gender" id="UserGender_" value=""><input type="radio" name="gender" id="UserGenderM" value="m">
                                    <label for="UserGenderM">Male</label>
                                    <input type="radio" name="gender" id="UserGenderF" value="f">
                                    <label for="UserGenderF">Female</label>
                                </div>
                                <div class="input textarea">
                                    <label for="UserHobby">Hobby</label>
                                    <textarea name="hobby" cols="30" rows="6" id="UserHobby"><?php if(isset($hobby)) echo $hobby; ?></textarea>
                                </div>
                                <button class="btn btn-info" type="submit">Update</button>	
                            </fieldset>
								
							<?php echo form_close(); ?>
						</div>
					
				  </div>
				 
				</div>
			  </div>
			</div>
           </div>
        </div><!-- @end .row -->
</div>
	
<script type="text/javascript">
jQuery(document).ready(function(){
	
	//enable datepicker
	jQuery("#birthDate").Zebra_DatePicker({
		format: 'Y-m-d'
	});
	 
});
</script>