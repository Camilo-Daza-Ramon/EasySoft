<ul class="sidebar-menu">

  <!-- Authentication Links -->

  @if (Auth::guest())

  @else
    <li><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-list-ul"></i> <span>Inventarios</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="{{ url('inventarios/proveedores') }}"><i class='fa fa-industry'></i>Proveedores</a>
        </li>
        <li>
          <a href="{{ url('inventarios/bodegas') }}"><i class='fa fa-home'></i>Bodegas</a>
        </li>
        <li>
          <a href="{{ url('inventarios/insumos') }}"><i class='fa fa-list-ul'></i>Insumos</a>
        </li>
      </ul>
    </li>

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
          <a href="{{ url('solicitudes') }}"><i class='fa fa-commenting-o'></i>Solicitudes</a>
        </li>
      </ul>
    </li>

    <li><a href="{{ url('pqr') }}"><i class='fa fa-comments-o'></i> <span>PQRs</span></a></li>

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
          <a href="{{ url('novedades') }}"><i class='fa fa-edit'></i>Novedades</a>
        </li>
      </ul>
    </li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-connectdevelop"></i> <span>Modulo de Red</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="#"><i class='fa fa-map-signs'></i>Nodos</a>
        </li>
        <li>
          <a href="{{url('red/olts')}}"><i class='fa fa-server'></i>OLTs</a>
        </li>
      </ul>
    </li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-wrench"></i> <span>Soporte Tecnico</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li class="treeview" style="height: auto;">
          <a href="#"><i class="fa fa-wrench"></i>Mantenimientos
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <li><a href="#"><i class="fa fa-circle-o"></i>Preventivos</a></li>
            <li><a href="{{ url('mantenimientos') }}"><i class="fa fa-circle-o"></i>Correctivos</a></li>
          </ul>
        </li>
        <li><a href="{{ url('tickets') }}"><i class='fa fa-ticket'></i> <span>Tickets</span></a></li>
      </ul>
    </li>

  @endif
</ul>
