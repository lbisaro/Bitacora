<style type="text/css">
  .data {
    font-weight: bolder;
  }
</style>
<div class="container">
  <div class="row">
    <div class="col">
      <h5 class="bd-title" >
        {{tag}}  
      </h5>
    </div>
  </div>
  <div class="row">
    <div class="col" style="text-align: right;">
      {{addButtons}}
    </div>
  </div>
</div>
<div class="container">
  <table class="table table-borderless table_data">
    <tr>
        <td>Paciente</td>
        <td class="data">{{paciente_ayn}}</td>
    </tr>
    <tr>
        <td>Prosional</td>
        <td class="data">{{profesional_ayn}}</td>
    </tr>
    <tr>
        <td>Fecha</td>
        <td class="data">{{fecha}}</td>
    </tr>
    <tr>
        <td>Registro del evento</td>
        <td class="data">{{username}} {{datetime}}</td>
    </tr>
  </table>
  <div class="row">
    <div class="col">
      <h5 class="bd-title" >
        Notas  
      </h5>
      <p>{{notas}}</p>
    </div>
  </div>
</div>
{{hidden}}

<script language="javascript" >

    $(document).ready( function () {
       
    });

    function regresar()
    {
        var url = $('#returnTo').val();
        goTo(url+'+idpaciente='+$('#idpaciente').val());
    }

    function eliminar()
    {
        if (confirm('Confirma eliminar definitivamente el evento?'))
        {
            CtrlAjax.sendCtrl("app","evento","eliminar","idpacientelog="+$('#idpacientelog').val()); 
        }
    }

    function editar()
    {
        goTo("app.evento.editar+idpacientelog="+$('#idpacientelog').val()); 
    }

</script>