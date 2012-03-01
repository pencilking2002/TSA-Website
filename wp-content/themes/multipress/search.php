<?php get_header(); ?>
<div class="b_content clearfix" id="main">
    <div class="b_page clearfix">
        
        <?php /* antet title */ ?>
        <div class="content-title">
            <div class="title">
                <h1 class="entry-title">
                    <?php
                        if( have_posts () ){
                            echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , __( 'Search results for' , _DEV_ ) . ': ' . get_query_var( 's' ) , 'span' );
                        }else{
                            echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , __( 'Sorry, no results found for' , _DEV_ ) . ': ' . get_query_var( 's' ) , 'span' );
                        }
                    ?>
                </h1>
            </div>
        </div>
        
        <?php /* left sidebar */ ?>
        <?php _core::method( '_layout' , 'aside' , 'left' , 0 , 'lsearch' ); ?>
        
        <?php /*  content */ ?>
        <div id="primary" <?php _core::method( '_layout' , 'primary_class' , 0 , 'lsearch' ); ?>>
            <div id="content" role="main">
                <div <?php _core::method( '_layout' , 'content_class' , 0 , 'lsearch' ); ?>>
                    <div class="loop-container-view <?php _core::method( '_layout' , 'view' , 'lsearch' ); ?>">
                        <?php /*  posts loop articles */ ?>
                        <?php post::loop( 'lsearch' ) ?>
                    </div>
                </div>
				<?php get_template_part( 'templates/pagination' ); ?>
            </div>
        </div>
        
        <?php /* right sidebar */ ?>
        <?php _core::method( '_layout' , 'aside' , 'right' , 0 , 'lsearch' ); ?>
    </div>
</div>
<?php get_footer(); ?>