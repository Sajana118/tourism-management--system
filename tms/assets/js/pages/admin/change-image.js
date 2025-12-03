// Script for sticky navigation
$(document).ready(function() {
    var navoffeset = $(".header-main").offset().top;
    $(window).scroll(function(){
        var scrollpos = $(window).scrollTop(); 
        if(scrollpos >= navoffeset){
            $(".header-main").addClass("fixed");
        } else {
            $(".header-main").removeClass("fixed");
        }
    });
});

// Script for sidebar toggle
var toggle = true;
$(".sidebar-icon").click(function() {                
    if (toggle) {
        $(".page-container").addClass("sidebar-collapsed").removeClass("sidebar-collapsed-back");
        $("#menu span").css({"position":"absolute"});
    } else {
        $(".page-container").removeClass("sidebar-collapsed").addClass("sidebar-collapsed-back");
        setTimeout(function() {
            $("#menu span").css({"position":"relative"});
        }, 400);
    }
    toggle = !toggle;
});