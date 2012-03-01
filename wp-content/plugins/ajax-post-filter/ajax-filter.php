<?php
/*
Plugin Name: Ajax Filter
Plugin URI: http://www.electricstudio.co.uk
Description: Filter posts with Ajax
Version: 1.4
Author: James Irving-Swift
Author URI: http://www.irving-swift.com
License: GPL2
*/

$installpath = pathinfo(__FILE__);

//include php files in lib folder
foreach (glob($installpath['dirname']."/lib/*.php") as $filename){
    include $filename;
}

add_shortcode('ajaxFilter','ajax_filter');
add_action('init','af_enqueue_scripts');

function ajax_filter($atts){
    if(!isset($atts['posttypes']))
        $posttypes = array('post, events');
    else	
        $posttypes = explode(',',$atts['posttypes']);
        
    if(isset($atts['taxonomies']))
        $taxs = explode(",",$atts['taxonomies']);
        
    if($atts['showcount']==1)
        $showCount = 1;
    else
        $showCount = 0;

    if(isset($atts['pagination']))
        $pagination = explode(",",$atts['pagination']);
    else
        $pagination = array("top","bottom");
        
    if(isset($atts['posts_per_page']))
        $postsPerPage = $atts['posts_per_page'];
    else
        $postsPerPage = 15;
        
    if(isset($atts['filters'])){
        $f = explode(",",$atts['filters']);
    }
    
    if(!isset($atts['shownav']) || $atts['shownav']=='1')    
        create_filter_nav($taxs,$posttypes,$showCount);
    add_inline_javascript($posttypes);
    create_prog_bar();?>
    <section id="ajax-filtered-section">
        <?php create_filtered_section($posttypes,$filters,$postsPerPage,$pagination);?>
    </section>
    <?php
}

function af_enqueue_scripts(){
    wp_register_style('af-style',get_bloginfo('wpurl').'/wp-content/plugins/ajax-post-filter/css/style.css');
	wp_register_script('af-script', get_bloginfo('wpurl').'/wp-content/plugins/ajax-post-filter/js/af-script.js',array('jquery'),'1.0',true);
    wp_enqueue_style('af-style');
    wp_enqueue_script('af-script');
}

function create_filter_nav($taxs = array('category'), $posttypes= array('post', 'events'), $showCount = 1, $showTitles = 1){?>
    <nav id="ajax-filters">
        <?php
        $qo = get_queried_object();
        
        foreach($taxs as $tax){
        
            $terms = get_terms( $tax, array(
                    'orderby'    => 'name',
                    'hide_empty' => 1
                )
            );
            
            if($showTitles == 1){
                $the_tax = get_taxonomy( $terms[0]->taxonomy ); 
                echo "<h2>{$the_tax->labels->name}</h2>";
            }?>
            
            <ul>
                <?php
                foreach($terms as $term){
                	echo "<li class=\"ajaxFilterItem {$term->slug} af-$tax-{$term->term_id}";
                	if($term->term_id == $qo->term_id)
                		echo " filter-selected";
                	echo "\" data-tax=\"$tax={$term->term_id}\"><a href=\"#\" class=\"ajax-filter-label\"><span class=\"checkbox\"></span>{$term->name}</a></label>";
                    if($showCount==1){
                    	echo " ({$term->count})";
                    }
                    echo "</li>";
                } ?>
            </ul>
        <?php } ?>
    </nav>
    <?php	
}
