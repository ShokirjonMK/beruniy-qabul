// $(document).ready(function() {
//     $('.pageLoading').fadeOut('slow');
// });


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
    const password2 = $("#eye_password");
    $("#clickPassword").click(function(){
        if(password2.prop('type')=='password')
        {
            $(this).addClass('fas fa-eye');
            $(this).removeClass('fa-eye-slash');
            password2.attr('type','text');
        }else
        {
            $(this).removeClass('fa-eye');
            $(this).addClass('fa-eye-slash');
            password2.attr('type','password');
        }
    });
});

$(document).ready(function(){
    $('#passwordView').click(function () {
         if ($(this).hasClass('fa-eye-slash')) {
             $(this).addClass('fas fa-eye');
             $(this).removeClass('fa-eye-slash');
             $('.textPassword').css('display' , 'block');
             $('.slashPassword').css('display' , 'none');
         } else {
             if ($(this).hasClass('fa-eye')) {
                 $(this).removeClass('fa-eye');
                 $(this).addClass('fa-eye-slash');
                 $('.textPassword').css('display' , 'none');
                 $('.slashPassword').css('display' , 'block');
             }
         }
    });
});

// $(document).ready(function(){
//     $("#ww1 a").on('click', function (e) {
//         e.preventDefault();
//         var id = $(this).data('id');
//         $.ajax({
//             url: '../group/group-day-update/',
//             data: {id: id},
//             type: 'POST',
//             success: function (data) {
//                 $("#loadLink").html(data);
//                 $("#updateGroup").show();
//             },
//             error: function () {
//                 alert("ERROR!");
//             }
//         });
//     });
// });

$(document).ready(function() {
    $('#updateGroup').on('show.bs.modal', function (e) {
        $(this).find('#loadLink').load(e.relatedTarget.href);
    });
});
$(document).ready(function() {
    $('#deleteGroupDay').on('show.bs.modal', function (e) {
        $(this).find('#deleteLink').load(e.relatedTarget.href);
    });
});

$(document).ready(function() {
    $('#updateStudent').on('show.bs.modal', function (e) {
        $(this).find('#updateStudentBody').load(e.relatedTarget.href);
    });
});

$(document).ready(function() {
    $('#studentGroupDelete').on('show.bs.modal', function (e) {
        $(this).find('#studentGroupDeleteBody').load(e.relatedTarget.href);
    });
});
