$(document).ready(function(){
    $("#ajax-loader").load("/wp-content/plugins/members-search/members-info.php");
    
    $(".select-members").on('change', function(e){
        var v_members = $("#members").val();
        var v_states = $("#states").val();
        var v_countries = $("#countries").val();
        var v_reports = $("#reports").val();
        $("#ajax-loader").load("/wp-content/plugins/members-search/members-info.php?members="+v_members+"&states="+v_states+"&countries="+v_countries+"&reports="+v_reports);
    });
});

function closeMemberPanel(header, panel){
    $(header).removeClass('vc_active');
    $(panel).removeClass('vc_active');
}