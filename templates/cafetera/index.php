<?php
//  prevent direct access to this file  
if (!defined('WB_URL')) {
	header('Location: ../index.php');
	exit(0);
}
// get the grid 
if (!defined('WB_PATH')) die(header('Location: ../../index.php'));

ob_start(); page_content(1); $content=ob_get_contents(); ob_end_clean(); // Main
ob_start(); page_content(2); $right=ob_get_contents(); ob_end_clean(); // Right from main
ob_start(); page_content(3); $left=ob_get_contents(); ob_end_clean(); // Left from main
ob_start(); page_content(4); $fulltop=ob_get_contents(); ob_end_clean(); // Full top of the page
ob_start(); page_content(5); $fullbottom=ob_get_contents(); ob_end_clean(); // Full bottom of the page

?>
<!DOCTYPE HTML>
<!-- Put here the main language of your site  -->
<html lang="<?php echo LANGUAGE; ?>">
<head>
	
	<?php
	// ##### If module wbstats is active do this ##### 
	 if (file_exists(WB_PATH.'/modules/wbstats/count.php')) { 
		include (WB_PATH.'/modules/wbstats/count.php'); 
	} else {
			echo "<!-- wbstats not found -->";
	}
	// ##### If module simplehead is active do this ##### 
	if(function_exists('simplepagehead')) {
		simplepagehead('/', 1, 0, 0); 
	} else { ?>
		<title><?php page_title(' - ','[PAGE_TITLE][SPACER][WEBSITE_TITLE]'); ?></title>
		<meta http-equiv="Content-Type" content="text/html; charset=<?php if(defined('DEFAULT_CHARSET')) { echo DEFAULT_CHARSET; } else { echo 'utf-8'; }?>" />
		<meta name="description" content="<?php page_description(); ?>" />
		<meta name="keywords" content="<?php page_keywords(); ?>" />
	<?php  } ?>
	<meta name="author" content="John Broeckaert - JBD" />
	<meta name="viewport" content="width=device-width,initial-scale=1.0" />
	<!-- Place favicon.ico and apple-touch-icon.png in the root directory -->
	<link rel="shortcut icon" href="favicon.ico">

	<!-- <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,700,300' rel='stylesheet' type='text/css'> -->
	
	<!-- Animate.css -->
	<link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/css/animate.css">
	<!-- Icomoon Icon Fonts--
	<link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/css/icomoon.css"> -->
	<link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/css/font-awesome.min.css">
	<!-- Bootstrap  -->
	<link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/css/bootstrap.css">
	<!-- Superfish -->
	<link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/css/superfish.css">
	<!-- Magnific Popup -->
	<link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/css/magnific-popup.css">
	
	<link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/css/style.css">


	<?php
    if (!function_exists('LangPageId')) {function LangPageId(){return $iPageId;}}
    // to show flags in frontend
	$iMultiLang = 0;$sMultiLang = ''; if (function_exists('language_menu')){$sMultiLang = language_menu('png',false); $iMultiLang = intval(!empty($sMultiLang) ? 1 : 0);}
	if (function_exists('LangPageId')&&$iMultiLang) {
    ?>
    <!--
        <link rel="alternate" hreflang="x-default" href="[wblink<?php echo LangPageId($sPageLang);?>]" />
        <link rel="alternate" hreflang="de" href="[wblink<?php echo LangPageId('DE');?>]" />
        <link rel="alternate" hreflang="en" href="[wblink<?php echo LangPageId('EN');?>]" />
    -->
    <?php } ?>
	<?php
	register_frontend_modfiles('css');
	register_frontend_modfiles('jquery');
	register_frontend_modfiles('js');
	?>
