(function($){
    var defaultOptions = {
        "number"        : 4,                                    /* default number of columns            */
        "id"            : 'jq-generic-price-col',               /* default id prefix of column          */
        "classes"       : 'pricing_box jq-generic-price-col',   /* default class of column              */

        "option_id"     : 'jq-generic-option',                  /* default id prefix of option list     */
        "price_class"   : 'jq-generic-price',                   /* default class of price box           */
        "button_class"  : 'jq-generic-button-location',         /* default class of button box          */
        "blabel_class"  : 'jq-generic-button-label',            /* default class of button label box    */
        "high_class"    : 'jq-generic-highlight'                /* default class of highlight box       */
    };

    $.fn.price = function( options ){
        var container = this;
        var opt = $.extend( {} , defaultOptions , options );
        return this.each(function(){
            container.html('');
            container.body( opt );
            container.append( '<div class="clear"></div>' );    /* onclick="javascript:"                */
            container.append( '<br><div class="standard-generic-field generic-field-button"><div class="generic-input generic-input-button"><input type="button" value="Add Price Table" class="generic-record-button  button-primary" /></div></div>' );
            container.append( '<div class="clear"></div>' );
            var cnt = container;
            jQuery( 'input[type="button"]' , container ).click(function(){
                Editor.AddText( 'content' ,  container.shorcode( opt ) );
                showNotify();
            });
        });

    }

    $.fn.body = function( opt ){
        var container = this;
        var selector;

        for( var i = 0; i < opt.number; i++ ){

            /* generate pricing box */
            this.append('<div class="'+ opt.classes + '" id="' + opt.id + '-' + i + '"></div>');

            /* container selector */
            selector = 'div#' + opt.id + '-' + i;

            /* generate header */
            jQuery( selector , this ).append( '<div class="header"></div>' );
            jQuery( selector + ' .header' , this ).append('<span><p><input type="text" value="Title of pricing box" /></p></span>');
            jQuery( selector + ' .header input[type="text"]' , this ).title('Title of pricing box');

            /* generate options */
            jQuery( selector , this ).append('<ul></ul>');
            jQuery( selector + ' ul' , this ).append( '<li id="' + opt.option_id + '-0"></li>' );
            jQuery( selector + ' ul li#' + opt.option_id + '-0' , this ).append('<p><input value="Title Option" type="text" /><p>');
            jQuery( selector + ' ul li#' + opt.option_id + '-0' , this ).append('<input type="checkbox" checked="checked"><span>Set active option</span>');
            jQuery( selector + ' ul li#' + opt.option_id + '-0 input[type="text"]' , this ).title('Title Option');

            /* generate button add new option */
            jQuery( selector , this ).append('<div class="add-options"><a href="javascript:void(0);" title="' + i + '">Add new option</a></div>');

            /* init action add new option */
            jQuery( selector + ' div.add-options a' , this ).click(function(){
                container.option( opt , jQuery( this ).attr('title') );
            });

            /* generate price */
            jQuery( selector , this ).append('<div class="' + opt.price_class + '"></div>');
            jQuery( selector + ' div.' + opt.price_class , this ).append('<p><input value="Set price" type="text"/></p>');
            jQuery( selector + ' div.' + opt.price_class + ' input[type="text"]' , this ).title('Set price');

            /* generate button location */
            jQuery( selector , this ).append('<div class="' + opt.button_class + '"></div>');
            jQuery( selector +' div.' + opt.button_class , this ).append('<p><input value="Set button url" type="text"/></p>');
            jQuery( selector +' div.' + opt.button_class + ' input[type="text"]' , this ).title('Set button url');

            /* generate button label */
            jQuery( selector , this ).append('<div class="' + opt.blabel_class + '"></div>');
            jQuery( selector +' div.' + opt.blabel_class , this ).append('<p><input value="Set button label" type="text"/></p>');
            jQuery( selector +' div.' + opt.blabel_class + ' input[type="text"]' , this ).title('Set button label');

            /* generate highlight */
            jQuery( selector , this ).append('<div class="' + opt.high_class + '"></div>');
            jQuery( selector + ' div.' + opt.high_class , this ).append('<input type="checkbox"  /><span>Set this colum highlighted</span>')
        }
    }

    $.fn.option = function( opt , col ){
        var nr;
        var cl;
        var v;
        var selector = 'div#' + opt.id + '-' + col;

        jQuery( selector + ' ul li' , this ).each(function(i){
            nr = i;
        });

        /* index of new item in list */
        nr++;

        /* generate new item in list */
        jQuery( selector + ' ul' , this ).append( '<li id="' + opt.option_id + '-' + nr + '"></li>' );
        jQuery( selector + ' ul li#' + opt.option_id + '-' + nr , this ).zebra( nr );
        jQuery( selector + ' ul li#' + opt.option_id + '-' + nr , this ).append('<p><input value="New title Option" type="text"/></p>');
        jQuery( selector + ' ul li#' + opt.option_id + '-' + nr , this ).append('<span><input type="checkbox" checked="checked"/> Set active option </span>');
        jQuery( selector + ' ul li#' + opt.option_id + '-' + nr +' span', this ).append('<span><a href="javascript:void(0);" title="' + nr + '"> drop </a></span>');

        /* init drop action */
        jQuery( selector + ' ul li a' , this ).click(function(){
            jQuery( this ).parent('span').parent('span').parent('li').remove();
            jQuery( selector + ' ul li').each(function(i){
                jQuery( this ).zebra( i );
                jQuery( this ).attr('id' ,  opt.option_id + '-' + i );
                /* reset dinamic label inner input */
                jQuery( 'input[type="text"]' , this ).title( 'New title Option' );
            });
        });

        /* generate dinamic label inner input */
        jQuery( selector + ' ul li#' + opt.option_id + '-' + nr + ' input[type="text"]' , this ).title( 'New title Option' );
    }

    $.fn.title = function( val ){
        jQuery( this ).focusin(function(){
            if( jQuery( this ).val() == val ){
                jQuery( this ).val('');
            }
        });

        jQuery( this ).focusout(function(){
            if( jQuery( this ).val() == '' ){
                jQuery( this ).val( val );
            }
        });
    }

    $.fn.zebra = function( i ){
        if( i % 2 == 0 ){
            if( this.hasClass('odd') ){
                this.removeClass('odd');
            }
            this.addClass('even');
        }else{
            if( this.hasClass('even') ){
                this.removeClass('even');
            }
            this.addClass('odd');
        }
    }

    $.fn.shorcode = function( opt ){
        var selector;
        var type;
        var classes;
        var title;
        var price;
        var href;
        var cl;
        var licl;

        result = '[price_table cols="' + opt.number + '"]';
        jQuery(function(){

            for( var i = 0; i < opt.number; i++ ){
                /* reset value */
                type = '';
                classes = '';
                selector = 'div#' + opt.id + '-' + i;

                if( i == 0 ){
                    classes = 'class="radius-left"';
                }

                /* check if is highlight box */
                if( jQuery( selector + ' div.' + opt.high_class + ' input[type="checkbox"]' , this  ).is(':checked') ){
                    type = 'type="highlight"';
                }

                if( i == opt.number - 1 ){
                    classes = 'class="radius-right"';
                }

                title = jQuery( selector + ' div.header input[type="text"]' , this  ).val();
                price = jQuery( selector + ' div.' + opt.price_class + ' input[type="text"]' , this  ).val();
                href  = jQuery( selector + ' div.' + opt.button_class + ' input[type="text"]' , this  ).val();
                label = jQuery( selector + ' div.' + opt.blabel_class + ' input[type="text"]' , this  ).val();

                result += '[price_table_col ' + type + ' ' + classes + ' title="' + title + '" price="' + price + '" href="' + href + '" button_label="' + label + '" ]';
                result += '<ul>';
                jQuery( selector + ' ul li').each(function(i){
                    if( jQuery( 'input[type="checkbox"]' , this  ).is(':checked') ){
                        cl = 'yes';
                    }else{
                        cl = 'no';
                    }

                    if( i % 2 == 0 ){
                        licl = 'even';
                    }else{
                        licl = 'odd';
                    }
                    result += '<li class="' + licl + '"><strong class="' + cl + '">' + jQuery( 'input[type="text"]' , this ).val() + '</strong></li>';
                });
                result += '</ul>';
                result += '[/price_table_col]';
            }

            result += '[/price_table]';
        });

        return result;
    }
})(jQuery);

function price( nr ){
    jQuery(function(){
        jQuery('.container-price-cols').price( { "number" : nr  } );
    });
}