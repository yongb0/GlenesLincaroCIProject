<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"> 
        <title><?php echo (isset($title)) ? $title : "My CI Site" ?> </title>
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css">
        <!--<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/style.css" />-->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/override.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/zebra_datepicker.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>css/jquery-ui.min.css"/>

        <!-- <script type="text/javascript" href="<?php echo base_url();?>js/jquery-1.12.1.js"></script> -->
        <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>
        
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery-migrate-1.2.1.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/zebra_datepicker.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery-ui.min.js"></script>
        <script type="text/javascript" src="<?php echo base_url();?>js/jquery.bpopup.min.js"></script>
        </head>
        <body>
            <div id="wrapper">
                <nav class="navbar navbar-default">
                  <div class="container-fluid">
                    <!-- Brand and toggle get grouped for better mobile display -->
                    <div class="navbar-header">
                      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                      </button>
                      <a class="navbar-brand" href="<?php echo base_url(); ?>">Message Board</a>
                    </div>

                    <!-- Collect the nav links, forms, and other content for toggling -->
                    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                      <ul class="nav navbar-nav">
                        <li class="active"><?php echo anchor('message/home', 'Message List'); ?></li>
                        <!-- <li><a href="#">Link</a></li> -->
                      </ul>
                      <ul class="nav navbar-nav navbar-right">
                      
                       <?php if ($this->session->userdata('logged_in') == true) { ?>
                            <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Hi, <?php echo $this->session->userdata('user_name') ?> <span class="caret"></span></a>
                              <ul class="dropdown-menu">
                                <li><?php echo anchor('user/profile', 'View Profile'); ?></li>
                                <!-- <li><a href="#">Another action</a></li>
                                <li><a href="#">Something else here</a></li> -->
                                <li role="separator" class="divider"></li>
                                <li><?php echo anchor('user/logout', 'Logout'); ?></li>
                              </ul>
                            </li>
                       <?php } else { ?>
                            <li><?php echo anchor('user/login', 'Login'); ?></li>
                            <li><?php echo anchor('user/register', 'Register'); ?></li>
                       <?php } ?>
                      </ul>
                    </div><!-- /.navbar-collapse -->
                  </div><!-- /.container-fluid -->
                </nav>