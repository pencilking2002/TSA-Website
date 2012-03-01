<?php get_header(); ?>
<div class="b_content clearfix" id="main">
    <div class="b_page clearfix">
        
        <?php /* antet title */ ?>
        <div class="content-title">
            <div class="title">
                <h1 class="entry-title">
                    <?php
                        if ( is_day() ) {
                            echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , __( 'Daily archives' , _DEV_ ) . ': ' . get_the_date() , 'span' );
                        }else if ( is_month() ) {
                            echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , __( 'Monthly archives' , _DEV_ ) . ': ' . get_the_date( 'F Y' ) , 'span' );
                        }else if ( is_year() ) {
                            echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , __( 'Yearly archives' , _DEV_ ) . ': ' . get_the_date( 'Y' ) , 'span' );
                        }else {
                            echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , __( 'Blog archives' , _DEV_ ) , 'span' );
                        }
                    ?>
                </h1>
            </div>
        </div>
        
        <?php /* left sidebar */ ?>
        <?php _core::method( '_layout' , 'aside' , 'left' , 0 , 'archive' ); ?>
        
        <?php /*  content */ ?>
        <div id="primary" <?php _core::method( '_layout' , 'primary_class' , 0 , 'archive' ); ?>>
            <div id="content" role="main">
                <div <?php _core::method( '_layout' , 'content_class' , 0 , 'archive' ); ?>>
                    <div class="loop-container-view <?php _core::method( '_layout' , 'view' , 'archive' ); ?>">
                        <?php /*  posts loop articles */ ?>
                        <?php post::loop( 'archive' ) ?>
                    </div>
                </div>
            </div>
        </div>
        
        <?php /* right sidebar */ ?>
        <?php _core::method( '_layout' , 'aside' , 'right' , 0 , 'archive' ); ?>
    </div>
</div>
<?php get_footer(); ?>