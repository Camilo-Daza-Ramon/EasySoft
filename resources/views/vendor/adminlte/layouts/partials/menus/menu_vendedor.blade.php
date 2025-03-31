<ul class="sidebar-menu">            

  <!-- Authentication Links -->

  @if (Auth::guest())  

  @else
    <li><a href="{{url('home')}}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-users"></i> <span>Clientes</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="{{ url('clientes/create') }}"><i class='fa fa-file-text-o'></i>Vender</a>
        </li>
        <li>
          <a href="{{ url('clientes') }}"><i class='fa fa-edit'></i>Novedades</a>
        </li>
        <li>
          <a href="{{ url('clientes/importar/datos') }}"><i class='fa fa-file-text-o'></i>Importar</a>
        </li>
      </ul>
    </li>    
  @endif
</ul>