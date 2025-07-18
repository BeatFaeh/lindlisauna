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

    <!-- CSS
    ================================================== -->
    <link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/css/base.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/css/vendor.css">
    <link rel="stylesheet" href="<?php echo TEMPLATE_DIR;?>/css/main.css">

    <!-- script
    ================================================== -->
    <script src="<?php echo TEMPLATE_DIR;?>/js/modernizr.js"></script>
    <script src="<?php echo TEMPLATE_DIR;?>/js/pace.min.js"></script>

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
</head>

<body id="top">

    <!-- header
    ================================================== -->
    <header class="s-header">

        <div class="header-logo">
            <a class="site-logo" href="index.html">
                <!--img src="images/logo.svg" alt="Homepage">-->
				<h1 style="color:#fff;">Fotilo</h1>
            </a>
        </div> <!-- end header-logo -->

        <nav class="header-nav">

            <a href="#0" class="header-nav__close" title="close"><span>Close</span></a>

            <div class="header-nav__content">
                <h3>fotilo</h3>
                	<?php
				$open = '<li class="[if(class=menu-current||class=menu-parent){current}]"><a href="[url]">[menu_title]</a>';
				show_menu2(
					$aMenu          = 1,
					$aStart         = SM2_ROOT,
					$aMaxLevel      = SM2_ALL,						
					$aOptions       = SM2_ALL,
					$aItemOpen      = $open,
					$aItemClose     = '</li>',
					$aMenuOpen      = '<ul [if(level==0){class="header-nav__list"}]>',
					$aMenuClose     = '</ul>',
					$aTopItemOpen   = false,
					$aTopMenuOpen   = false
				);
			?>		
                <p></p>
    
                <ul class="header-nav__social">
                    <li>
                        <a href="#0"><i class="fab fa-facebook"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-twitter"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-instagram"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-behance"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-dribbble"></i></a>
                    </li>
                </ul>

            </div> <!-- end header-nav__content -->

        </nav> <!-- end header-nav -->

        <a class="header-menu-toggle" href="#0">
            <span class="header-menu-icon"></span>
        </a>

    </header> <!-- end s-header -->


    <!-- home
    ================================================== -->
	<?php if (PAGE_ID == 4 || PAGE_ID== 12 || PAGE_ID== 10) { ?>
    <section id="home" class="s-home target-section" data-parallax="scroll" data-image-src="<?php echo TEMPLATE_DIR;?>/images/hero-bg.jpg" data-natural-width=3000 data-natural-height=2000 data-position-y=top>

        <!--div class="shadow-overlay"></div>-->

        <div class="home-content">

            <div class="row home-content__main">
                <h1>
                Hi there,  <br>
                I am Fotilo.
                </h1>

                <p>
                We create stunning digital experiences <br>
                that will help your business stand out.
                </p>
		<?php $iMultiLang = 0; if (function_exists('language_menu')) { $sMultiLang = language_menu('png'); $iMultiLang = (int)((!empty($sMultiLang))?1:0);} ?>
            </div> <!-- end home-content__main -->

        </div> <!-- end home-content -->
		<!-- Hero menu -->
		           	<?php  
					show_menu2(
						$aMenu          = 2,
						$aStart         = SM2_ROOT,
						$aMaxLevel      = SM2_ALL,
						$aOptions       = SM2_ALLINFO|SM2_ALL,
						$aItemOpen      = '<li><a href="[url]">[menu_title]<span> - [description]</span></a>',
						$aItemClose     = '</li>',
						$aMenuOpen      = '<ul class="home-sidelinks">',
						$aMenuClose     = '</ul>',
						$aTopItemOpen   = false,
						$aTopMenuOpen   = false
					);
				?>
		<!-- end home-hero menu -->

        <ul class="home-social">
            <li class="home-social-title">Follow Us</li>
            <li><a href="#0">
                <i class="fab fa-facebook"></i>
                <span class="home-social-text">Facebook</span>
            </a></li>
            <li><a href="#0">
                <i class="fab fa-twitter"></i>
                <span class="home-social-text">Twitter</span>
            </a></li>
            <li><a href="#0">
                <i class="fab fa-linkedin"></i>
                <span class="home-social-text">LinkedIn</span>
            </a></li>
        </ul> <!-- end home-social -->

        <a href="#about" class="home-scroll smoothscroll">
            <span class="home-scroll__text">Scroll Down</span>
            <span class="home-scroll__icon"></span>
        </a> <!-- end home-scroll -->
		<?php
			}else{ ?>
			<img src="<?php echo TEMPLATE_DIR;?>/images/hero2.jpg" width="100%">
		<?php
			}
		?>
    </section> <!-- end s-home -->


    <!-- about
    ================================================== -->
    <section id="about" class="s-about">

        <div class="row section-header" data-aos="fade-up">
		<h3 class="subhead"><?php echo PAGE_TITLE ; ?></h3>
