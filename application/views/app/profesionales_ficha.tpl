<style type="text/css">
  .data {
    font-weight: bolder;
  }

  .paciente_asignado {
    padding: 0px 3px 0px 13px;
  }


</style>
<div class="container">
  <div class="row">
    <div class="col">
      <h5 class="bd-title" >
        {{ayn}}  
      </h5>
    </div>
  </div>
  <div class="row">
    <div class="col" style="text-align: right;">
      <a class="btn btn-info btn-sm menu-admin" href="app.profesional.editar+idprofesional={{idprofesional}}">Editar</a>
        {{addButtons}}
    </div>
  </div>
</div>
<div class="container">
  <table class="table table-borderless table_data">
    <tr>
        <td>Mail</td>
        <td class="data">{{mail}}</td>
        <td>Telefono</td>
        <td class="data">{{telefono}}</td>
    </tr>
    <tr>
        <td>Cargo</td>
        <td class="data text-success">{{cargo}}</td>
        <td>Usuario asociado</td>
        <td class="data text-danger">{{usuario}}</td>
    </tr>
    <tr>
        <td>Pacientes asignados</td>
        <td class="data">{{pacientes_asignados}}</td>
    </tr>      
  </table>
</div>

{{log}}

<input type="hidden" name="idprofesional" id="idprofesional" value="{{idprofesional}}">

<script language="javascript" >

    $(document).ready( function () {

    });

    function addPaciente()
    {
        var idpaciente = $('#add_paciente').val();
        var ayn = $('#add_paciente option:selected').html();
        if (idpaciente)
        {
            if (confirm('Desea asignar al paciente '+ayn+' ?'))
                CtrlAjax.sendCtrl("app","profesional","addPaciente","idpaciente="+idpaciente);
        }
        $('#add_paciente option:selected').attr('selected',false);
    }

    function delPaciente(idpaciente)
    {
        var ayn = $('#paayn_'+idpaciente).text();
        if (idpaciente)
        {
            if (confirm('Desea quitar la asignacion del paciente '+ayn+' ?'))
                CtrlAjax.sendCtrl("app","profesional","delPaciente","idpaciente="+idpaciente);
        }
    }

</script>

