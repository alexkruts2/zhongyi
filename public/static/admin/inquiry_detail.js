$(function(){
    $(".question-area").height($(".question-area").width()*0.751);
    if(typeof biaozhengList !== 'undefined') {
        drawItems('biaozheng', biaozhengList, selBiaozheng);
        drawItems('lizheng', lizhengList, selLizheng);
        drawItems('biaoli', biaoliList, selBiaoli);
        drawItems('maizheng', maizhengList, selmaizheng);
    }
    $("input[type='checkbox']").prop('disabled',true);
    if (typeof recipes !== 'undefined') {
        $("#medicines").val(recipes);
        drawMedicine(JSON.parse(recipes),false,false);
        calcPriceTotal();
    }

});
