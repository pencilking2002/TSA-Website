jQuery(document).ready(function(){
    /* show images inserted in gallery */
    jQuery("a[rel^='prettyPhoto']").prettyPhoto({
            autoplay_slideshow: false,
            theme: 'light_rounded'

    });

    /* show images inserted into post in LightBox */
    jQuery("[class*='wp-image-'] , ").parents('a').prettyPhoto({
            autoplay_slideshow: false,
            theme: 'light_rounded'

    });

    jQuery("a[rel^='keyboardtools']").prettyPhoto({
            autoplay_slideshow: false,
            theme: 'light_rounded',
            social_tools : ''

    });
});