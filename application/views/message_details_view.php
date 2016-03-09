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
                                        <?php echo anchor('message/details/'.$r['id'], ' <img src="'.$img.'"/>'.$name); ?>
                                    </li>
								<?php } ?>
							</ul>
						</div>
						<!-- END MENU -->
					</div>
					</div><!--end col-md-3 -->
                    
					<div class="col-md-8">
						<div class="panel panel-default">
							<div class="panel-heading top-bar" style="margin-bottom:10px;">
								<div class="col-md-8 col-xs-8">
									<h3 class="panel-title"><span class="glyphicon glyphicon-comment"></span> Message - <span class="recipName"><?php echo $to_name; ?></span></h3>
								</div>
								<!-- <div class="col-md-4 col-xs-4" style="text-align: right;">
									<a href="#"><span id="minim_chat_window" class="glyphicon glyphicon-minus icon_minim"></span></a>
									<a href="#"><span class="glyphicon glyphicon-remove icon_close" data-id="chat_window_1"></span></a>
								</div> -->
                                <div class="delConversation"><?php echo anchor('message/remove/'.$to_id, 'Delete Conversation'); ?></div>
							</div>
                                
							<div class="panel-body msg_container_base">
                            
								
                                <?php
                                foreach ($message_info as $msg) {
                                   $image = $msg['image'];
                                   if ($image!='') {
										$img = base_url().'images/avatar/'.$image;
									} else {
										$img = base_url().'images/default-profile.png';
									}
                                    
                                    //$timespan = timespan(strtotime($msg['created']), time()) . ' ago';
                                    $timespan = date('m-d-Y',strtotime($msg['created']));
                                   
                                   if ($msg['from_id'] == $my_id) {
                                       ?>
                                       
                                       <div class="row msg_container base_sent">
                                            <div class="col-md-10 col-xs-10" style="position:relative">
                                                <div class="messages msg_sent">
                                                    <p><?php echo $msg['content']; ?></p>
                                                    <div class="timeSent"><?php echo $timespan; ?></div>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-xs-2 avatar">
                                                <!--<img src="<?php echo $img; ?>" class=" img-responsive ">-->
                                            </div>
                                        </div>
                                   
                                  <?php } else { ?>
                                       
                                        <div class="row msg_container base_receive">
                                            <div class="col-md-2 col-xs-2 avatar">
                                                <img src="<?php echo $img; ?>" class=" img-responsive ">
                                            </div>
                                            <div class="col-md-10 col-xs-10" style="position:relative">
                                                <div class="messages msg_receive">
                                                    <p><?php echo $msg['content']; ?></p>
                                                    <div class="timeSent"><?php echo $timespan; ?></div>
                                                </div>
                                            </div>
                                        </div>
                                   <?php
                                   
                                   }
                               }
                                ?>
							</div>
                           
							<div class="panel-footer">
                                <form action="<?php echo base_url(); ?>message/details/<?php echo $to_id ?>" method="post" accept-charset="utf-8" style="width:100%">
                                    <div class="input-group">
                                            <input id="btn-input" type="text" name="reply" class="form-control input-sm chat_input" placeholder="Write your message here..." />
                                            <span class="input-group-btn">
                                                <button class="btn btn-primary btn-sm" id="btn-chat">Send</button>
                                            </span>
                                        
                                    </div>
                                </form>
							</div>
                            
						</div>
					</div><!--end col xs 12 -->
				
			  </div><!-- end row -->

           </div>
    
	</div>
</div>
	
