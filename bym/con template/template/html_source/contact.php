<?php
//+----------------------------------------------------------------+
//| CONTACT.PHP
//+----------------------------------------------------------------+

session_start();
$response=array();

//+----------------------------------------------------------------+
//| email settings
//+----------------------------------------------------------------+

$to = ""; /* you email address */
$subject ="Contact form message"; /* email subject */
$message ="You received a mail via your website contact form\n\n"; /* email messege prefix */

//+----------------------------------------------------------------+
//| post data validation
//+----------------------------------------------------------------+

if ($_POST) {	
	/* clean input & escape the special chars */
	foreach($_POST as $key=>$value) {
		if(ini_get('magic_quotes_gpc')) { $_POST[$key]=stripslashes($_POST[$key]); }
		$_POST[$key]=htmlspecialchars(strip_tags(trim($_POST[$key])), ENT_QUOTES);
	}	
	/* check name */
	if (!strlen($_POST['username'])) {
		$response['message']['username']="You must enter a <strong>name</strong>.";
	}
	/* check email */
	if (!strlen($_POST['email'])) {
		$response['message']['email']="You must enter an <strong>e-mail</strong>.";
	} elseif (!preg_match("/^[\w-]+(\.[\w-]+)*@([0-9a-z][0-9a-z-]*[0-9a-z]\.)+([a-z]{2,4})$/i", $_POST['email'])) {
		$response['message']['email']="Invalid <strong>e-mail</strong> address.";	
	}
	/* check website (if given) */
	if (strlen($_POST['website']) && !filter_var($_POST['website'], FILTER_VALIDATE_URL)) {
		$response['message']['website']="Invalid url.";
	}	
	/* check message */
	if (!strlen($_POST['message'])) {
		$response['message']['message']="You must enter a <strong>message</strong>.";
	}
	/* check captcha */
	if (!strlen($_POST['captcha'])) {
		$response['message']['captcha']="You must enter the <strong>captcha</strong>.";
	} elseif ($_POST['captcha']!=$_SESSION['captcha']) {
		$response['message']['captcha']="Invalid <strong>captcha</strong>.";	
	}			
	/* if no error */
	if (!isset($response['message'])) { $response['result']='success'; } else { $response['result']='error';}
}
	

//+----------------------------------------------------------------+
//| send the email
//+----------------------------------------------------------------+

if ($response['result']) {
	if ($response['result']=='success') {
		
		/* build the email message body */
		$message.= 'Sender name: '.$_POST['name']."\n";
		$message.= 'Sender email: '.$_POST['email']."\n";
		$message.= strlen($_POST['website']) ? 'Sender website: '.$_POST['website']."\n\n" : "Sender website: -\n\n";
		$message.= "Message: \n".$_POST['message'];
		
		/* send the mail */
		if(mail($to, $subject,$message)){
			$response['message']['mail_sent']='Your message has been sent successfully.';
		} else{
			$response['result']='error';
			$response['message']['mail_sent']='Something went wrong, please try again later.';
		}
	}
	/* if ajax request */
	if (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') { 
		print json_encode($response);
		exit;
	} 
	/* if reqular http request */
	else {
		$_SESSION['reponse']=$response;
		$_SESSION['postdata']=$_POST;
		header('location: '.$_SERVER['PHP_SELF']); 
		exit;
	}
}

//+----------------------------------------------------------------+
//| create session data
//+----------------------------------------------------------------+

$_SESSION['no1'] = rand(1,10);	/* first number */
$_SESSION['no2'] = rand(1,10);	/* second number */
$_SESSION['captcha'] = $_SESSION['no1']+$_SESSION['no2'];	/* captcha data */	
?><!DOCTYPE html>
<!-- imbus - Simple HTML Template v1.0 -->

