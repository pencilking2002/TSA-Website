			<footer id="colophon" role="contentinfo" class="b_body_f clearfix"><!--Footer starts here-->
				<div class="b_f_c" style="background-color: <?php echo _core::method("_settings","get","settings","style","general","footer_bg_color");?>;">
					<div class="footer-area">
                        <?php
                            ob_start();
                            ob_clean();
                            get_sidebar( 'footer-first' );
                            $f1 = ob_get_clean();
                            /*ob_start();
                            ob_clean();
                            get_sidebar( 'footer-second' );
                            $f2 = ob_get_clean();
                            ob_start();
                            ob_clean();
                            get_sidebar( 'footer-third' );
                            $f3 = ob_get_clean();*/
                            
                            //if( strlen( $f1 . $f2 . $f3 ) ){
							if( strlen( $f1) ){
                        ?>
                                <div class="b_page clearfix ">
                                    <?php echo $f1; ?>
                                </div>
                        <?php
                            }
                        ?>
						<div class="b_page clearfix ">
							<div class="b w_450">
								<p class="copyright"><?php echo str_replace('%year%',date('Y'),_core::method("_settings","get","settings","general","theme","copyright"));?></p>
							</div>	
							<div class="b w_450">
								<?php $limit=_core::method( '_settings' , 'get' , 'settings' , 'menus' , 'menus' , 'footer-menu-limit');?>
                                <?php echo menu( 'footer' , array( 'class' => 'footer-menu' , 'current-class' => 'active' , 'number-items' => $limit , 'more-label' => '' ) ) ?>
							</div>
						</div>
					</div>
				</div>
			</footer>

			<?php wp_footer(); 
				echo _core::method("_settings","get","settings","general","theme","code");
			?>

			<div id="toTop">
				<div class="inner">
					<p><span>Back</span><span>to top</span></p>
				</div>
			</div><!--Back to top-->

		</div>
	</div>
</body>
</html>