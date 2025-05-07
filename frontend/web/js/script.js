$(document).ready(function() {
    $('.pageLoading').fadeOut('slow');
});


$(document).ready(function(){
    const password = $("#eye_password");
    $("#eyePassword").click(function(){
        if(password.prop('type')=='password')
        {
            $(this).addClass('fas fa-eye');
            $(this).removeClass('fa-eye-slash');
            password.attr('type','text');
        }else
        {
            $(this).removeClass('fa-eye');
            $(this).addClass('fa-eye-slash');
            password.attr('type','password');
        }
    });
});

$(document).ready(function(){
    const password = $("#eye_password1");
    $("#eyePassword1").click(function(){
        if(password.prop('type')=='password')
        {
            $(this).addClass('fas fa-eye');
            $(this).removeClass('fa-eye-slash');
            password.attr('type','text');
        }else
        {
            $(this).removeClass('fa-eye');
            $(this).addClass('fa-eye-slash');
            password.attr('type','password');
        }
    });
});