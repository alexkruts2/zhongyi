function setCrawlerStatus(status) {
    $("#shenjian_ctrl_button").attr('class', '');
    $("#shenjian_ctrl_button").addClass('btn');
    if (status === 'starting') {
        $("#shenjian_ctrl_button").html("<i class='fas fa-sync-alt fa-spin'></i>&nbsp;启动中");
        $("#shenjian_ctrl_button").addClass('btn-warning');
        $("#shenjian_ctrl_button").prop("disabled",true);
    } else if (status === 'running') {
        $("#shenjian_ctrl_button").html("<i class='fas fa-power-off'></i>&nbsp;停止");
        $("#shenjian_ctrl_button").addClass('btn-danger');
        $("#shenjian_ctrl_button").prop("disabled",false);
    } else if(status === 'stopping') {
        $("#shenjian_ctrl_button").html("<i class='fas fa-sync-alt fa-spin'></i>&nbsp;停止中");
        $("#shenjian_ctrl_button").addClass('btn-warning');
        $("#shenjian_ctrl_button").prop("disabled",true);
    }else{
        $("#shenjian_ctrl_button").html("<i class=\"ti-control-play\"></i>&nbsp;启动");
        $("#shenjian_ctrl_button").addClass('btn-cyan');
        $("#shenjian_ctrl_button").prop("disabled",false);
    }
}
