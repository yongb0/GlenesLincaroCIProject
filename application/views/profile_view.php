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
				  <!--<img style="float:left" src = "/img/profile-default.png" class = "img-thumbnail"/>-->
				  <?php //echo $this->Html->image($img, array('alt' => 'profile', 'class' => 'img-thumbnail', 'border' => '0', 'data-src' => 'holder.js/100%x100')); ?>
				  <?php echo img(array('src' => $img, 'alt'=> 'alt information', 'class' => 'img-thumbnail' )); ?>
				  <div class="content-heading userInfo">
					<?php
								
									if($gender=='m')
										$g = 'Male';
									else if($gender=='f')
										$g = 'Female';
									else	
										$g ='';
								$date = '';
								$joindate = '';
								$lastlogin = '';
								
								if ($birthdate!='') {
									$date = date('M-d-Y', strtotime($birthdate));
								} else {
                                    $date = '';
                                }
								if ($created!='') {
									$joindate = date('M-d-Y', strtotime($created));
								}
								if ($last_login_time!='') {
									$lastlogin = date('M-d-Y', strtotime($last_login_time));
								}

					?>
					
						<div class="row" style="margin-left:10px;">
						  <div class="span4"><?php echo anchor('user/edit/'.$id, 'Edit Information'); ?></div>
						  <div class="span4"><h3><?php echo $name; ?></h3></div>
						  <div class="span4">Gender: <span><?php echo $g; ?></span></div>
						  <div class="span4">Birthdate: <span><?php echo $date; ?></span></div>
						  <div class="span4">Join Date: <span><?php echo $joindate; ?></span></div>
						  <div class="span4">Last Login: <span><?php echo $lastlogin; ?></span></div>
						  <div class="span4" style="width:37%;">Hobby: <span><?php echo $hobby; ?></span></div>
						</div>
					
				  </div>
				 
				</div>
			  </div>
			</div>
           </div>
        </div><!-- @end .row -->
		</div>
	</div>
</div>


