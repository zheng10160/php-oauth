/*
* @Author: Marte
* @Date:   2018-10-24 14:33:10
* @Last Modified by:   Marte
* @Last Modified time: 2018-10-24 15:29:58
*/

'use strict';
// 手机号码清空按钮
$('#u_p').focus(function(){
    $('.clear-user').show();
})
$('#u_p').blur(function(){
    $('.clear-user').hide();
})

//清除内容
$(".clear-user").on("touchend",function(){
     $('#u_p').val('');
});
//密码可视
$('#pwd').focus(function(){
    $('.clear-pwd').show();
})
// $('#pwd').blur(function(){
//     $('.clear-pwd').hide();
// })
// 密码可见
$('.clear-pwd').on('click',function(){

    if($('#pwd').attr('type')=='password'){
        $('#pwd').attr('type','text');
    }else{
        $('#pwd').attr('type','password');
    }
    if($('#pwd').val()==''){
        $('#pwd').attr('type','password');
    }
})