<?php echo PAGE_DESCRIPTION; ?>
		<?php if ($fulltop) { ?>
					<div class="row">
						<div class="col-full">
							<div class="box">
								<?php echo $fulltop ?>
							</div>
						</div>
					</div>
				<?php } ?>
					<div class="row">
						<?php if ($content && $right && $left) { ?>
							<div class="col-four"><div class="box"><?php echo $left ?></div></div>
							<div class="col-four"><div class="box"><?php echo $content ?></div></div>
							<div class="col-four"><div class="box"><?php echo $right ?></div></div>
						<?php } elseif ($right) { ?>
							<div class="col-eight"><div class="box"><?php echo $content ?></div></div>
							<div class="col-four"><div class="box"><?php echo $right ?></div></div>
						<?php } elseif ($left) { ?>
							<div class="col-four"><div class="box"><?php echo $left ?></div></div>
							<div class="col-eight"><div class="box"><?php echo $content ?></div></div>
						<?php } else { ?>
							<div class="col-full"><div class="box"><?php echo $content ?></div></div>
						<?php } ?>
					</div>
				<?php if ($fullbottom) { ?>
					<div class="row">
						<div class="col-full">
							<div class="box">
								<?php echo $fullbottom ?>
							</div>
						</div>
					</div>
				<?php } ?>
				
				
		
		
            <!--div class="col-full">
                <h3 class="subhead">Who We Are</h3>
                <h1>We are a group of design driven individuals passionate about creating beautiful UI designs.</h1>
				<p>Tekst tekst </p>
				<?php echo $fulltop ?>
            </div-->
        </div> <!-- end section-header -->      

    </section> <!-- end s-about -->
  
    <!-- contact
    ================================================== -->
    <section id="contact" class="s-contact">

        <div class="row section-header" data-aos="fade-up">
            <div class="col-full">
                <h3 class="subhead subhead--light">Contact Us</h3>
            </div>
        </div> <!-- end section-header -->

        <div class="row">

            <div class="col-full contact-main" data-aos="fade-up">
                <p>
                <a href="mailto:#0" class="contact-email">hello@fotilo.com</a>
                <span class="contact-number">+1 (917) 123 456  /  +1 (917) 333 987</span>
                </p>
            </div> <!-- end contact-main -->

        </div> <!-- end row -->

        <div class="row">

            <div class="col-four tab-full contact-secondary" data-aos="fade-up">
                <h3 class="subhead subhead--light">Where To Find Us</h3>

                <p class="contact-address">
                    1600 Amphitheatre Parkway<br>
                    Mountain View, CA<br>
                    94043 US
                </p>
            </div> <!-- end contact-secondary -->

            <div class="col-four tab-full contact-secondary" data-aos="fade-up">
                <h3 class="subhead subhead--light">Follow Us</h3>

                <ul class="contact-social">
                    <li>
                        <a href="#0"><i class="fab fa-facebook"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-twitter"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-instagram"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-behance"></i></a>
                    </li>
                    <li>
                        <a href="#0"><i class="fab fa-dribbble"></i></a>
                    </li>
                </ul> <!-- end contact-social -->
			</div>
			<div class="col-four tab-full contact-secondary" data-aos="fade-up">
			 <h3 class="subhead subhead--light">Legal</h3>
			 		<!-- Legal menu -->
		           	<?php
					show_menu2(
						$aMenu          = 3,
						$aStart         = SM2_ROOT,
						$aMaxLevel      = SM2_CURR,
						$aOptions       = SM2_ALL,
						$aItemOpen      = '<li><a href="[url]">[menu_title]</a>',
						$aItemClose     = '</li>',
						$aMenuOpen      = '<ul>',
						$aMenuClose     = '</ul>',
						$aTopItemOpen   = false,
						$aTopMenuOpen   = false
					);
					?>	
			</div>
            </div> <!-- end contact-secondary -->
 
        </div> <!-- end row -->

        <div class="row">
            <div class="col-full cl-copyright">
                <span>
				<p>Copyright <?php echo date("Y");?>  <a href="#">Folilo</a>. All Rights Reserved. <br>Made with <i class="far fa-heart"></i> by <a href="http://jbd.epizy.com/">JBD</a> / Demo Images: <a href="https://pixabay.com" target="_blank">Pixabay</a></p>
				</span> 
            </div>
        </div>

        <div class="cl-go-top">
            <a class="smoothscroll" title="Back to Top" href="#top"><i class="icon-arrow-up" aria-hidden="true"></i></a>
        </div>

    </section> <!-- end s-contact -->

    <!-- preloader
    ================================================== -->
    <div id="preloader">
        <div id="loader">            
        </div>
    </div>


    <!-- Java Script
    ================================================== -->
    <script src="<?php echo TEMPLATE_DIR;?>/js/plugins.js"></script>
    <script src="<?php echo TEMPLATE_DIR;?>/js/main.js"></script>

</body>

</html>