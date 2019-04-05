
function remove() {
    if($(this).scrollTop()>90){
        $('.container-logo').addClass('remove-logo');
    }
    else if ($(this).scrollTop()<90){
        $('.container-logo').removeClass('remove-logo');
    }
}
remove();


timeout_click_on_button();
function timeout_click_on_button() {
 var btn =  $('.button');
 btn.on('click',function () {
    if(check_timeout_button(btn)){
    $(this).attr('timeout','')
        setTimeout(function () {
            btn.removeAttr('timeout');
        }, 3000);
    }

 })
}

function check_timeout_button(btn){
    var attr = btn.attr('timeout');
   return !(typeof attr !== typeof undefined && attr !== false)
}

jQuery(function($) {
    $(window).scroll(function(){
        remove();
    })


});