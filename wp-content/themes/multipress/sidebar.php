<aside class ="w_260">
    <?php if (function_exists('dynamic_sidebar') && dynamic_sidebar('Sidebar Widgets')) : else : ?>
    <?php endif; ?>
    
    <h3 class ="event-categories">Categories</h3>
		<?php $terms = get_terms("event-category");
		 $count = count($terms);
		 if ( $count > 0 ){
		     echo "<ul>";
		     foreach ( $terms as $term ) {
		       echo '<p>' . '<a href="' . esc_attr(get_term_link($term, $taxonomy)) . '" title="' . sprintf( __( "View all posts in %s" ), $term->name ) . '" ' . '>' . $term->name.'</a> has ' . $term->count . ' post(s). </p> ';
					    }
					  }

    
   ?>
    </div><!-- End News Feed -->  

    <!-- All this stuff in here only shows up if you DON'T have any widgets active in this zone -->
  </aside>
