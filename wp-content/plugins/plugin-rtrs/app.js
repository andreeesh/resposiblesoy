$(document).ready(function(){
    $("#ajax-loader").load("/wp-content/plugins/plugin-rtrs/members-info.php");
    $("#loader-tabla-documentos").load("/wp-content/plugins/plugin-rtrs/tabla-documentos.php");
    
    $(".select-members").on('change', function(e){
        var v_members = $("#members").val();
        var v_states = $("#states").val();
        var v_countries = $("#countries").val();
        var v_reports = $("#reports").val();
        $("#ajax-loader").load("/wp-content/plugins/plugin-rtrs/members-info.php?members="+v_members+"&states="+v_states+"&countries="+v_countries+"&reports="+v_reports);
    });

    $(".link-tipos").on('click', function(e){
        var id_tipo = $(this).attr("id").replace("tipo", "");
        $("#loader-tabla-documentos").load("/wp-content/plugins/plugin-rtrs/tabla-documentos.php?t="+id_tipo);
        e.preventDefault();
    });

    $("#cont_tipo_cerrado").on('click', function(e){
        $("#cont_tipo_cerrado").addClass("hidden");
        $("#cont_tipo_abierto").removeClass("hidden");
    });

    $("#cont_tipo_abierto").on('click', function(e){
        $("#cont_tipo_abierto").addClass("hidden");
        $("#cont_tipo_cerrado").removeClass("hidden");
    });

    $("#form-biblioteca").submit(function(e){
        $("#loader-tabla-documentos").load("/wp-content/plugins/plugin-rtrs/tabla-documentos.php?"+$("#form-biblioteca").serialize());
        e.preventDefault();
      });
});

function closeMemberPanel(header, panel){
    $(header).removeClass('vc_active');
    $(panel).removeClass('vc_active');
}