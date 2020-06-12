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
        drawMedicine(JSON.parse(recipes),true,false,true);
        calcPriceTotal();
        $("input").prop("disabled",true);
    }
    videojs('player').ready(function() {
        var player = this;
        player.controlBar.progressControl.seekBar.on('mouseup', function(event) {
            var seekBarEl = this.el();
            var seekBarRect = videojs.dom.getBoundingClientRect(seekBarEl);
            var seekBarPoint = videojs.dom.getPointerPosition(seekBarEl, event).x;
            var duration = player.duration();
            var seekBarClickedTime = videojs.formatTime(seekBarPoint * duration, duration);
            console.log('Seekbar clicked time: ', seekBarClickedTime);
        });
    });

});
