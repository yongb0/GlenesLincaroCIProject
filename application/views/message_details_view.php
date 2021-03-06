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
                                         <?php 
                                            if ($r['unread'] > 0) {
                                                echo '<div class="unread">'.$r['unread'].'</div>';
                                            }
                                        ?>
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
                                
                                <!-- <div class="delConversation"><?php echo anchor('message/remove/'.$to_id, 'Delete Conversation'); ?></div> -->
                                <div class="delConversation">
                                    <a href="javascript:void(0)" onClick="delMessage()">
                                        <span class="glyphicon glyphicon-trash"></span> Delete Conversation
                                    </a>
                                </div>
                                
							</div>
                                
							<div class="panel-body msg_container_base">
                                <?php if ($message_count > 5) { ?>
                                    <a href="javascript:void(0);" onClick="showMore();" id="moreMsg" style="text-decoration:underline;">Show more</a>
                                <?php } else { ?>
                                    <a href="javascript:void(0);" onClick="showMore();" id="moreMsg" style="text-decoration:underline;display:none;">Show more</a>
                                <?php } ?>
                                
                                <input type="hidden" name="limit" id="limit" value="5"/>
                                <input type="hidden" name="offset" id="offset" value="5"/>
                                <input type="hidden" name="to_id" id="to_id" value="<?php echo $to_id; ?>"/>
                                <div id="rezult"></div>
								
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
                                       
                                       <div class="row msg_container base_sent" id="msg_<?php echo $msg['id']; ?>">
                                            <div class="col-md-10 col-xs-10" style="position:relative">
                                                <div class="messages msg_sent">
                                                    <p><?php echo $msg['content']; ?></p>
                                                    <div class="timeSent"><?php echo $timespan; ?></div>
                                                    <a href="javascript:void(0)" class='msgDel' onClick="del_single_msg(<?php echo $msg['id']; ?>); ">x</a>
                                                </div>
                                            </div>
                                            <div class="col-md-2 col-xs-2 avatar">
                                                <!--<img src="<?php echo $img; ?>" class=" img-responsive ">-->
                                            </div>
                                        </div>
                                   
                                  <?php } else { ?>
                                       
                                        <div class="row msg_container base_receive" id="msg_<?php echo $msg['id']; ?>">
                                            <div class="col-md-2 col-xs-2 avatar">
                                                <img src="<?php echo $img; ?>" class=" img-responsive ">
                                            </div>
                                            <div class="col-md-10 col-xs-10" style="position:relative">
                                                <div class="messages msg_receive">
                                                    <p><?php echo $msg['content']; ?></p>
                                                    <div class="timeSent"> <?php echo $timespan; ?></div>
                                                    <a href="javascript:void(0)" class='msgDel' onClick="del_single_msg(<?php echo $msg['id']; ?>); ">x</a>
                                                </div>
                                            </div>
                                        </div>
                                        <script type="text/javascript">
                                            jQuery.ajax({
                                            type: 'POST',
                                            url: '<?php echo base_url(); ?>message/set_seen_message', 
                                            dataType: 'json',
                                            data:{
                                                msg_id : '<?php echo $msg['id']; ?>'
                                                },
                                            success :function(data){
                                                
                                                   /*  if(data.status == 'success'){
                                                       
                                                    }else{
                                                       
                                                    } */
                                                
                                            }
                                        });
                                        </script>
                                   <?php
                                   
                                   }
                               }
                                ?>
                                <div class='reply-html'></div>
							</div>
                           
							<div class="panel-footer">
                                <!--<form action="<?php echo base_url(); ?>message/details/<?php echo $to_id ?>" method="post" accept-charset="utf-8" style="width:100%">-->
                                <form action="javascript:void(0)" id="replyForm" method="post" accept-charset="utf-8" style="width:100%">
                                    <div class="input-group">
                                            <input id="btn-input" type="text" name="reply" class="form-control input-sm chat_input reply-input" placeholder="Write your message here..." />
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

<div id="popup" style="display: none;">
    <span class="button b-close"><span>X</span></span>
       <div class="popHolder">
            <div class="popMessage">Do you really want to delete this conversation?</div>
            <div class="popButtonHolder">
                    <a href="javascript:void(0)" class="delConv glOk">DELETE</a>  
                    <a href="javascript:void(0)" class="b-close glCancel">CANCEL</a>
            </div>
       </div>
    <!--<span class="logo">bPopup</span>-->
</div>
<script type="text/javascript">
    function showMore(){
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>message/loadmore',
            dataType: 'json',
            data:{
                offset : jQuery('#offset').val(), 
                limit : jQuery('#limit').val(),
                to_id : jQuery('#to_id').val()
                },
            success :function(data){
                if (data.result == 1) {
                    jQuery('#rezult').after(data.view);
                    jQuery('#offset').val(data.offset);
                    jQuery('#limit').val(data.limit);
                    if (data.cnext > 0) {
                        
                    } else {
                        jQuery('#moreMsg').hide();
                    }
                } else if (data.result == 0) {
                    jQuery('#moreMsg').hide();
                }
                
            }
        });
    }
    
    function delMessage() {
        jQuery('#popup').bPopup();  
    }
     
    function del_single_msg(msg_id) {
        
        //alert(jQuery('#msg_'+msg_id).css('background','yellow'));
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>message/delete_single', 
            dataType: 'json',
            data:{
                id : msg_id
                },
            success :function(data){
                if(data.status == 'success'){
                   jQuery('#msg_'+msg_id).hide();
                }else{
                   
                }
            }
        });
    }
    
    jQuery('.delConv').click(function(){
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>message/remove', 
            dataType: 'json',
            data:{
                to_id : '<?php echo $to_id; ?>'
                },
            success :function(data){
                if(data.status == 'success'){
                    jQuery('#popup').bPopup().close();  
                    jQuery('.panel.panel-default').html();
                    window.location = '<?php echo base_url(); ?>message/home';
                }else{
                    window.location = '<?php echo base_url(); ?>user/login';
                }
            }
        });
    });
    
    jQuery('#replyForm').submit(function(){
        jQuery.ajax({
            type: 'POST',
            url: '<?php echo base_url(); ?>message/reply', 
            dataType: 'json',
            data:{
                    to_id : '<?php echo $to_id; ?>',
                    reply : jQuery('.reply-input').val()
                },
            success :function(data){
                if(data.status == 'success'){
                   jQuery('.reply-html').append(data.message_html);
                   if (jQuery('.msg_container').length > 5) { 
                        jQuery('.msg_container_base').find('.msg_container').first().remove();
                        jQuery('#moreMsg').show();
                   }
                   console.log(jQuery('.msg_container :hidden').length);
                   
                   jQuery('.reply-input').val('');
                }else if(data.status == 'error'){
                   jQuery('.reply-html').after('Message not sent.');
                   jQuery('.reply-input').val('');
                }
            }
        });
    });
    
</script>
	
