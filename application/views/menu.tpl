<script language="javascript" >

    {{server_entorno}}

    $(document).ready( function () {

        //jsMenuAdmin
        {{jsMenuAdmin}}

        
        
    
    });

</script>

<nav class="navbar fixed-top navbar-expand-lg navbar-default ">
  <a class="navbar-brand mb-2" href="app.Mst.home+">
    <img src="public/images/vxm_menu.png" style="width: 36px;" alt="Estado de cuenta">
  </a>
  <span class="navbar-brand" >{{title}}</span>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="sr-only  rounded" >Toggle navigation</span>
      <span class="glyphicon glyphicon-menu-hamburger text-white"></span>
  </button>

      
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    
    <ul class="navbar-nav ml-auto ">
        
        {{mainMenu}}
    
        <li class="nav-item">
          <a class="nav-link rounded" href="app.paciente.listar+"><span class="glyphicon glyphicon-folder-open"></span> Pacientes</a>
        </li>        
        <li class="nav-item">
          <a class="nav-link rounded" href="app.evento.home+"><span class="glyphicon glyphicon-th-list"></span> Eventos</a>
        </li>        
        <li class="nav-item menu-admin">
          <a class="nav-link rounded" href="app.profesional.listar+"><span class="glyphicon glyphicon-education"></span> Profesionales</a>
        </li>        
        <li class="nav-item menu-admin">
          <a class="nav-link rounded" href="app.Mst.tags+"><span class="glyphicon glyphicon-tags"></span> Tags</a>
        </li>         
        <li class="nav-item menu-admin">
          <a class="nav-link rounded" href="usr.Usr.usuarios+"><span class="glyphicon glyphicon-user"></span> Usuarios</a>
        </li>         
        <li class="nav-item">
          <a class="nav-link rounded" href="usr.Usr.perfil+"><span class="glyphicon glyphicon-cog"></span> Perfil</a>
        </li>
        <li class="nav-item">
          <a class="nav-link rounded" href="usr.Usr.logout+"><span class="glyphicon glyphicon-log-out"></span> Salir</a>
        </li>
    </ul>

  </div>
</nav>

  