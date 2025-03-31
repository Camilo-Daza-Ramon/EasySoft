<ul class="sidebar-menu">            

  <!-- Authentication Links -->

  @if (Auth::guest())  

  @else
    <li><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>
    
    <li><a href="{{ url('proyectos') }}"><i class='fa fa-cubes'></i> <span>Proyectos</span></a></li>

    <li><a href="{{ url('clientes') }}"><i class='fa fa-users'></i> <span>Clientes</span></a></li>

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
      </ul>
    </li>

    <li><a href="{{ url('tickets') }}"><i class='fa fa-wrench'></i> <span>Tickets</span></a></li>
  @endif
</ul>