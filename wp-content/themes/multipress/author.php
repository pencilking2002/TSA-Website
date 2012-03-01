<?php get_header(); ?>
<div class="b_content clearfix" id="main">
    <div class="b_page clearfix">
        
        <?php /* antet title */ ?>
        <div class="content-title">
            <div class="title">
			   <?php if( have_posts () ){?>
					 <h1 class="entry-title">
						<?php echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , __( 'Author archives' , _DEV_ ) , 'span' ) . ': ';  ?>
						<span class='vcard'>
						  <a class="url fn n" href="" title="<?php echo esc_attr( get_the_author_meta( 'display_name' , $post-> post_author ) ); ?>" rel="me">
							  <?php echo get_the_author_meta( 'display_name' , $post-> post_author ); ?>
                          </a>
						</span>
					  </h1>
			  <?php }else{ ?>
					<h1 class="entry-title archive">
                        <?php echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , __( 'Sorry, no posts found' , _DEV_ ) , 'span' );  ?>
					</h1>
			  <?php } ?>
            </div>
        </div>
        
        <?php /* left sidebar */ ?>
        <?php _core::method( '_layout' , 'aside' , 'left' , 0 , 'author' ); ?>
        
        <?php /*  content */ ?>
        <div id="primary" <?php _core::method( '_layout' , 'primary_class' , 0 , 'author' ); ?>>
            <div id="content" role="main">
                <div <?php _core::method( '_layout' , 'content_class' , 0 , 'author' ); ?>>
                    <div class="loop-container-view <?php _core::method( '_layout' , 'view' , 'author' ); ?>">
                        <?php /*  posts loop articles */ ?>
                        <?php post::loop( 'author' ) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php /* right sidebar */ ?>
        <?php _core::method( '_layout' , 'aside' , 'right' , 0 , 'author' ); ?>
    </div>
</div>
<?php get_footer(); ?>