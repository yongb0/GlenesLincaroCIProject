<div class="container">
   
	<div id="loginbox" style="margin-top:50px;" class="mainbox col-md-6 col-sm-8 col-sm-offset-2">                    
            <div class="panel panel-info" >
                    <div class="panel-heading">
                        <div class="panel-title">Sign In</div>
                    </div>     

                    <div style="padding-top:30px" class="panel-body" >
					
                        <?php
                            if (isset($error)){
                                echo '<div id="login-alert" class="alert alert-danger col-sm-12">'.$error.'</div>';
                            }
                        ?>

                        <div style="display:none" id="login-alert" class="alert alert-danger col-sm-12"></div>
                            
                        <?php echo form_open("user/login"); ?>
                                    
                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
                                <input type="text" id="login-username email" name="email" class="form-control" value="" placeholder="username or email"/>                                    
                            </div>
                                
                            <div style="margin-bottom: 25px" class="input-group">
                                <span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
                                <input type="password" id="login-password pass" name="pass" class="form-control" value="" placeholder="password"/>
                            </div>
                                    

                            <div style="margin-top:10px" class="form-group">
                                <!-- Button -->

                                <div class="controls">
                                  <input type="submit" class="btn btn-info" value="Sign in" />
                  

                                </div>
                            </div>


                            <div class="form-group">
                                <div class="control">
                                    <div style="border-top: 1px solid#888; padding-top:15px; font-size:85%" >
                                        Don't have an account! 
                                    <?php echo anchor('user/register', 'Signup'); ?>
                                    </div>
                                </div>
                            </div>    
                        <?php echo form_close(); ?>  



                    </div>                     
            </div>  
        </div>
		
		
</div>
