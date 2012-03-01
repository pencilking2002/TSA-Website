
var program = new Object();
program.vr = {
    "action" : "program_load",
    "cnt" : ".panel-program",
    'init' : function(){
        return 0;
    }
};

program.r = function( method , args ){
    tools.init = function( result ){
    };
    tools.r( program.vr.action , method , args );
}