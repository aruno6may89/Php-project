<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8" lang="en"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9" lang="en"> <![endif]-->
<!--[if gt IE 8]><!--> 
<html class="no-js" lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>i-Bizsuite-<?=ucwords($page)?></title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
  		<!--<link rel="stylesheet" href="css/normalize.css">-->
        <link rel="stylesheet" href="<?=CSS_DIR?>bootstrap.min.css">
        <link rel="stylesheet" href="<?=CSS_DIR?>DT_bootstrap.css">
        <link rel="stylesheet" href="<?=CSS_DIR?>main.css">
        <link rel="stylesheet" href='<?=CSS_DIR?>chosen.css'>
        <link rel="stylesheet" href='<?=CSS_DIR?>datepicker.css'>
        <script src="<?=JS_DIR?>vendor/modernizr-2.6.2.min.js"></script>
        <script src="<?=JS_DIR?>constants.js"></script>
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="<?=JS_DIR?>/vendor/jquery-1.9.1.min.js"><\/script>')</script>
	<script src="<?=JS_DIR?>vendor/bootstrap.min.js"></script>
        <script src="<?=JS_DIR?>vendor/jquery.dataTables.js"></script>
        <script type="text/javascript" src='<?= JS_DIR ?>jquery.validate.min.js'></script>
          <script src='<?=JS_DIR?>chosen/chosen.jquery.min.js'></script>
        <script src='<?=JS_DIR?>datepicker/js/bootstrap-datepicker.js'></script>
        <!-- Developed JS scripts follows -->
        
        <script src="<?=JS_DIR?>plugins.js"></script>
        <script src="<?=JS_DIR?>main.js"></script>
      
    </head>
    <body
        <?php
        if(!$this->session->userdata('is_logged')){
        ?>
        class="app-login"
        <?php
        }
        ?>>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <noscript></noscript>
        <!-- App main html start -->
        <div class="app-main">
        	<header class="navbar">
            	<div class="nav-border-highlight"></div>
                <?php
                if($this->session->userdata('is_logged'))
                {
                ?>
                <nav class="navbar-static-top nav-bg">
                  <div class="container clearfix">
                  	<a href="#" class="brand" title="i-Bizsuiet">
                        <img src="<?=IMG_DIR?>i-Bizsuite-Logo.png" width="153" height="36" alt="i-Bizsuiet" >
                    </a>
                    <ul class="main-nav pull-left">
                        <li>
                            <a href="<?=BASE_URL?>/company"
                     		<?php 
                     		if($page=="company")
                    		{
                    		?>
                    			class="active"
                    		<?php
                    		}
                            ?>>Companies</a>
                        </li>
                        <li>
                            <a href="<?=BASE_URL?>/requirements"<?php  
                     		if($page=="requirement")
                    		{
                    		?>
                    			class="active"
                    		<?php
                    		}
                            ?>      >Requirements</a>
                        </li>
                        <li>
                            <a href="<?=BASE_URL?>/candidate"<?php 
                     		if($page=="candidate")
                    		{
                    		?>
                    			class="active"
                    		<?php
                    		}
                            ?>     >Candidates</a>
                        </li>
                        <li>
                            <a href="<?=BASE_URL?>/contacts"<?php  
                     		if($page=="contact")
                    		{
                    		?>
                    			class="active"
                    		<?php
                    		}
                            ?>>Contacts</a>
                        </li>
                        <li>
                            <a href="#">Reports</a>
                        </li>
                    </ul>
                    <ul class="logout-menu pull-right">
                    	<li class="dropdown">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            	<div class="text-truncate"><?=$this->session->userdata('username')?></div>
                                <span class="caret"></span>
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a href="#">Settings</a>
                                </li>
                                <li>
                                    <a href="#">Help</a>
                                </li>
                                <li>
                                    <a href="<?=BASE_URL?>/login/logout">Logout</a>
                                </li>
                            </ul>
                        </li>
                    </ul>
                  </div>
                </nav>
                <?php
                }
                ?>
            </header>