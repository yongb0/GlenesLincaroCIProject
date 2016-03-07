<div id="content">

<div class="login_form">
	<h2>Login</h2>
	<?php echo form_open("user/login"); ?>
	    <label for="email">Email:</label>
    	<input type="text" id="email" name="email" value="" />
	    <label for="pass">Password:</label>
		<input type="password" id="pass" name="pass" value="" />
        <input type="submit" class="" value="Sign in" />
    <?php echo form_close(); ?>
</div><!--<div class="signin_form">-->
