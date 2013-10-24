<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" <?php language_attributes( 'xhtml' ); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<title><?php hybrid_document_title(); ?></title>
<link rel="stylesheet" href="<?php echo get_stylesheet_uri(); ?>" type="text/css" media="screen" />

<?php wp_head(); // WP head hook ?>

</head>

<body class="<?php hybrid_body_class(); ?>">
	<div id="container">
		<div id="header">
			<h1><a href="<?php bloginfo( 'wpurl' ) ?>"><?php bloginfo( 'title' ) ?></a></h1>
		</div><!-- #header -->
		<div id="header-images">
			<div id="images-frame"></div>
		</div><!-- #header-images -->
		<ul id="nav"><?php
		$main_menu = array( 'About', 'Get Involved', 'Tickets', 'Gallery', 'Store', 'Contact' );
		foreach( $main_menu as $post_title ){
			$ids[$post_title] = $wpdb->get_var( 'SELECT ID FROM '.$wpdb->posts.' WHERE post_title="'.$post_title.'" AND post_type="page" AND post_status="publish"' );
		}
		foreach( $ids as $post_title => $ID ){
			echo '<li';
			if( $post_title == $post->post_title ) echo ' class="current_page"';
			echo '><a href="'.get_permalink( $ID ).'" id="page-'.str_replace( ' ', '-', strtolower( $post_title ) ).'">'.$post_title.'</a></li>';
		}
		?></ul><!-- #nav -->
		<div id="content">