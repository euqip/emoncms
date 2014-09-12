
var dashboard = {

    'list':function()
    {
        var result = {};
        $.ajax({
            url: path+"dashboard/list.json",
            dataType: 'json',
            async: false,
            success: function(data) {result = data;} });
        return result;
    },

    'set':function(id, fields)
    {
        var result = {};
        $.ajax({
            url: path+"dashboard/set.json",
            data: "id="+id+"&fields="+JSON.stringify(fields),
            async: false,
            success: function(data){
                showfeedback(data);
            } });
        return result;
    },

    'remove':function(id)
    {
        $.ajax({
            url: path+"dashboard/delete.json",
            data: "id="+id,
            async: false,
            success: function(data){
                showfeedback(data);
            } });
    }

}

function showfeedback(data){
                    if (data['success'] == false){
                    $('#msgfeedback').html(data.message);
                    $('.feedback').removeClass("in alert-danger alert-success")
                    $(".feedback").fadeIn().delay(200).addClass("in alert-danger").fadeOut(2000);
                } else {
                    $('#msgfeedback').html(data.message);
                    $('.feedback').removeClass("in alert-danger alert-success")
                    $(".feedback").fadeIn().delay(200).addClass("in alert-success" ).fadeOut(2000);
                }

}