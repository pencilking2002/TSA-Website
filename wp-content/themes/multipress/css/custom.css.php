<?php
  $background_color=_settings::get( 'settings' , 'style' , 'slideshow' , 'background');
  $text_color=_settings::get( 'settings' , 'style' , 'slideshow' , 'color');
  $hover_color=_settings::get( 'settings' , 'style' , 'slideshow' , 'hover_color');
  $opacity=_settings::get( 'settings' , 'style' , 'slideshow' , 'opacity');

  function html2rgb($color)
	{
	  if ($color[0] == '#')
        $color = substr($color, 1);

	  if (strlen($color) == 6)
        list($r, $g, $b) = array($color[0].$color[1],
                                 $color[2].$color[3],
                                 $color[4].$color[5]);
	  elseif (strlen($color) == 3)
        list($r, $g, $b) = array($color[0].$color[0], $color[1].$color[1], $color[2].$color[2]);
	  else
        return false;

	  $r = hexdec($r); $g = hexdec($g); $b = hexdec($b);

	  return array($r, $g, $b);
	}

  $background_rgb_color=html2rgb($background_color);
  $rgb=implode(",",$background_rgb_color);
?>
/*Header background and opacity*/
.header-wrapper {background-color:<?php echo $background_color ;?> !important; background-color: rgba(<?php echo $rgb;?>, <?php echo $opacity/100;?>) !important; *filter: alpha(opacity = <?php echo $opacity;?>) !important; box-shadow: 0 1px 20px rgba(0, 0, 0, 0.50); }
.fixed-width .header-wrapper {background-color: black; background-color: rgba(0, 0, 0, 1); *filter: alpha(opacity = 100); box-shadow: none;}

/*Menu background and opacity - must be identical to header background. Added values - border*/
.sf-menu li li {background:<?php echo $background_color ;?> !important; background: rgba(<?php echo $rgb;?>, <?php echo $opacity/100;?>) !important; *filter: alpha(opacity = <?php echo $opacity;?>) !important; border: 1px solid rgba(0, 0, 0, 0.2); border-top: none;}

.cosmo-icons ul li.active a { background: black; background: rgba(255, 255, 255, 0.20); }
.sf-menu li.active li a {background: none; } /*Needed to override the above styles*/
.sf-sub-indicator {background: url(../images/arrows-white.png) -11px -104px; }

/*Slider caption background - must be identical to header background**/
.caption {background-color: <?php echo $background_color ;?> !important; background-color: rgba(<?php echo $rgb;?>, <?php echo $opacity/100;?>) !important; *filter: alpha(opacity = <?php echo $opacity;?>) !important;}
#headertxt #firstline span { color:<?php echo $text_color ;?> !important; }
#headertxt #firstline span:hover { color: <?php echo $hover_color ;?> !important; }
#headertxt #firstline { color:<?php echo $text_color ;?> !important; }
#headertxt #secondline { color: <?php echo $text_color ;?> !important; }


/*Quick news - must be identical to header background*/
.cosmo-qnews-label, .cosmo-qnews-wrapper {background-color: <?php echo $background_color ;?> !important; background-color: rgba(<?php echo $rgb;?>, <?php echo $opacity/100;?>) !important; *filter: alpha(opacity = <?php echo $opacity;?>) !important;}
.cosmo-qnews-close { color: #CB3939;}
.cosmo-qnews-label a { color:<?php echo $text_color ;?> !important; }
.cosmo-qnews-label a:hover { color:<?php echo $hover_color ;?> !important; }
.cosmo-qnews-content { color: white; }