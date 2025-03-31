<ul class="sidebar-menu">

  <!-- Authentication Links -->

  @if (Auth::guest())

  @else
    <li><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

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
          <a href="{{ url('solicitudes') }}"><i class='fa fa-commenting-o'></i>Solicitudes</a>
        </li>
      </ul>
    </li>
  @endif
</ul>
