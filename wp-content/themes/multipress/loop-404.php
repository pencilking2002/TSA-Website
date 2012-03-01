<!-- if not found post or is 404 error -->
<article <?php post_class( 'post' ) ?>>
    <!-- content -->
    <footer class="entry-footer">
        <div class="excerpt">
            <?php
                if( is_search () ){
                    _e( 'Unfortunately we did not find any results for your request.' , _DEV_ );
                }else{
                    _e( 'We apologize but this page, post or resource does not exist or can not be found. Perhaps it is necessary to change the call method to this page, post or resource.' , _DEV_ );
                }

                wp_link_pages();
            ?>
        </div>
    </footer>
</article>

