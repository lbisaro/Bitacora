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
      {{addButtons}}
      <a class="btn btn-primary btn-sm menu-admin" href="usr.usr.editar+idusuario={{idusuario}}">Editar</a>
      <button class="btn btn-secondary btn-sm menu-admin" onclick="resetPassword({{idusuario}})">Resetear Password</button>
        
    </div>
  </div>
</div>
<div class="container">
  <table class="table table-borderless table_data">
    <tr>
        <td width="25%">Nombre de usuario</td>
        <td class="data">{{username}}</td>
    </tr>
    <tr>
        <td>Mail</td>
        <td class="data">{{mail}}</td>
    </tr>
    <tr>
        <td>Profesional asociado</td>
        <td class="data text-success">{{profesional}}</td>
    </tr>
  </table>
</div>

{{log}}

<input type="hidden" name="idusuario" id="idusuario" value="{{idusuario}}">

<script language="javascript" >

    $(document).ready( function () {

    });


    function resetPassword(idusuario)
    {
        CtrlAjax.sendCtrl("usr","usr","resetPassword","idusuario="+idusuario);    
    }
</script>