<!--[if lt IE 7]>	<html dir="ltr" lang="en-US" class="lt-ie9 lt-ie8 lt-ie7"><![endif]-->
<!--[if IE 7]>		<html dir="ltr" lang="en-US" class="lt-ie9 lt-ie8"><![endif]-->
<!--[if IE 8]>		<html dir="ltr" lang="en-US" class="lt-ie9"><![endif]-->
<!--[if IE 9]>		<html dir="ltr" lang="en-US"  class="lt-ie10"> <![endif]-->
<!--[if gt IE 9]>	<!--><html dir="ltr" lang="en-US" class="gt-ie9 non-ie"> <!--<![endif]-->
<head>

<!-- meta tags -->
<meta charset="UTF-8">
<!--[if IE]><meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"><![endif]-->
<meta name="author" content="Granthweb">
<meta name="description" content="imbus - Simple HTML Template">
<meta name="keywords" content="">
<meta name="robots" content="index, follow">

<!-- mobile -->
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">

<!-- favicon -->
<link rel="icon" href="assets/images/favicon.ico" type="image/png">

<!-- page title -->
<title>Contact | imbus - Simple HTML Template</title>

<!-- css stylesheets -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400italic,400,700" rel="stylesheet" type="text/css">
<link href="assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
<link href="assets/css/style.css" rel="stylesheet" type="text/css">
<link href="assets/plugins/magnific-popup/magnific-popup.css" rel="stylesheet" type="text/css">

<!-- js head -->
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="assets/plugins/jquery-1.8.1.min.js"><\/script>')</script>
<script type="text/javascript" src="assets/js/scripts.js"></script>
</head>

