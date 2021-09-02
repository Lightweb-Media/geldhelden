<!doctype html>
<html <?php language_attributes(); ?> class="no-js">
	<head>
		<meta charset="<?php bloginfo('charset'); ?>">
		<title><?php wp_title(''); ?><?php if(wp_title('', false)) { echo ' :'; } ?> <?php bloginfo('name'); ?></title>
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/favicon.ico" rel="shortcut icon">
        <link href="<?php echo get_template_directory_uri(); ?>/img/icons/touch.png" rel="apple-touch-icon-precomposed">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="<?php bloginfo('description'); ?>">

		<?php wp_head(); ?>

	</head>
	<body <?php body_class(); ?>>

		<div id="mobile-back-overlayer"></div>

		<header class="header clear" role="banner">

			<div class="centered-header">
				<a class="mobile-menu-btn icon-menu-show-24" onclick="OpenMobileMenu();return false"></a>

				<div id="header-search">
					<?php get_template_part('searchform'); ?>
				</div>

				<div class="right-content">
					<a class="header-icon icon-search-24" href="#"></a>
					<a class="header-icon icon-add-boxed-fill-24" href="#"></a>
					<a class="header-icon icon-chat-fill-24" href="#"></a>
					<a class="header-icon icon-bell-straight-fill-24" href="#"><span class="notification-count">2</span></a> 
					<a class="header-icon icon-avatar-40" href="#"></a> 
				</div>
			</div>

		</header>

		<div id="sidebar-left">

			<div class="sidebar-left-header">
				<div class="left-content">
					<div class="youtube-logo">
						<a href="#">
							<img src="https://media1-production-mightynetworks.imgix.net/asset/25081701/yio6XoAyT.png?ixlib=rails-0.3.0&auto=format&w=148&h=92&fit=clip&impolicy=ResizeCrop&aspect=fit" alt="YouTube">
						</a>
					</div>
					<div class="logo">
						<a href="<?php echo home_url(); ?>">
							<img src="https://media1-production-mightynetworks.imgix.net/asset/14150824/geldhelden-favicon-2.png?ixlib=rails-0.3.0&auto=format&w=84&h=84&fit=crop&crop=faces" alt="Logo" class="logo-img">
						</a>
					</div>
					<a id="mobile-menu-btn" class="inner-mobile-menu-btn icon-menu-show-24" onclick="OpenMobileMenu();return false"></a>
				</div>
				<a class="tablet-mid-hide icon-arrow-tip-forward-10 icon-scale-13 toggle-collapse-arrow-button" href="#"></a>
			</div>

			<div class="sidebar-left-nav-wrapper">

				<nav class="sidebar-nav main-nav" role="navigation">
					<?php 
						// Hauptmenü
						wp_nav_menu( array( 
							'theme_location' => 'sidebar-menu', 
							'container_class' => 'hauptmenue' ) 
						); 
					?>
				</nav>

				<hr />

				<nav class="sidebar-nav second-nav" role="navigation">
					<?php 
						// Hauptmenü
						wp_nav_menu( array( 
							'theme_location' => 'extra-menu', 
							'container_class' => 'extramenue' ) 
						); 
					?>
				</nav>

				<hr />

				<div class="sidebar-chat">

					<div class="sidebar-chat-title"><? _e('Chat'); ?></div>
					<ul class="chat-menu">
						<li class="has-right-content"><a class="chat-room" href="#"><img class="chat-icon" src="https://media1-production-mightynetworks.imgix.net/asset/14150824/geldhelden-favicon-2.png?ixlib=rails-0.3.0&auto=format&w=68&h=68&fit=crop&impolicy=Avatar&crop=faces"> Geldhelden Community</a></li>
					</ul>

					<div class="sidebar-chat-title sidebar-chat-online"><? _e('Online now'); ?><span class="online-now"></span></div>
					<ul class="chat-users">
						<li><img src="https://assets1-production-mightynetworks.imgix.net/assets/default_user_avatars/default_user_avatar_11-3517b7fbb8075a1fb66d302ee19426062a5e1d51ebf43f1804d074bcb184d81d.jpg?auto=format&w=52&h=52&fit=crop&crop=faces"></li>
						<li><img src="https://media1-production-mightynetworks.imgix.net/asset/19530540/20210123_155206.jpg?ixlib=rails-0.3.0&fm=jpg&q=100&auto=format&w=52&h=52&fit=crop&crop=faces&impolicy=Avatar"></li>
						<li><img src="https://media1-production-mightynetworks.imgix.net/asset/16353237/profilepic.jpeg?ixlib=rails-0.3.0&fm=jpg&q=100&auto=format&w=52&h=52&fit=crop&crop=faces&impolicy=Avatar"></li>
						<li><img src="https://assets1-production-mightynetworks.imgix.net/assets/default_user_avatars/default_user_avatar_20-33c4232ce3137c4e1e8b6fe59edb6e5fa5c015ec557db387dfd4fc5a6b326aa9.jpg?auto=format&w=52&h=52&fit=crop&crop=faces"></li>
						<li><img src="https://assets1-production-mightynetworks.imgix.net/assets/default_user_avatars/default_user_avatar_2-7d1bb03671d911a4271fb8334ab3117c7e0559ff15d73b76bd9f2294b8583b72.jpg?auto=format&w=52&h=52&fit=crop&crop=faces"></li>
						<li><img src="https://assets1-production-mightynetworks.imgix.net/assets/default_user_avatars/default_user_avatar_19-5fa9055279fca7084b38ed292fe6c7edfaad976cd01d61f815aa326a660abb69.jpg?auto=format&w=52&h=52&fit=crop&crop=faces"></li>
						<li><img src="https://assets1-production-mightynetworks.imgix.net/assets/default_user_avatars/default_user_avatar_17-ec9ff53a1fe4d0c8c6ccf86da43544878d64ecc7d24e1e7abdf34d7eff947d41.jpg?auto=format&w=52&h=52&fit=crop&crop=faces"></li>
					</ul>

				</div>

			</div>

		</div>
		
		<div id="sidebar-right">
			<?php dynamic_sidebar( 'widget-area-1' ); ?>
		</div>