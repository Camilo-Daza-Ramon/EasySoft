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
        <i class="fa fa-file-text-o"></i> <span>Facturación</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="{{ url('facturacion') }}"><i class='fa fa-file-text-o'></i>Facturas</a>
        </li>
        <li>
          <a href="{{ url('notas') }}"><i class='fa fa-sticky-note-o'></i>Notas</a>
        </li>
        <li>
          <a href="{{ url('recaudos') }}"><i class='fa fa-dollar'></i>Recaudos</a>
        </li>
        <li>
          <a href="{{ url('novedades') }}"><i class='fa fa-edit'></i>Novedades</a>
        </li>
        <li>
          <a href="{{ url('cartera') }}"><i class='fa fa-balance-scale'></i>Cartera</a>
        </li>
        <li>
          <a href="{{ url('estadisticas') }}"><i class='fa fa-pie-chart'></i> <span>Estadisticas</span></a>
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
      </ul>
    </li> 
  @endif
</ul>