<!-- body -->
<body id="top" class="stickymenu stickyfooter">

	<!-- #wrapper -->
	<div id="wrapper"> 
		
		<!-- #header-info -->
		<div id="header-info" class="wrapper">
			<div class="container fullwidth">
				<ul>
					<li><span class="header-icon-pin"></span>1419 Westwood Blvd, Los Angeles</li>
					<li><span class="header-icon-mail"></span><a href="#" class="link-style2">office@imbus.com</a></li>
					<li><span class="header-icon-phone"></span>(811) 108-4000</li>
				</ul>
			</div>
		</div>
		<!-- /#header-info --> 
		
		<!-- #header -->
		<div id="header" class="wrapper"> 
			
			<!-- #logo -->
			<div id="logo" class="container fullwidth"><a href="http://templates.granthweb.com/imbus"><img src="assets/images/logo_anim_1.gif" alt="imbus"></a></div>
			<!-- /#logo --> 
			
			<!-- #primary-navigation -->
			<div id="primary-navigation" class="container fullwidth"> 
				
				<!-- .menu-responsive --> 
				<a class="menu-responsive"><span class="menu-icon-nav"></span><span class="menu-title">Navigation</span></a> 
				<!-- /.menu-responsive --> 
				
				<!-- .menu -->
				<ul class="menu clearfix">
					<li><a href="#"><span class="menu-icon-home"></span>Home</a>
						<ul>
							<li><a href="home.html">Home - Original</a></li>
							<li><a href="home_no_slider.html">Home - No Slider</a></li>
							<li><a href="#">Home - More Colors</a>
								<ul>
									<li><a href="home_red.html">Red Style</a></li>
									<li><a href="home_green.html">Green Style</a></li>
									<li><a href="home_orange.html">Orange Style</a></li>
									<li><a href="home_blue.html">Blue Style</a></li>
								</ul>
							</li>
						</ul>
					</li>
					<li><a href="#"><span class="menu-icon-about"></span>About</a>
						<ul>
							<li><a href="about.html">About Team</a></li>
							<li><a href="about_personal.html">About Personal</a></li>
						</ul>
					</li>
					<li><a href="#"><span class="menu-icon-features"></span>Features</a>
						<ul>
							<li><a href="typography.html">Typography</a></li>
							<li><a href="shortcodes.html">Shortcodes</a></li>
							<li><a href="#">Pricing Tables</a>
								<ul>
									<li><a href="pricing_1.html">Style 1</a></li>
									<li><a href="pricing_2.html">Style 2</a></li>
								</ul>
							</li>
							<li><a href="404.html">404 page</a></li>
						</ul>
					</li>
					<li><a href="#"><span class="menu-icon-portfolio"></span>Portfolio</a>
						<ul>
							<li><a href="portfolio-2cols.html">Portfolio 2 Columns</a></li>
							<li><a href="portfolio-3cols.html">Portfolio 3 Columns</a></li>
							<li><a href="portfolio-4cols.html">Portfolio 4 Columns</a></li>
							<li><a href="portfolio-single-half.html">Portfolio single half</a></li>
							<li><a href="portfolio-single-wide.html">Portfolio single wide</a></li>
						</ul>
					</li>
					<li><a href="#"><span class="menu-icon-blog"></span>Blog</a>
						<ul>
							<li><a href="blog.html">Blog</a></li>
							<li><a href="blog-single.html">Blog single</a></li>
						</ul>
					</li>
					<li class="current"><a href="contact.html"><span class="menu-icon-contact"></span>Contact</a></li>
				</ul>
				<!-- /.menu --> 
				
			</div>
			<!-- /#primary-navigation --> 
			
		</div>
		<!-- #/header --> 
		
		<!-- #header panel -->
		<div id="header-panel" class="wrapper header-parallax">
			<div id="header-map"></div>
			<div class="page-title">
				<h1>Contact</h1>
			</div>
		</div>
		<!-- /#header panel --> 
		
		<!-- #main -->
		<div id="main" class="wrapper">
			<div class="main-content container">
				<h2 class="main-title">Get in Touch</h2>
				<p class="sub-title">Our team is eager to provide the high-power knowledge that will be the cornerstone of your startup. If you have an interesting project for us, do get in touch on:</p>
				<div class="row">
					<div class="span3">
						<div class="circle-box contact-box">
							<h4>
								<span class="circle">
									<span class="circle-border"></span>
									<span class="circle-inner"><span class="circle-icon-skype"></span></span>
								</span>
								Skype</h4>
							<p><a href="#" class="link-style1">Call us</a></p>
						</div>
					</div>
					<div class="span3">
						<div class="circle-box">
							<h4>
								<span class="circle">
									<span class="circle-border"></span>
									<span class="circle-inner"><span class="circle-icon-tel"></span></span>
								</span>
								Phone</h4>
							<p>(811) 108-4000</p>
						</div>
					</div>
					<div class="span3">
						<div class="circle-box">
							<h4>
								<span class="circle">
									<span class="circle-border"></span>
									<span class="circle-inner"><span class="circle-icon-twitter"></span></span>
								</span>
								Twitter</h4>
							<p><a href="#" class="link-style1">Follow us</a></p>
						</div>
					</div>
					<div class="span3">
						<div class="circle-box">
							<h4>
								<span class="circle">
									<span class="circle-border"></span>
									<span class="circle-inner"><span class="circle-icon-mail"></span></span>
								</span>
								E-mail</h4>
							<p><a href="#" class="link-style1">office@imbus.com</a></p>
						</div>
					</div>
				</div>
				<h2 class="main-title">FEEL FREE SHOOT US A MAIL</h2>
				<p class="sub-title">Hinc disputationi ei nam, mei doctus tamquam suscipit te. Enim mediocritatem vel et, in qui falli minimum. Tale sanctus assentior sed ex. Gubergren euripidis vis, usu noluisse, dicta intellegam eu per.</p>
				<?php
				if (isset($_SESSION['reponse']) && $_SESSION['reponse']['result']=='success') : ?>
                <div class="alert alert-success1"><?php echo $_SESSION['reponse']['message']['mail_sent']; ?>
					<button type="button" class="close"><span class="alert-icon-close"></span></button>
				</div>
                <?php
					unset($_SESSION['reponse']);
					unset($_SESSION['postdata']);
				?>
                <?php elseif ($_SESSION['reponse']['result']=='error' && isset($_SESSION['reponse']['message']['mail_sent'])) : ?>
				<div class="alert alert-error"><?php echo $_SESSION['reponse']['message']['mail_sent']; ?>
					<button type="button" class="close"><span class="alert-icon-close"></span></button>
				</div>
                <?php
					unset($_SESSION['reponse']);
					unset($_SESSION['postdata']);
				?>				
				<?php elseif ($_SESSION['reponse']['result']=='error') : ?>
				<div class="alert alert-error">
				<?php foreach ($_SESSION['reponse']['message'] as $msg) { echo '<p>'.$msg.'</p>'; } ?>
					<button type="button" class="close"><span class="alert-icon-close"></span></button>
				</div>
				<?php endif; ?>
				<!-- #contact-form -->
				<form id="contact-form" name="contact-form" method="post" action="contact.php">
				<div class="row">
					<p class="span4"><input id="username" name="username" type="text" tabindex="1" value="<?php echo isset($_SESSION['postdata']['username']) ? $_SESSION['postdata']['username']  : '' ; ?>" placeholder="Name (required)"></p>
					<p class="span4"><input id="email" name="email" type="text" tabindex="2" value="<?php echo isset($_SESSION['postdata']['email']) ? $_SESSION['postdata']['email']  : '' ; ?>" placeholder="E-mail (required)"></p>
					<p class="span4"><input id="url" name="url" type="text" tabindex="3" value="<?php echo isset($_SESSION['postdata']['url']) ? $_SESSION['postdata']['url']  : '' ; ?>" placeholder="Website"></p>
				</div>
				<div class="row">
					<p class="span12"><textarea id="message" name="message" tabindex="4" rows="6" placeholder="Message (required)"><?php echo isset($_SESSION['postdata']['message']) ? $_SESSION['postdata']['message']  : '' ; ?></textarea></p>
				</div>
				<div class="row">
					<div class="span4 pull-right">
						<div class="captcha">
							<label for="captcha">Are you human? <?php echo isset($_SESSION['no1']) ? $_SESSION['no1'] : ''; ?>+<?php echo isset($_SESSION['no2']) ? $_SESSION['no2'] : ''; ?> = </label>
							<input id="captcha" name="captcha" type="text" tabindex="5" value="" autocomplete="off" />
						</div>
					</div>
					<div class="span3">
						<p><button id="contact-submit" type="submit" tabindex="6" class="btn-large btn-style1" data-send="Sending...">Submit</button></p>
					</div>
				</div>
				<div class="nspace-20"></div>
				</form>
				<!-- /#contact-form -->
							
			</div>
		</div>
		<!-- /#main -->
	
		<!-- #top-footer -->
		<div id="top-footer" class="wrapper">
			<div class="container">
				<div class="top-footer-list-wrapper"> <span class="ticker-footer-icon"></span>
					<div class="ticker top-footer-list">
						<ul class="ticker-list">
							<li>If you caught Apple’s iOS 7 presentation on Monday or have followed any of the numerous bits of coverage, then you know that the home screen icons have quickly become a point of contention. </li>
							<li>What information do you include on your business card?</li>
							<li>Ask DN: Visual designers looking for full time. <a href="#">Apply here.</a></li>	
							<li>Anyone knew, that Ubuntu 10.10 was also named Maverick?.</li>														
						</ul>
					</div>
					<ul class="ticker-controls top-footer-list-controls">
						<li><a href="#" class="follow">Sign-up to Newsletter</a></li>
						<li><a href="#" class="prev arrow"></a></li>
						<li><a href="#" class="next arrow"></a></li>
					</ul>
				</div>
			</div>
		</div>
		<!-- /#top-footer --> 
		
		<!-- #footer -->
		<div id="footer" class="wrapper">
			<div class="container">
				<div class="row">
					<div class="span4"> 

						<!-- footer wiget area 1 -->
						<div class="widget">
							<h4>
								<span class="circle">
									<span class="circle-border"></span>
									<span class="circle-inner"><span class="circle-icon-posts"></span></span>
								</span>
								Recent Posts</h4>
							<ul>
								<li><a href="#">Ad mea veniam sanctus </a></li>
								<li><a href="#">Ea viris nostro phaedrum </a></li>
								<li><a href="#">Et lucilius eleifend constuer</a></li>
								<li><a href="#">Ad mea veniam sanctus</a></li>
							</ul>
						</div>
						<!-- /footer wiget area 1 --> 
						
					</div>
					<div class="span4">
					
						<!-- footer wiget area 2 -->
						<div class="widget">
							<h4>
								<span class="circle">
									<span class="circle-border"></span>
									<span class="circle-inner"><span class="circle-icon-about"></span></span>
								</span>
								A Little About Me</h4>
							<p>Sumo ludus at eos, veri assum efficiant indee nec. No quo labores elaboraret disputando. Eam quas tritani his.</p>
							<form class="searchform" action="#" method="get">
								<p class="input-button-inside">
									<input name="s" type="text" placeholder="Search">
									<button type="submit" class="input-button-search"></button>
								</p>
							</form>
						</div>
						<!-- /footer wiget area 2 -->
						
					</div>
					<div class="span4">

						<!-- footer wiget area 3 -->
						<div class="widget">
							<h4>
								<span class="circle">
									<span class="circle-border"></span>
									<span class="circle-inner"><span class="circle-icon-about"></span></span>
								</span>
								Get In Touch</h4>
							<p>1419 Westwood Blvd, Los Angeles<br>
								CA 90024</p>
							<p>E-mail: <a href="#" class="link-style2">office@imbus.com</a><br>
								Tel: (811) 108-4000</p>
						</div>
						<!-- /footer wiget area 3 -->
						
					</div>
				</div>
			</div>
		</div>
		<!-- /#footer --> 
		
		<!-- #bottom -->
		<div id="bottom" class="wrapper">
			<div class="container">
				<div class="social-icons">
					<div class="social-icon-twitter"><a href="#"><img src="assets/images/social_icons/social_twitter.png" alt="twitter"></a></div>
					<div class="social-icon-facebook"><a href="#"><img src="assets/images/social_icons/social_facebook.png" alt="facebook"></a></div>
					<div class="social-icon-pinterest"><a href="#"><img src="assets/images/social_icons/social_pinterest.png" alt="pinterest"></a></div>
					<div class="social-icon-youtube"><a href="#"><img src="assets/images/social_icons/social_youtube.png" alt="youtube"></a></div>
					<div class="social-icon-vimeo"><a href="#"><img src="assets/images/social_icons/social_vimeo.png" alt="vimeo"></a></div>
					<div class="social-icon-flickr"><a href="#"><img src="assets/images/social_icons/social_flickr.png" alt="flickr"></a></div>
					<div class="social-icon-linkedin"><a href="#"><img src="assets/images/social_icons/social_linkedin.png" alt="linkedin"></a></div>
					<div class="social-icon-rss"><a href="#"><img src="assets/images/social_icons/social_rss.png" alt="rss"></a></div>
				</div>
				<div class="clearfix"></div>
				<p class="copyright">Copyright 2013 | imbus by <a href="#" class="link-style2">Granthweb</a></p>
			</div>
		</div>
		<!-- /#bottom -->
		
		<div id="scroll-top"><a href="#top"></a></div>
	</div>
	<!-- /#wrapper -->

	<!-- js body -->
	<script type="text/javascript" src="assets/plugins/jquery.inview.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery.carouFredSel-6.2.1-packed.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery.lavalamp-1.3.5.min.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery.easing.1.3.js"></script>
	<script type="text/javascript" src="assets/plugins/jquery.isotope.min.js"></script>
	<script type="text/javascript" src="assets/plugins/magnific-popup/jquery.magnific-popup.min.js"></script>
	<script type="text/javascript" src="http://maps.google.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript" src="assets/plugins/jquery.gomap-1.3.2.min.js"></script>

</body>
<!-- /body -->

</html>