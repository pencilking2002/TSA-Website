
(function($){
    var def = {
        options : {
            map : {
                zoom : 2,
                mapTypeId: google.maps.MapTypeId.ROADMAP
            },
            pos : {
                lat : 28.1546,
                lng : 41.1083
            },
            markers : [],
            selectors : {
                markers  : '.select-markers',
                edit : '.edit-marker-form',
                add : '.add-marker-form'
            },
            listenerHandle : null,
            postID : 0
        },

        init:  function(){
            return this.each(function(){
                var gmap = new google.maps.Map( this , def.options.map );
                gmap.setCenter( new google.maps.LatLng( def.options.pos.lat , def.options.pos.lng ) );
                google.maps.event.addListener( gmap , 'zoom_changed', function() {
                    map.r( 'edit' , [ def.options.postID  , -1 , {zoom : gmap.getZoom() , pos:{lat: gmap.getCenter().lat() , lng : gmap.getCenter().lng()}} ] );
                });
                google.maps.event.addListener( gmap , 'center_changed', function() {
                    map.r( 'edit' , [ def.options.postID  , -1 , {pos:{lat: gmap.getCenter().lat() , lng : gmap.getCenter().lng()}} ] );
                });
                def.addMarkers.apply( gmap );
                def.drawMarker.apply( gmap );

            });
        },
        
        initFrontEnd:  function(){
            return this.each(function(){
                var gmap = new google.maps.Map( this , def.options.map );
                gmap.setCenter( new google.maps.LatLng( def.options.pos.lat , def.options.pos.lng ) );
                def.addMarkersFrontEnd.apply( gmap );

            });
        },

        addMarkers: function(){
            var _self = this;
            var markerOption;
            for( var i = 0; i < def.options.markers.length; i++ ){
                if( def.options.markers[i].icon != "undefined" ){
                    if( def.options.markers[i].icon == "standard" ){
                        markerOption = {
                            position: new google.maps.LatLng( def.options.markers[i].lat, def.options.markers[i].lng ),
                            map: _self,
                            draggable: true,
                            visible: true
                        };
                    }else{
                        markerOption = {
                            position: new google.maps.LatLng( def.options.markers[i].lat, def.options.markers[i].lng ),
                            map: _self,
                            draggable: true,
                            visible: true,
                            icon: def.options.markers[i].src,
                            shadow: def.options.markers[i].shadow
                        };
                    }
                }else{
                    markerOption = {
                        position: new google.maps.LatLng( def.options.markers[i].lat, def.options.markers[i].lng ),
                        map: _self,
                        draggable: true,
                        visible: true
                    };
                }
                var marker = new google.maps.Marker( markerOption );

                (function(i, marker) {
                    marker.set( 'id' , def.options.markers[i].id );
                    
                    google.maps.event.addListener( marker , 'mouseup' , function( event ){
                        
                        map.marker = marker;
                        map.vr.init = function( args ){
                            map.r( 'edit' , [ def.options.postID  , marker.get('id') , {lat: event.latLng.lat() , lng : event.latLng.lng()}] );
                        }
                        field.load(  map , 'editForm' , [ def.options.postID , marker.get('id') ] , '.panel-map-action' , '.marker-editbox' );
                    });
                })(i, marker);
            }
        },
        
        addMarkersFrontEnd: function(){
            var _self = this;
            var markerOption;
            for( var i = 0; i < def.options.markers.length; i++ ){
                if( def.options.markers[i].icon != "undefined" ){
                    if( def.options.markers[i].icon == "standard" ){
                        markerOption = {
                            position: new google.maps.LatLng( def.options.markers[i].lat, def.options.markers[i].lng ),
                            map: _self,
                            draggable: false,
                            visible: true
                        };
                    }else{
                        markerOption = {
                            position: new google.maps.LatLng( def.options.markers[i].lat, def.options.markers[i].lng ),
                            map: _self,
                            draggable: false,
                            visible: true,
                            icon: def.options.markers[i].src,
                            shadow: def.options.markers[i].shadow
                        };
                    }
                }else{
                    markerOption = {
                        position: new google.maps.LatLng( def.options.markers[i].lat, def.options.markers[i].lng ),
                        map: _self,
                        draggable: false,
                        visible: true
                    };
                }
                var marker = new google.maps.Marker( markerOption );

                (function(i, marker) {
                    if( def.options.markers[i].html != null ){
                        google.maps.event.addListener( marker , 'click', function() {
                            var infowindow = new google.maps.InfoWindow({
                                content: def.options.markers[i].html
                            });
                            infowindow.open( _self , marker);
                        });
                    }
                    
                    marker.set( 'id' , def.options.markers[i].id );
                    
                })(i, marker);
            }
        },

        drawMarker: function(){
            var _self = this;
            var marker;
            jQuery( def.options.selectors.markers ).each(function(){
                jQuery( this ).click(function(){
                    if( def.options.listenerHandle != null ){
                        google.maps.event.removeListener( def.options.listenerHandle );
                    }
                    marker = this;    
                    def.options.listenerHandle = google.maps.event.addListener( _self , 'click' , function( event ){
                        def.options.markers = new Array();
                        
                        /* ajax get id */
                        jQuery.post( ajaxurl, {
                                action : map.vr.action,
                                method : 'add',
                                args: [ 
                                    def.options.postID ,
                                    event.latLng.lat() ,
                                    event.latLng.lng() ,
                                    jQuery( marker ).attr('alt'),
                                    jQuery( marker ).attr('src'),
                                    jQuery( def.options.selectors.markers  + '.shadow' ).val()
                                ]
                            } , function( result ){
                                field.load(  map , 'editForm' , [ def.options.postID , result ] , '.panel-map-action' , '.marker-editbox' );
                                def.options.markers = [{
                                    lat: event.latLng.lat(), 
                                    lng: event.latLng.lng(),
                                    html : '',
                                    icon: jQuery( marker ).attr('alt'),
                                    src: jQuery( marker ).attr('src'),
                                    shadow: jQuery( def.options.selectors.markers  + '.shadow' ).val(),
                                    id: result
                                }];
                            
                                def.addMarkers.apply( _self );
                            }
                        );
                    });
                });
            })
        }
    }

    $.fn.gmap = function( options ){
        if( typeof options.map != "undefined" ){
            def.options.map = $.extend( {} , def.options.map , options.map );
        }
        if( typeof options.pos != "undefined" ){
            def.options.pos = $.extend( {} , def.options.pos , options.pos );
        }

        if( typeof options.markers != "undefined" ){
            def.options.markers = options.markers;
        }

        if( typeof options.selector != "undefined" ){
            def.options.selectors = $.extend( {} , def.options.selectors , options.selectors );
        }
        if( typeof options.postID != "undefined" ){
            if( options.postID > 0 ){
                def.options.postID = options.postID;
            }else{
                def.options.postID = jQuery('#post_ID').val();
            }
        }

        def.init.apply( this );
    }
    
    $.fn.gmapFrontEnd = function( options ){
        if( typeof options.map != "undefined" ){
            def.options.map = $.extend( {} , def.options.map , options.map );
        }
        if( typeof options.pos != "undefined" ){
            def.options.pos = $.extend( {} , def.options.pos , options.pos );
        }

        if( typeof options.markers != "undefined" ){
            def.options.markers = options.markers;
        }

        if( typeof options.selector != "undefined" ){
            def.options.selectors = $.extend( {} , def.options.selectors , options.selectors );
        }
        if( typeof options.postID != "undefined" ){
            if( options.postID > 0 ){
                def.options.postID = options.postID;
            }else{
                def.options.postID = jQuery('#post_ID').val();
            }
        }

        def.initFrontEnd.apply( this );
    }
})(jQuery);

var map = new Object();

map.vr = {
    'cnt' : '.panel-map',
    'canvas' : '#map_canvas',     
    'action' : 'map_load',
    'init' : function(){
    }
};

map.r = function( method , args  ){
    tools.init = function( result ){
        if( method == 'remove' ){
            map.vr.init( result );
        }
    };
    tools.r( map.vr.action , method , args );
}

map.remove = function(){
    map.marker.setMap(null);
}