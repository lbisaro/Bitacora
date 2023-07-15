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
  </table>
</div>

{{log}}

<input type="hidden" name="idprofesional" id="idprofesional" value="{{idprofesional}}">

<script language="javascript" >

    $(document).ready( function () {

    });

</script>