</head>
<body>
		<div id="wrapper">
		<div id="page">
		<!-- ############################################  -->
		<!-- if you use mulitilang give here the page-id from the first lang pages otherwise delete next line -->
		<!-- ############################################  -->
		<?php if (PAGE_ID == 1 || PAGE_ID== 12 || PAGE_ID== 10) { ?>
		<div class="hero">
			<div class="overlay"></div>
			<div class="cover text-center" data-stellar-background-ratio="0.5" style="background-image: url(<?php echo TEMPLATE_DIR;?>/images/hero.jpg);">
				<div class="desc">
					<div class="container">
						<div class="col-md-10 col-md-offset-1">
							<a href="index.html" id="main-logo">Lindli Sauna</a>
							<div class="animate-box">
							<!-- SEE manual index.html for details -->
								<h2>Wärme - Wasser - Wohlgefühl<br />dein Ruhepol am Fluss</h2>
								
								<p><a class="btn btn-primary btn-lg" href="#feature-product">Get Started</a></p>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
		<?php
			}else{ ?>
			<img src="<?php echo TEMPLATE_DIR;?>/images/hero2.jpg" width="100%">
		<?php
			}
		?>
		<header id="header-section" class="sticky-banner">
			<div class="container">
				<div class="nav-header">
					<a href="#" class="js-nav-toggle nav-toggle dark"><i></i></a>
					<h1 id="logo"><a href="<?php echo WB_URL; ?>">Home</a></h1>
					<!-- START #menu-wrap -->
					<nav id="menu-wrap" role="navigation">
					<div class="flag" style="text-align:right"><!--<?php $iMultiLang = 0; if (function_exists('language_menu')) { $sMultiLang = language_menu('png'); $iMultiLang = (int)((!empty($sMultiLang))?1:0);} ?>-->
					<?php if (trim($sMultiLang)!=''){ echo $sMultiLang; } ?>
					</div>					
		<?php
			$open = '<li class="[if(class=menu-current||class=menu-parent){active}]">
			[if(class==menu-expand){<a href="[url]" class="sub-ddown">[menu_title] <i class="fa fa-caret-down"></i></a>
				}
			else {<a href="[url]">[menu_title]</a>}]';
			show_menu2(
				$aMenu          = 1,
				$aStart         = SM2_ROOT+$iMultiLang,
				$aMaxLevel      = SM2_ALL,
				$aOptions       = SM2_ALL,
				$aItemOpen      = $open,
				$aItemClose     = '</li>',
				$aMenuOpen      = '<ul [if(level==0){class="sf-menu" id="primary-menu"} else {class="sub-menu"}]>',
				$aMenuClose     = '</ul>',
				$aTopItemOpen   = false,
				$aTopMenuOpen   = false
			);
		?>
			<!--  IMPORTANT If you use mulitilang set $aMenuOpen (level==1)  -->		
					</nav>
					
				</div>
			</div>
		</header>

		<!-- end:header-top -->


		<div id="feature-product" class="section-gray">
			<div class="container">
				<?php if ($fulltop) { ?>
					<div class="row">
						<div class="col-md-12 animate-box">
							<div class="box">
								<?php echo $fulltop ?>
							</div>
						</div>
					</div>
				<?php } ?>
					<div class="row">
						<?php if ($content && $right && $left) { ?>
							<div class="col-xs-12 col-sm-4 col-lg-4"><div class="box"><?php echo $left ?></div></div>
							<div class="col-xs-12 col-sm-4 col-lg-4"><div class="box"><?php echo $content ?></div></div>
							<div class="col-xs-12 col-sm-4 col-lg-4"><div class="box"><?php echo $right ?></div></div>
						<?php } elseif ($right) { ?>
							<div class="col-xs-12 col-sm-8 col-lg-8"><div class="box"><?php echo $content ?></div></div>
							<div class="col-xs-12 col-sm-4 col-lg-4"><div class="box"><?php echo $right ?></div></div>
						<?php } elseif ($left) { ?>
							<div class="col-xs-12 col-sm-4 col-lg-4"><div class="box"><?php echo $left ?></div></div>
							<div class="col-xs-12 col-sm-8 col-lg-8"><div class="box"><?php echo $content ?></div></div>
						<?php } else { ?>
							<div class="col-xs-12 col-sm-12 col-lg-12"><div class="box"><?php echo $content ?></div></div>
						<?php } ?>
					</div>
				<?php if ($fullbottom) { ?>
					<div class="row">
						<div class="col-xs-12 col-sm-12 col-lg-12">
							<div class="box">
								<?php echo $fullbottom ?>
							</div>
						</div>
					</div>
				<?php } ?>
			</div>	
		</div>

 <button onclick="topFunction()" id="myBtn" title="Go to top">Top</button> 
		
		<footer>
			<div id="footer">
				<div class="container">
					<div class="row row-bottom-padded-md">
						<div class="col-md-3 col-sm-3 col-xs-12 footer-link">
							<h2><i class="fa fa-address-card"></i> Lindli Sauna</h2>			
							<p>Rheinhaldenstrasse 16</p>
							<p>8200 Schaffhausen</p>
							<!-- <p>Phone</p> -->
							<p>info@lindlisauna.ch</p>
                            <br>
                            <img src="<?php echo WB_URL;?>/pages/lindlisauna_logo.png" width="50%">
						</div>
						<div class="col-md-6 col-sm-6 col-xs-12 footer-link">
							<h2><i class="fa fa-map-marker"></i> Map</h2>
							
							<iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d1327.3406763529197!2d8.649086993534514!3d47.6943431196009!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x479a81e773dc530d%3A0xa2585ffa283bbf1b!2sRheinhaldenstrasse%2016%2C%208200%20Schaffhausen!5e1!3m2!1sde!2sch!4v1752748353201!5m2!1sde!2sch" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
							</iframe>							
						</div>

						<div class="row">
						<div class="col-md-6 col-md-offset-3 text-center">
							<p class="social-icons">
								<!-- <a href="#"><i class="fa fa-twitter"></i></a> -->
								<a href="#"><i class="fa fa-facebook-f"></i></a>
								<a href="#"><i class="fa fa-instagram"></i></a>
								<!-- <a href="#"><i class="fa fa-dribbble"></i></a> -->
								<!-- <a href="#"><i class="fa fa-youtube"></i></a> -->
							</p>
							<p>
								<?php echo date("Y");?> 
								Lindli Sauna
								<br>
								powerd by <a href="https://rhysauna.ch" target="_blank">RhySauna
                                <br>
                                    <br>
                                    <img src="<?php echo WB_URL;?>/pages/rhysauna_logo.png" width="25%">
                                </a>
							</p>
						</div>
					</div>
				</div>
			</div>
		</footer>
	</div>
	<!-- END page -->

	</div>
	<!-- END wrapper -->

	<!-- Modernizr JS -->
	<script src="<?php echo TEMPLATE_DIR;?>/js/modernizr-2.6.2.min.js"></script>
	<!-- jQuery Easing -->
	<script src="<?php echo TEMPLATE_DIR;?>/js/jquery.easing.1.3.js"></script>
	<!-- Bootstrap -->
	<script src="<?php echo TEMPLATE_DIR;?>/js/bootstrap.min.js"></script>
	<!-- Waypoints -->
	<script src="<?php echo TEMPLATE_DIR;?>/js/jquery.waypoints.min.js"></script>
	<script src="<?php echo TEMPLATE_DIR;?>/js/sticky.js"></script>

	<!-- Stellar -->
	<script src="<?php echo TEMPLATE_DIR;?>/js/jquery.stellar.min.js"></script>
	<!-- Superfish -->
	<script src="<?php echo TEMPLATE_DIR;?>/js/hoverIntent.js"></script>
	<script src="<?php echo TEMPLATE_DIR;?>/js/superfish.js"></script>
	<!-- Main JS -->
	<script src="<?php echo TEMPLATE_DIR;?>/js/main.js"></script>
	<!--  back to top -->
<script>
window.onscroll = function() {scrollFunction()};

function scrollFunction() {
  if (document.body.scrollTop > 280 || document.documentElement.scrollTop > 280) {
    document.getElementById("myBtn").style.display = "block";
  } else {
    document.getElementById("myBtn").style.display = "none";
  }
}

// When the user clicks on the button, scroll to the top of the document
function topFunction() {
  document.body.scrollTop = 0; // For Safari
  document.documentElement.scrollTop = 0; // For Chrome, Firefox, IE and Opera
} 
</script>

	</body>
</html>

