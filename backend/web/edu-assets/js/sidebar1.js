$(".sidebar_li_link").click(function() {
    $(".menu_drop").slideUp(200);
    if (
        $(this)
            .parent()
            .hasClass("menu_active")
    ) {
        $(".sidebar_drop").removeClass("menu_active");
        $(this)
            .parent()
            .removeClass("menu_active");
    } else {
        $(".sidebar_drop").removeClass("menu_active");
        $(this)
            .next(".menu_drop")
            .slideDown(200);
        $(this)
            .parent()
            .addClass("menu_active");
    }
});

$("#close-sidebar").click(function() {
    $(".page-wrapper").removeClass("toggled");
});
$("#show-sidebar").click(function() {
    $(".page-wrapper").addClass("toggled");
});


$(".display_close").click(function() {
    this.classList.toggle("show_active");
    var a = $(".display_close").hasClass("show_active");
    if (a)
    {
        $("#sidebar").addClass("toggle_sidebar");
        $(".left-260").css("paddingLeft", "50px");
    }else
    {
        $("#sidebar").removeClass("toggle_sidebar");
        $(".left-260").css("paddingLeft", "260px");
    }
});


$(".display_show").click(function() {
    this.classList.toggle("show_active");
    var a = $(".display_show").hasClass("show_active");
    if (a)
    {
        $(".root_left").css("left", "0px");
    }else
    {
        $(".root_left").css("left", "-400px");
    }
});

$(".close_button").click(function() {
    $(".root_left").css("left", "-400px");
    $(".display_show").removeClass("show_active");
});

$(window).resize(function() {
    if ($(window).width() < 1024) {
        $(".content").removeAttr('style');
        $(".navbar_item").removeAttr('style');
        $(".root_left").removeClass('toggle_sidebar');
        $(".close_nav").removeClass('show_active');
    } else {
        $(".root_left").removeAttr('style');
    }
    var heigth = $(".navbar_item").outerHeight();
    $(".content").css("padding-top", heigth + 22);
}).resize();

$(".course_name").click(function() {
    this.classList.toggle("group-show");
});

