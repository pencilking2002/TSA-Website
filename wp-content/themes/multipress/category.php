<?php get_header(); ?>
<div class="b_content clearfix" id="main">
    <div class="b_page clearfix">
        
        <?php /* antet title */ ?>
        <div class="content-title">
            <div class="title">
                <h1 class="entry-title">
                    <?php
                        if( have_posts () ){
                            echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , __( 'Category archives' , _DEV_ ) . ': ' . get_cat_name( get_query_var('cat') ) , 'span' );
                        }else{
                            echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , __( 'Sorry, no posts found' , _DEV_ ) , 'span' );
                        }
                    ?>
                </h1>
            </div>
        </div>
        
        <?php /* left sidebar */ ?>
        <?php _core::method( '_layout' , 'aside' , 'left' , 0 , 'category' ); ?>
        
        <?php /*  content */ ?>
        <div id="primary" <?php _core::method( '_layout' , 'primary_class' , 0 , 'category' ); ?>>
            <div id="content" role="main">
                <div <?php _core::method( '_layout' , 'content_class' , 0 , 'category' ); ?>>
                    <div class="loop-container-view <?php _core::method( '_layout' , 'view' , 'category' ); ?>">
                        <?php /*  posts loop articles */ ?>
                        <?php post::loop( 'category' ) ?>
                    </div>
                </div>
				<?php get_template_part( 'templates/pagination' ); ?>
            </div>
        </div>
        
        <?php /* right sidebar */ ?>
        <?php _core::method( '_layout' , 'aside' , 'right' , 0 , 'category' ); ?>
    </div>
</div>
<?php get_footer(); ?>