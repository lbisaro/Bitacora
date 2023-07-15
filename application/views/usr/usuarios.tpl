{{divPop}}
{{actionBar}}
<div class="container">
  <div class="row">
    <div class="col" style="text-align: right;">
      <a class="btn btn-primary btn-sm menu-admin" href="usr.usr.editar+">Crear nuevo</a>
    </div>
  </div>
</div>
<br/>
{{data}}
{{tabs}}
{{hidden}}
<div id="PopUpContainer" ></div>

<script language="javascript" >

    $(document).ready( function () {

    });


    function toogleBlock(idusuario)
    {
        CtrlAjax.sendCtrl("usr","usr","toogleBlock","idusuario="+idusuario);    
    }

    function editar(idusuario)
    {
        goTo("usr.usr.editar+idusuario="+idusuario);    
    }

    function ficha(idusuario)
    {
        goTo("usr.usr.ficha+idusuario="+idusuario);    
    }

    function creaProfesional(idusuario)
    {
        goTo("app.profesional.editar+idusuario="+idusuario);    
    }
   



</script>
