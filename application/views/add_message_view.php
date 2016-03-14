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
                        <form action="<?php echo base_url(); ?>message/add" class="well" id="MessageAddForm" method="post" accept-charset="utf-8">
                            <div style="display:none;">
                                <input type="hidden" name="_method" value="POST">
                            </div>	
                            <div id="success"></div>
                            <div id="saving" style="display:none;"><img src="/GlenesLincaroProject/img/pre_loader.gif"></div>
                             <?php
                                if (isset($error)) {
                                    echo '<span class="err" style="color:red;">'.$error.'</span>';
                                }
                            ?>
                            <fieldset class="regGlen">
                                <legend>New Message</legend>
                                <div class="form-group searchRecip" style="position:relative;">
                                    <div id="recip-error"></div>
                                    <input type="text" id="recipient-name" autocomplete="off" name="recipient" class="form-control" value="<?php echo set_value('recipient'); ?>" placeholder="Search for a recipient" required="required">        
                                     <ul class="dropdown-menu txtrecipient" style="margin-left:15px;margin-right:0px;" role="menu" aria-labelledby="dropdownMenu"  id="DropdownRecip">
                                     </ul>
                                </div>
                                <input type="hidden" name="to_id" class="form-control" id="message-to">
                                <input type="hidden" name="from_id" value="<?php echo $this->session->userdata('user_id'); ?>" id="MessageFromId">
                                <div class="form-group required">
                                    <label for="MessageContent">Content</label>
                                    <textarea name="content" class="form-control" placeholder="Message" cols="30" rows="6" id="MessageContent" required="required"><?php echo set_value('content'); ?></textarea>
                                </div>
                                <button class="btn btn-info" type="submit">Send</button>    
                            </fieldset>
                            
                        </form>
                    </div><!-- end row -->

           </div>
    
	</div>
</div>


<script type="text/javascript">
jQuery(document).ready(function () {
    jQuery("#recipient-name").keyup(function () {
    jQuery('#recip-error').html('');
    jQuery('.err').html('');
    jQuery('#recipient-name').removeClass('recip-error');
    if (jQuery("#recipient-name").val().length > 2) {   
        jQuery.ajax({
            type: "POST",
            url: "http://localhost/GlenesLincaroCIProject/user/search_user",
            data: {
                keyword: jQuery("#recipient-name").val()
            },
            dataType: "json",
            success: function (data) {
                
                if (data.status == 'success') {
                    
                    if (data.res.length > 0) {
                        jQuery('#DropdownRecip').empty();
                        jQuery('#recipient-name').attr("data-toggle", "dropdown");
                        jQuery('#DropdownRecip').dropdown('toggle');
                    }
                    else if (data.res.length == 0) {
                        jQuery('#recipient-name').attr("data-toggle", "");
                    }
                    jQuery.each(data.res, function (key,value) {
                        
                        if (data.res.length >= 0) {
                            var prof_image = value["image"];
                            if (prof_image !='') {
                                var src = 'src="<?php echo base_url(); ?>images/avatar/'+prof_image+'"';
                            }else{
                                var src = 'src="<?php echo base_url(); ?>images/default-profile.png"';
                            }
                            jQuery('#DropdownRecip').append('<li role="presentation" data-val="'+value['id']+'"><img '+src+' style="width:30px;"> ' + value['name'] + '</li>');
                        }
                    });
                }else if(data.status = 'error') {
                
                    jQuery('#recipient-name').addClass('recip-error');
                    jQuery('#recip-error').html(jQuery('#recipient-name').val()+' not found');
                }
            }
        });
    }
        
    });
    jQuery('ul.txtrecipient').on('click', 'li', function () {
        jQuery('#recipient-name').val(jQuery(this).text());
        jQuery('input#message-to').val(jQuery(this).data('val'));
    });
});
</script>
<?php 
/* if($this->Session->check('Auth.User')){
echo $this->Html->link( "Return to Dashboard",   array('action'=>'index') ); 
echo "<br>";
echo $this->Html->link( "Logout",   array('action'=>'logout') ); 
}else{
echo $this->Html->link( "Return to Login Screen",   array('action'=>'login') ); 
} */
?>