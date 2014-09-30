$(document).ready(function(){
	/*
	Wait until document ready
	insert the feedback message block (div) in localheading
	set click functions for common icon buttons
	 */
	var str = '<div class="feedback pull-right fade"><span id ="msgfeedback"></span></div>';
	$("#localheading").prepend(str);

    $("#expandall").click(function() {
        table.groupby = groupfield;
        table.expand = true;
        table.tablegrpidshow = false;
        table.state = 1;
        update();
    })

    $("#collapseall").click(function() {
        table.groupby = groupfield;
        table.collapse = true
        table.tablegrpidshow = false;
        table.state = 0;
        update();
    })

    $("#nogroups").click(function() {
        table.groupby = '';
        table.expand = true;
        table.tablegrpidshow = true;
        table.state = 2;
        update();
    })


});


function showfeedback(data){
	var myclass = (data['success']) ? "alert-success" : "alert-danger";
    $('#msgfeedback').html(data.message);
    $('.feedback').removeClass("in alert-danger alert-success")
    $(".feedback").fadeIn().delay(200).addClass("in "+myclass).fadeOut(2000);
};


