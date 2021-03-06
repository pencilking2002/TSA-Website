<?php
    get_header();
    
    if( isset( $wp_query -> queried_object -> ID ) &&  $wp_query -> queried_object -> ID == get_option( 'page_for_posts' ) ){
        global $wp_query;
        $wp_query = new WP_Query( array( 'post_type' => 'post' , 'post_status' => 'publish' ) );
    }
?>
<div class="b_content clearfix" id="main">
    <div class="b_page clearfix">
        
        <?php /* antet title */ ?>
        <div class="content-title">
            <div class="title">
                <h1 class="entry-title">
                    <?php
                        if( have_posts () ){
                            if( isset( $_GET[ 'fp_type' ] ) ){
                                $title = __( 'Post type ' , _DEV_ ) . ' ' . $_GET[ 'fp_type' ];
                            }else{
                                $title = __( 'Blog page' , _DEV_ );
                            }
                            echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , $title , 'span' );
                        }else{
                            echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , __( 'Sorry, no posts found' , _DEV_ ) , 'span' );
                        }
                    ?>
                </h1>
            </div>
        </div>
        
        <?php /* left sidebar */ ?>
        <?php _core::method( '_layout' , 'aside' , 'left' , 0 , 'blog_page' ); ?>
        
        <?php /*  content */ ?>
        <div id="primary" <?php _core::method( '_layout' , 'primary_class' , 0 , 'blog_page' ); ?>>
            <div id="content" role="main">
                <div <?php _core::method( '_layout' , 'content_class' , 0 , 'blog_page' ); ?>>
                    <div class="loop-container-view <?php _core::method( '_layout' , 'view' , 'blog_page' ); ?>">
                        <?php /*  posts loop articles */ ?>
                        <?php post::loop( 'blog_page' ) ?>
                    </div>
                </div>
                
                <?php get_template_part( 'templates/pagination' ); ?>
            </div>
        </div>
        
        <?php /* right sidebar */ ?>
        <?php _core::method( '_layout' , 'aside' , 'right' , 0 , 'blog_page' ); ?>
    </div>
</div>
<?php get_footer(); ?>