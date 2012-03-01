
var attachdocs = new Object();
attachdocs.vr = {
    "action" : "attachdocs_load",
    "cnt" : ".panel-attachdocs",
    'init' : function(){
        return 0;
    }
};

attachdocs.r = function( method , args ){
    tools.init = function( result ){
    };
    tools.r( attachdocs.vr.action , method , args );
}