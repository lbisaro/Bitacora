<style type="text/css">
  .data {
    font-weight: bolder;
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
      <a class="btn btn-success btn-sm menu-admin" href="app.paciente.addLog+idpaciente={{idpaciente}}">Registrar evento</a>
      <a class="btn btn-info btn-sm menu-admin" href="app.paciente.editar+idpaciente={{idpaciente}}">Editar</a>
        {{addButtons}}
    </div>
  </div>
</div>
<div class="container">
  <table class="table table-borderless table_data">
    <tr>
        <td>Fecha de alta</td>
        <td class="data text-success">{{fecha_alta}}</td>
        <td>Fecha de baja</td>
        <td class="data text-danger">{{fecha_baja}}</td>
    </tr>
    <tr>
        <td>Mail</td>
        <td class="data">{{mail}}</td>
        <td>Telefono</td>
        <td class="data">{{telefono}}</td>
    </tr>
  </table>
</div>

{{log}}

<input type="hidden" name="idpaciente" id="idpaciente" value="{{idpaciente}}">

<script language="javascript" >

    $(document).ready( function () {

    });

</script>

