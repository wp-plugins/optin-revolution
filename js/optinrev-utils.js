var css = {
parseCSSBlock : function(css) { 
    var rule = {};
    var declarations = css.split(';');
    declarations.pop();
    var len = declarations.length;
    for (var i = 0; i < len; i++)
    {
        var loc = declarations[i].indexOf(':');
        var property = jQuery.trim(declarations[i].substring(0, loc));
        var value = jQuery.trim(declarations[i].substring(loc + 1));

        if (property != "" && value != "")
            rule[property] = value;
    }
    return rule;
},
cssBlock : function( parsed_css ) {
    var rule = '';    
    for( v in parsed_css ) {
        rule += v + ':' + parsed_css[ v ] + ';';
    };      
    return rule;    
}
}

var input_valid = {msg: function(m){alert(m);},
is_email: function( email ) {var re = /^(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])$/;return re.test( email );},
is_name: function( name ) {var re = /^[a-zA-Z]+$/;return re.test( name );}
};
