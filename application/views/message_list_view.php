<div class="container">
    <div class="row">
          <div class="span4">
            <h2>Message List</h2>
			 
			  <div class="row">
					<div class="col-md-2">
					<div class="profile-sidebar">
						<!-- SIDEBAR MENU -->
						<div class="profile-usermenu">
							<ul class="nav">
								<li>
									<!-- <a href="Message/add">
									<i class="glyphicon glyphicon-plus"></i>
									New Message</a> -->
									<?php echo anchor('message/add', '<i class="glyphicon glyphicon-plus"></i> New Message'); ?>
								</li>
                                <?php foreach ($recipient_ids as $r) {
									$image = $r['image'];
									if ($image!='') {
										$img = base_url().'images/avatar/'.$image;
									} else {
										$img = base_url().'images/default-profile.png';
									}
                                    
                                    $name = $r['name'];
                                    if (strlen($name) > 14) {
                                        $name = substr($name, 0, 14).' ...'; 
                                    } 
								 ?>
                                    <li class="active recip-list">
                                        <!-- <a href="details/<?php echo $r['id']; ?>"> -->
                                        <?php echo anchor('message/details/'.$r['id'], ' <img src="'.$img.'"/>'.$name); ?>
                                       
                                    </li>
								<?php } ?>
							</ul>
						</div>
						<!-- END MENU -->
					</div>
					</div><!--end col-md-3 -->
					
                    <div class="col-md-8">
						
					</div><!--end col xs 12 -->
				
			  </div><!-- end row -->

           </div>
    
	</div>
</div>
	
