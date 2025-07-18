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
	<link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/assets/css/main.css" />
	<noscript><link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/assets/css/noscript.css" /></noscript>
	
	    <!-- favicons
    ================================================== -->
    <link rel="shortcut icon" href="favicon.ico" type="image/x-icon">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
	
	<!-- register WB modules
	================================================== -->
		<?php
		register_frontend_modfiles('css');
		register_frontend_modfiles('jquery');
		register_frontend_modfiles('js');
		?>
		
<?php 
$font[] = 'https://fonts.googleapis.com/css?family=Lato:300,400,900&display=swap';
$font_format = 'woff'; // optional values 'woff' / 'woff2' / 'woff2-unicode'
include 'google_fonts.php';
?>
		
	</head>
	<body class="index is-preload">
		<div id="page-wrapper">

			<!-- Header -->
			<?php if (PAGE_ID == 8) { ?>
				<header id="header" class="alt">
			<?php
			}else{ ?>
				<header id="header">
			<?php
				}
			?>
					<h1 id="logo"><a href="index.html">Nigra<span>blanka</span></a></h1>
					<nav id="nav">
				<?php
				$open = '<li class="[if(class=menu-current||class=menu-parent){active}]">
			[if(class==menu-expand){<a href="[url]" class="submenu">[menu_title] <i class="fas fa-caret-down"></i></a>
				}
			else {<a href="[url]">[menu_title]</a>}]';
				show_menu2(
					$aMenu          = 1,
					$aStart         = SM2_ROOT,
					$aMaxLevel      = SM2_ALL,						
					$aOptions       = SM2_ALL,
					$aItemOpen      = $open,
					$aItemClose     = '</li>',
					$aMenuOpen      = '<ul>',
					$aMenuClose     = '</ul>',
					$aTopItemOpen   = false,
					$aTopMenuOpen   = false
				);
			?>
					
					</nav>
				</header>

			<!-- Banner only for homepage -->
			<?php if (PAGE_ID == 8 || PAGE_ID== 12 || PAGE_ID== 10) { ?>
				<section id="banner">

					<!--
						".inner" is set up as an inline-block so it automatically expands
						in both directions to fit whatever's inside it. This means it won't
						automatically wrap lines, so be sure to use line breaks where
						appropriate (<br />).
					-->
					<div class="inner">

						<header>
							<h2>Nigrablanka</h2>
						</header>
						<p>This is <strong>Nigrablanka</strong>, a free
						<br />
						responsive template
						<br />
						by <a href="http://jbd.epizy.com/">JBD</a>.</p>
						<footer>
							<ul class="buttons stacked">
								<li><a href="#main" class="button fit scrolly">Tell Me More</a></li>
							</ul>
						</footer>

					</div>

				</section>
				<?php
					}
				?>
			<!-- Main -->
				<article id="main">

					<header class="special container">
						<span class="icon solid fa-chart-bar"></span>
						<h2><?php echo MENU_TITLE ?></h2>
						<h3><?php echo PAGE_DESCRIPTION ?></h3>
					</header>

					<!-- One -->
						<section class="wrapper style4 container">
							<!-- Content -->
								<div class="content">
									<section>
										<?php if ($fulltop) { ?>
											<div class="row">
												<div class="col-12">
													<div class="box">
														<?php echo $fulltop ?>
													</div>
												</div>
											</div>
				<?php } ?>
					<div class="row">
						<?php if ($content && $right && $left) { ?>
							<div class="col-4 col-12-narrower"><div class="box"><?php echo $left ?></div></div>
							<div class="col-4 col-12-narrower"><div class="box"><?php echo $content ?></div></div>
							<div class="col-4 col-12-narrower"><div class="box"><?php echo $right ?></div></div>
						<?php } elseif ($right) { ?>
							<div class="col-8 col-12-narrower"><div class="box"><?php echo $content ?></div></div>
							<div class="col-4 col-12-narrower"><div class="box"><?php echo $right ?></div></div>
						<?php } elseif ($left) { ?>
							<div class="col-4 col-12-narrower"><div class="box"><?php echo $left ?></div></div>
							<div class="col-8 col-12-narrower"><div class="box"><?php echo $content ?></div></div>
						<?php } else { ?>
							<div class="col-12"><div class="box"><?php echo $content ?></div></div>
						<?php } ?>
					</div>
				<?php if ($fullbottom) { ?>
					<div class="row">
						<div class="col-12">
							<div class="box">
								<?php echo $fullbottom ?>
							</div>
						</div>
					</div>
				<?php } ?>
								</div>

						</section>
				</article>
			<!-- CTA -->
				<section id="cta">

					<header>
						<h2>Ready to do <strong>something</strong>?</h2>
						<p>Proin a ullamcorper elit, et sagittis turpis integer ut fermentum.</p>
					</header>
					<footer>
						<ul class="buttons">
							<li><a href="#" class="button primary">Take My Money</a></li>
							<li><a href="#" class="button">Subscribe to Newsletter</a></li>
						</ul>
					</footer>

				</section>

			<!-- Footer -->
				<footer id="footer">

					<ul class="icons">
						<li><a href="#" class="icon brands circle fa-twitter"><span class="label">Twitter</span></a></li>
						<li><a href="#" class="icon brands circle fa-facebook-f"><span class="label">Facebook</span></a></li>
						<li><a href="#" class="icon brands circle fa-google-plus-g"><span class="label">Google+</span></a></li>
						<li><a href="#" class="icon brands circle fa-github"><span class="label">Github</span></a></li>
						<li><a href="#" class="icon brands circle fa-dribbble"><span class="label">Dribbble</span></a></li>
					</ul>

					<ul class="copyright">
						<li>&copy; <?php echo date("Y");?> Nigroblanka</li><li>Design: <a href="http://">JBD</a></li>
					</ul>

				</footer>

		</div>

		<!-- Scripts -->
			<!--script src="<?php echo TEMPLATE_DIR;?>/assets/js/jquery.min.js"></script-->
			<script src="<?php echo TEMPLATE_DIR;?>/assets/js/jquery.dropotron.min.js"></script>
			<script src="<?php echo TEMPLATE_DIR;?>/assets/js/jquery.scrolly.min.js"></script>
			<script src="<?php echo TEMPLATE_DIR;?>/assets/js/jquery.scrollex.min.js"></script>
			<script src="<?php echo TEMPLATE_DIR;?>/assets/js/browser.min.js"></script>
			<script src="<?php echo TEMPLATE_DIR;?>/assets/js/breakpoints.min.js"></script>
			<script src="<?php echo TEMPLATE_DIR;?>/assets/js/util.js"></script>
			<script src="<?php echo TEMPLATE_DIR;?>/assets/js/main.js"></script>

	</body>
</html>