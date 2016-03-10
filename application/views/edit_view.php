<div class="container">
    <div class="row">
          <div class="span4">
          
			 
			<div class="paragraphs">
			  <div class="row">
				<div class="span4 profImg">
                <div class="col-sm-3">
                  <h2>User Profile</h2>
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
                        if($upload_error!='') { 
                            echo '<div class="alert alert-danger">'.$upload_error.'</div>'; 
                        }
                    ?>
                    <form action="<?php echo base_url(); ?>user/edit/<?php echo $id; ?>" enctype="multipart/form-data" id="UserEditForm uploadForm" method="post" accept-charset="utf-8">
                        <div style="display:none;">
                            <input type="hidden" name="_method" value="PUT">
                        </div>
                        <div class="input file required">
                        <label for="UserUpload">Upload</label>
                        <input type="file" name="img" id="UserUpload pdffile" required="required" style="margin-bottom:20px;"></div>
                        <button class="btn btn-info" type="submit">Upload</button>
                    </form>
                    
				  </div>
                </div>
                <div class="col-sm-8">
				  <div class="content-heading userInfo">
						<div style="width:45%! important;">
						
                            <?php echo form_open("user/edit/".$id); ?>
                            <?php
                                if ($birthdate=='0000-00-00') {
                                    $birthdate = '';
                                }
                            ?>
                            
                            <fieldset>
                               <div class="input text required">
                                   <label for="UserName">Name</label>
                                   <input name="name" placeholder="Name" maxlength="50" type="text" value="<?php if(isset($name)) echo $name; ?>" id="UserName" required="required">
                               </div>
                               
                               <div class="input text" style="position:relative">
                                    <label for="birthDate">Birthdate</label>
                                    <input name="birthdate" id="birthDate" type="text" value="<?php echo $birthdate; ?>">
                                   
                                </div>
                                
                                <div class="input text" style="position:relative">
                                     <label for="birthDate">Gender</label><br />
                                    <input type="hidden" name="gender" id="UserGender_" value="">
                                    <input type="radio" name="gender" id="UserGenderM" value="m"<?php echo ($gender=='m')?'checked':'' ?>>
                                    <label for="UserGenderM">Male</label>
                                    <input type="radio" name="gender" id="UserGenderF" value="f"<?php echo ($gender=='f')?'checked':'' ?>>
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
           </div>
        </div><!-- @end .row -->
</div>
<script type="text/javascript">
jQuery(document).ready(function(){

	//enable datepicker
	jQuery("#birthDate").Zebra_DatePicker({
         format: 'Y-m-d',
         view: 'years',
         direction: -3000
	});
});
</script>