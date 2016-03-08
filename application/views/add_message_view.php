<div class="glogForm" style="width:45%! important;">

<form action="/GlenesLincaroProject/Message/add" class="well" id="MessageAddForm" method="post" accept-charset="utf-8">
    <div style="display:none;">
        <input type="hidden" name="_method" value="POST">
    </div>	
    <div id="success"></div>
	<div id="saving" style="display:none;"><img src="/GlenesLincaroProject/img/pre_loader.gif"></div>
    <fieldset class="regGlen">
        <legend>New Message</legend>
        <div class="form-group searchRecip" style="position:relative;">
            <input type="text" id="country" autocomplete="off" name="country" class="form-control" placeholder="Search for a recipient">        
             <ul class="dropdown-menu txtcountry" style="margin-left:15px;margin-right:0px;" role="menu" aria-labelledby="dropdownMenu"  id="DropdownCountry">
             </ul>
        </div>
        <input type="hidden" name="to_id" class="form-control" id="message-to">
        <input type="hidden" name="from_id" value="<?php echo $this->session->userdata('user_id'); ?>" id="MessageFromId">
        <div class="form-group required">
            <label for="MessageContent">Content</label>
            <textarea name="content" class="form-control" placeholder="Message" cols="30" rows="6" id="MessageContent" required="required"></textarea>
        </div>
        <button class="btn btn-info" type="submit">Send</button>    
    </fieldset>
	
</form>

</div>
<script type="text/javascript">
jQuery(document).ready(function () {
    jQuery("#country").keyup(function () {
        jQuery.ajax({
            type: "POST",
            url: "http://localhost/GlenesLincaroCIProject/user/search_user",
            data: {
                keyword: jQuery("#country").val()
            },
            dataType: "json",
            success: function (data) {
                if (data.length > 0) {
                    jQuery('#DropdownCountry').empty();
                    jQuery('#country').attr("data-toggle", "dropdown");
                    jQuery('#DropdownCountry').dropdown('toggle');
                }
                else if (data.length == 0) {
                    jQuery('#country').attr("data-toggle", "");
                }
                jQuery.each(data, function (key,value) {
                    if (data.length >= 0) {
                        var prof_image = value["image"];
                        if (prof_image !='') {
                            var src = 'src="<?php echo base_url(); ?>images/avatar/'+prof_image+'"';
                        }else{
                            var src = 'src="<?php echo base_url(); ?>images/default-profile.png"';
                        }
                        jQuery('#DropdownCountry').append('<li role="presentation" ><img '+src+' style="width:30px;"> ' + value['name'] + '</li>');
                    }
                });
            }
        });
    });
    jQuery('ul.txtcountry').on('click', 'li', function () {
        jQuery('#country').val(jQuery(this).text());
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