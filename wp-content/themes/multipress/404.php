<?php get_header(); ?>
<div class="b_content clearfix" id="main">
    <div class="b_page clearfix">
        
        <?php /* antet title */ ?>
        <div class="content-title">
            <div class="title">
                <h1 class="entry-title">
                    <?php echo _core::method( '_text' , 'content' , 'settings' , 'style' , 'archive' , 'title' , 'text' , __( 'Error 404, page, post or resource can not be found' , _DEV_ ) , 'span' ); ?>
                </h1>
            </div>
        </div>
        
        <?php /* left sidebar */ ?>
        <?php _core::method( '_layout' , 'aside' , 'left' , 0 , 'l404' ); ?>
        
        <?php /*  content */ ?>
        <div id="primary" <?php _core::method( '_layout' , 'primary_class' , 0 , 'l404' ); ?>>
            <div id="content" role="main">
                <div <?php _core::method( '_layout' , 'content_class' , 0 , 'l404' ); ?>>
                    <div class="loop-container-view list">
                        <?php /*  posts loop articles */ ?>
                        <div class="element last">
                            <?php get_template_part( 'loop' , '404' ); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <?php /* right sidebar */ ?>
        <?php _core::method( '_layout' , 'aside' , 'right' , 0 , 'l404' ); ?>
    </div>
</div>
<?php get_footer(); ?>