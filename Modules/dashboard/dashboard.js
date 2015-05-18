
var dashboard = {

    /*
// Deprecation Notice: The jqXHR.success(), jqXHR.error(), and jqXHR.complete() callbacks are deprecated 
// as of jQuery 1.8. To prepare your code for their eventual removal, use jqXHR.done(), jqXHR.fail(),
// and jqXHR.always() instead.


    var myvariable = $.ajax( "example.php" )
      .done(function() {
        alert( "success" );
      })
      .fail(function() {
        alert( "error" );
      })
      .always(function() {
        alert( "complete" );
    });

     */


    'list':function()
    {
        var result = {};
/*        $.ajax({
            url: path+"dashboard/list.json",
            dataType: 'json',
            async: false,
            success: function(data) {result = data;} });
        return result;

*/
        $.ajax({
            url      : path+"dashboard/list.json",
            datatype : "json",
            async    : false
            })
            .done(function (data, textStatus, jqXHR){
                //alert( "success" );
                result= data;
            })
            .fail(function (jqXHR, textStatus, errorThrown) {
                //alert( "error" );
                // unused
            })
            .always(function() {
                //alert( "complete" );
                // unused
            });
            return result;
    },


    'set':function(id, fields)
    {
        var result = {};
        $.ajax({
            url   : path+"dashboard/set.json",
            data  : "id="+id+"&fields="+JSON.stringify(fields),
            async : false
            })
            .done(function (data, textStatus, jqXHR){
                showfeedback(data);
            });
        return result;
    },

    'remove':function(id)
    {
        $.ajax({
            url   : path+"dashboard/delete.json",
            data  : "id="+id,
            async : false
            })
            .done(function (data, textStatus, jqXHR){
                showfeedback(data);
            });
    }

};

