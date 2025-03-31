<ul class="sidebar-menu">            

  <!-- Authentication Links -->

  @if (Auth::guest())  

  @else
    <li><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

    <li><a href="{{ url('proyectos') }}"><i class='fa fa-cubes'></i> <span>Proyectos</span></a></li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-users"></i> <span>Clientes</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="{{ url('clientes') }}"><i class='fa fa-list-ul'></i>Listar</a>
        </li>
        <li>
          <a href="{{ url('cambios-reemplazos') }}"><i class='fa fa-retweet'></i>Cambios y Reemplazos</a>
        </li>
      </ul>
    </li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-smile-o"></i> <span>Atencion al Cliente</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="{{ url('atencion-clientes') }}"><i class='fa fa-list-ul'></i>Listar</a>
        </li>
        <li>
          <a href="{{ url('atencion-clientes/create') }}"><i class='fa fa-plus-circle'></i>Atender</a>
        </li>
        <li>
          <a href="{{ url('respuestas') }}"><i class='fa fa-thumbs-o-up'></i>Respuestas</a>
        </li>
        <li>
          <a href="{{ url('atencion-clientes/estadisticas') }}"><i class='fa fa-pie-chart'></i>Estadisticas</a>
        </li>
      </ul>
    </li>

    <li><a href="{{ url('instalaciones') }}"><i class='fa fa-hdd-o'></i> <span>Instalaciones</span></a></li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-file-text-o"></i> <span>Facturación</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="{{ url('facturacion') }}"><i class='fa fa-file-text-o'></i>Facturas</a>
        </li>        
      </ul>
    </li>

  @endif
</ul>