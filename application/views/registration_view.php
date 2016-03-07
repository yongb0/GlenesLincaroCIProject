<div id="content">
<div class="signup_wrap">
</div><!--<div class="signup_wrap">-->

 <div id="signupbox" style="margin-top:50px" class="mainbox col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
                    <div class="panel panel-info">
                        <div class="panel-heading">
                            <div class="panel-title">Sign Up</div>
                            <div style="float:right; font-size: 85%; position: relative; top:-10px"><a id="signinlink" href="#" onclick="$('#signupbox').hide(); $('#loginbox').show()">Sign In</a></div>
                        </div>  
                        <div class="panel-body" >
                           <?php echo validation_errors('<p class="error">'); ?>
							<?php echo form_open("user/registration", array('class' => 'form-horizontal', 'role' => 'form')); ?>
                                
                                <div id="signupalert" style="display:none" class="alert alert-danger">
                                    <p>Error:</p>
                                    <span></span>
                                </div>
                                    
                                
                                  
                                <div class="form-group">
                                    <label for="email" class="col-md-3 control-label">Username</label>
                                    <div class="col-md-9">
                                       <input type="text" id="user_name" name="user_name" class="form-control" value="<?php echo set_value('user_name'); ?>" placeholder="Username"/>
                                    </div>
                                </div>
								
								<div class="form-group">
                                    <label for="email" class="col-md-3 control-label">Name</label>
                                    <div class="col-md-9">
                                       <input type="text" id="name" name="name" class="form-control" value="<?php echo set_value('user_name'); ?>" placeholder="Name"/>
                                    </div>
                                </div>
                                    
                                <div class="form-group">
                                    <label for="firstname" class="col-md-3 control-label">Email</label>
                                    <div class="col-md-9">
                                        <input type="text" id="email_address" class="form-control" name="email_address" value="<?php echo set_value('email_address'); ?>" placeholder="Email" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="lastname" class="col-md-3 control-label">Password</label>
                                    <div class="col-md-9">
                                       <input type="password" id="password" class="form-control" name="password" value="<?php echo set_value('password'); ?>" placeholder="Password" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="password" class="col-md-3 control-label">Confirm Password</label>
                                    <div class="col-md-9">
                                        <input type="password" id="con_password" class="form-control" name="con_password" value="<?php echo set_value('con_password'); ?>" placeholder="Confirm Password"/>
                                    </div>
                                </div>
                                    
                               
                                <div class="form-group">
                                    <!-- Button -->                                        
                                    <div class="col-md-offset-3 col-md-9">
                                       <input type="submit" class="greenButton btn btn-info" value="Submit" />
                                    </div>
                                </div>
                            
                           <?php echo form_close(); ?>
                         </div>
                    </div>

               
               
                
         </div> 

		
</div><!--<div id="content">-->