function startprofile(){

    list.init();

    $("#table").bind("onSave", function(e){
      user.set(list.data);

      // refresh the page if the language has been changed.
      if (list.data.language!=currentlanguage) window.location.href = path+"user/view";
    });

    //------------------------------------------------------
    // Username
    //------------------------------------------------------
    $(".username").html(list.data['username']);
    $("#input-username").val(list.data['username']);

    $("#edit-username").click(function(){
      $("#username-view").hide();
      $("#edit-username").hide();
      $("#save-username").show();
      $("#edit-username-form").show();
      $("#edit-username-form input").val(list.data.username);
    });

    $("#save-username").click(function(){

      var username = $("#edit-username-form input").val();

      if (username!=list.data.username)
      {
        $.ajax({
          url: path+"user/changeusername.json",
          data: "&username="+username,
          dataType: 'json',
          success: function(result)
          {
            if (result.success)
            {
              $("#username-view").show();
              $("#edit-username-form").hide();
              list.data.username = username;
              $(".username").html(list.data.username);
              $("#change-username-error").hide();
              $("#password-changed").show();
            }
            else
            {
              $("#change-username-error").html(result.message).show();
              $("#password-changed").hide();
            }
          }
        });
      }
      else
      {
        $("#username-view").show();
        $("#edit-username-form").hide();
        $("#change-username-error").hide();
              $("#edit-username").show();
              $("#save-username").hide();
      }
    });

    //------------------------------------------------------
    // Email
    //------------------------------------------------------
    $(".email").html(list.data['email']);
    $("#input-email").val(list.data['email']);

    $("#edit-email").click(function(){
      $("#email-view").hide();
      $("#edit-email-form").show();
      $("#edit-email").hide();
      $("#save-email").show();
      $("#edit-email-form input").val(list.data.email);
    });

    $("#save-email").click(function(){

      var email = $("#edit-email-form input").val();

      if (email!=list.data.email)
      {
        $.ajax({
          url: path+"user/changeemail.json",
          data: "&email="+email,
          dataType: 'json',
          success: function(result)
          {
            if (result.success)
            {
              $("#email-view").show();
              $("#edit-email-form").hide();
              list.data.email = email;
              $(".email").html(list.data.email);
              $("#change-email-error").hide();
            }
            else
            {
              $("#change-email-error").html(result.message).show();
            }
          }
        });
      }
      else
      {
        $("#email-view").show();
        $("#edit-email-form").hide();
        $("#change-email-error").hide();
              $("#edit-email").show();
              $("#save-email").hide();
      }
    });

    //------------------------------------------------------
    // Password
    //------------------------------------------------------
    $("#changedetails").click(function(){
      $("#changedetails").hide();
      $("#change-password-form").show();
      $("#password-changed").hide();

    });

    $("#change-password-submit").click(function(){

      var oldpassword = $("#oldpassword").val();
      var newpassword = $("#newpassword").val();
      var repeatnewpassword = $("#repeatnewpassword").val();

      if (newpassword != repeatnewpassword) 
      {
        $("#change-password-error").show();
      }
      else
      {
        $.ajax({
          url: path+"user/changepassword.json",
          data: "old="+oldpassword+"&new="+newpassword,
          dataType: 'json',
          success: function(result)
          {
            if (result.success)
            {
              $("#oldpassword").val('');
              $("#newpassword").val('');
              $("#repeatnewpassword").val('');
              $("#change-password-error").hide();
              $("#password-changed").show();

              $("#change-password-form").hide();
              $("#changedetails").show();
            }
            else
            {
              $("#change-password-error").html(result.message).show();
            }
          }
        });
      }
    });

    $("#change-password-cancel").click(function(){
      $("#oldpassword").val('');
      $("#newpassword").val('');
      $("#repeatnewpassword").val('');
      $("#change-password-error").hide();
              $("#password-changed").hide();

      $("#change-password-form").hide();
      $("#changedetails").show();
    });

}

