		</div><!-- #content -->
		<div id="sidebar"><?php if( is_active_sidebar( 'sidebar-blog' ) ): 
		dynamic_sidebar( 'sidebar-blog' );
	endif;
	?></div><!-- #sidebar -->
		<br class="clear" />
	</div><!-- #container -->
	<div id="footer">
	&copy; <?php echo date( 'Y' ) ?> <a href="http://www.sevierheights.org">Sevier Heights Baptist Church</a>. All rights reserved. The Living Christmas Tree.
	</div>
<?php wp_footer(); // WordPress footer hook ?>

</body>
</html>