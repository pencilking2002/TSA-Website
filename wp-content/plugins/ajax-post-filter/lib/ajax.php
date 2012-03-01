<?php
/*
 * ajax.php
 * Created on Sep 29, 2011 - By swifty
 *
 */

if(is_admin()){
        add_action( 'wp_ajax_nopriv_affilterposts', 'create_filtered_section' );
    add_action( 'wp_ajax_affilterposts', 'create_filtered_section' );
}else{
        add_action( 'wp_ajax_nopriv_affilterposts', 'create_filtered_section' );
}

function add_inline_javascript($posttypes = array('post')){
    $qo = get_queried_object();

    //get the page's current taxonomy to filter
    if(isset($qo->term_id))
       $qoString = $qo->taxonomy."##".$qo->term_id;
    else
           $qoString = "af_na";

    ?>
    <script type="text/javascript">
        var $j = jQuery.noConflict(),
            ajaxurl = '<?php bloginfo('url'); ?>/wp-admin/admin-ajax.php',
            posttypes = '<?php echo implode(',',$posttypes); ?>',
            qo = '<?php echo $qoString; ?>',
            thisPage = 1,
            nonce = '<?php echo js_escape(wp_create_nonce('filternonce')); ?>';
    </script>
<?php
}

function create_filtered_section($pt = array("post"), $filters = array(), $postPerPage=15, $paginationDisplay = array('top','bottom'), $useQO = true){
	//$useQO refers to using the Queried Object. This is to preset certain filters if in a taxonomy page.
	
    if($_POST){
        check_ajax_referer('filternonce');
        $pt = explode(',',$_POST['posttypes']);
    }

    $f = explode('&',$_POST['filters']);
    $c=0;

    if($f[0] != ""){ //check that the array isn't blank

        //this while loop put the filters in a usable array
        while($c < count($f)){
                $string = explode('=',$f[$c]);
            if(!is_array($filters[$string[0]]))
                $filters[$string[0]] = array();
            array_push($filters[$string[0]],$string[1]);
            $c++;
        }

    }


    $args = array(
        "post_type" => $pt,
        "posts_per_page" => $postPerPage,
        "tax_query" => array(),
        "orderby" => "title",
        "order" => "ASC",
    	"post_status" => "publish"
    );

    if(!$_POST){
        $qo = get_queried_object();

        //get the page's current taxonomy to filter
        if(isset($qo->term_id) && $useQO==true){
            array_push($args['tax_query'],
                array(
                    'taxonomy' => $qo->taxonomy,
                    'field' => 'id',
                    'terms' => $qo->term_id
                )
            );
        }
    }else{
        if($_POST['qo'] != 'af_na'){
                $qo = explode('##',$_POST['qo']);
            array_push($args['tax_query'],
                array(
                    'taxonomy' => $qo[0],
                    'field' => 'id',
                    'terms' => $qo[1]
                )
            );
        }
        if(isset($_POST['paged']))
            $args['paged'] = $_POST['paged'];

    }

    if(isset($_POST['paged']))
        $args['paged'] = $_POST['paged'];
    else
        $args['paged'] = 1;

    if(isset($filters)){
        //add all the filters to tax_query
        foreach($filters as $tax => $ids){
            foreach($ids as $id){
                array_push($args['tax_query'],
                    array(
                        'taxonomy' => $tax,
                        'field' => 'id',
                        'terms' => $id
                    )
                );
            }
        }
    }

    //inserts a relation if more than one array in the tax_query
    if(count($args['tax_query'])>1)
        $args['tax_query']['relation'] = 'AND';


        if(file_exists(get_stylesheet_directory()."/ajax-loop.php")){
                include get_stylesheet_directory()."/ajax-loop.php";
        }else{
            $i =0;
            $ajaxPostfilter = new WP_Query();
            $ajaxPostfilter->query($args);
            if(in_array("top",$paginationDisplay))
                af_pageination($ajaxPostfilter->found_posts, $postPerPage);
            if($ajaxPostfilter->have_posts()): while ($ajaxPostfilter->have_posts()) : $ajaxPostfilter->the_post(); ?>
            <?php $i++; ?>
            <article>
                <h3><?php the_title();?></h3>
                <?php the_post_thumbnail(); ?>
                <p><?php the_excerpt(); ?></p>
            </article>
            <?php endwhile; else:
                echo "No Results found :-(";
            endif;
        }
    
    if(in_array("bottom",$paginationDisplay))
    af_pageination($ajaxPostfilter->found_posts, $postPerPage);
    echo "<p>Total Results: {$ajaxPostfilter->found_posts}</p>";


    if($_POST)
        die();
}

function af_pageination($totalPosts,$postPerPage){?>
            <nav class="pagination">
            <?php if($_POST && $_POST['paged']>1){ ?>
                <div class="prevPage"><a class="paginationNav" rel="prev" href="#">&lt; Prev Page</a></div>
            <?php } ?>
            <div class="af-pages">
                <?php
                $p = 1;
                while($p<=ceil($totalPosts/$postPerPage)){
                	echo '<a href="#" class="pagelink-'.$p.' pagelink';
                    if($p == $_POST['paged'] || (!$_POST && $p == 1))
                    	echo "current";
                    echo '" rel="'.$p.'">'.$p.'</a>';
                    if($p <= ceil($totalPosts/$postPerPage-1))
                        echo " | ";
                    $p++;
                }
                ?>
            </div>    
            <?php if($postPerPage*$_POST['paged']<$totalPosts && $postPerPage<$totalPosts){ ?>
                <div class="nextPage"><a class="paginationNav" rel="next" href="#">Next Page &gt;</a></div>
            <?php } ?>
        </nav>
<?php }
