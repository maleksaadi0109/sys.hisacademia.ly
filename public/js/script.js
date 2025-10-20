$(document).ready(function () {

    $('#sidebarCollapse').on('click', function () {
        $('#sidebar').toggleClass('active');
    });

    $('.nav-link').on('click', function () {
        $('.nav-link').removeClass('active');
        $('.collapse').removeClass('show');
        $(this).toggleClass('active');
        $(this).siblings('.collapse').toggleClass('show');
    });

    $('.li-collapse a').each(function(index){
        if($(this).attr('href') == window.location || $(this).attr('href')+'#' == window.location){
            $('.nav-link').removeClass('active');
            $('.collapse').removeClass('show');
            $(this).closest('.collapse').toggleClass('show');
            $(this).closest('.li-collapse').toggleClass('li-collapse-active');
        }
    });

    
    var $loading = $('#loading').hide();
    $(document)
    .ajaxStart(function () {
        $loading.show();
    })
    .ajaxStop(function () {
        $loading.hide();
    });
    
});