<div id="Overlay" style=" width:100%; height:100%; background-color:rgba(0,0,0,.5); border:0px #990000 solid; position:absolute; top:0px; left:0px; z-index:2000; display:none;"></div>
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
                         Upload profile<br />
                        <input type="file" id="image" name="image" required="required" style="width:200px;padding: 5px 0 5px 5px;border: 1px solid #D8D2D2;border-radius: 5px;" />
                        <input type="submit" value="Upload" class="btn btn-info" style="width:85px;" />
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
        
        <?php  if($imgSrc!=''){ //if an image has been uploaded display cropping area?>
            <script>
                jQuery('#Overlay').show();
                jQuery('#formExample').hide();
            </script>
            <div id="CroppingContainer" style="width:800px; max-height:600px; background-color:#FFF; position:relative; overflow:hidden; border:2px #666 solid; margin:50px auto; z-index:2001; padding-bottom:0px;position:absolute;top:50px;">  
            
                <div id="CroppingArea" style="width:500px; max-height:400px; position:relative; overflow:hidden; margin:40px 0px 40px 40px; border:2px #666 solid; float:left;">	
                    <img src="<?php echo base_url().'images/'.$imgSrc; ?>" border="0" id="jcrop_target" style="border:0px #990000 solid; position:relative; margin:0px 0px 0px 0px; padding:0px; " />
                </div>  
                <div id="InfoArea" style="width:180px; height:150px; position:relative; overflow:hidden; margin:40px 0px 0px 40px; border:0px #666 solid; float:left;">	
                   <p style="margin:0px; padding:0px; color:#444; font-size:18px;">          
                        <b>Crop Profile Image</b><br /><br />
                        <span style="font-size:14px;">
                            Using this tool crop / resize your uploaded profile image. <br />
                            Once you are happy with your profile image then please click save.
                        </span>
                   </p>
                </div>  
                <br />
                    <div id="CropImageForm" style="width:100px; height:30px; float:left; margin:10px 0px 0px 40px;" >  
                        <form action="<?php echo base_url(); ?>user/edit/<?php echo $id; ?>" method="post" onsubmit="return checkCoords();">
                            <input type="hidden" id="x" name="x" />
                            <input type="hidden" id="y" name="y" />
                            <input type="hidden" id="w" name="w" />
                            <input type="hidden" id="h" name="h" />
                            <input type="hidden" value="jpeg" name="type" /> <?php // $type ?> 
                            <input type="hidden" value="<?php echo $imgSrc; ?>" name="src" /> <!-- <input type="hidden" value="<?php echo $src; ?>" name="src" /> -->
                            <input type="submit" value="Crop Image" style="width:100px; height:30px;"   />
                        </form>
                    </div>
                    <div id="CropImageForm2" style="width:100px; height:30px; float:left; margin:10px 0px 0px 40px;" >  
                        <form action="<?php echo base_url(); ?>user/edit/<?php echo $id; ?>" method="post" onsubmit="return cancelCrop();">
                            <input type="submit" value="Cancel Crop" style="width:100px; height:30px;"   />
                        </form>
                    </div>            
                    
            </div><!-- CroppingContainer -->
	<?php } ?>
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

jQuery(function(){
	  
    jQuery('#jcrop_target').Jcrop({
      aspectRatio: 1,
	  setSelect:   [ 200,200,37,49 ],
      onSelect: updateCoords
    });

  });

  function updateCoords(c)
  {
    jQuery('#x').val(c.x);
    jQuery('#y').val(c.y);
    jQuery('#w').val(c.w);
    jQuery('#h').val(c.h);
  };

  function checkCoords()
  {
    if (parseInt(jQuery('#w').val())) return true;
    alert('Please select a crop region then press submit.');
    return false;
  }; 
//End JCrop Bits

	function cancelCrop(){
		//Refresh page				
		window.location = '<?php echo base_url(); ?>user/edit/<?php echo $id; ?>';
		return false;
	}
</script>