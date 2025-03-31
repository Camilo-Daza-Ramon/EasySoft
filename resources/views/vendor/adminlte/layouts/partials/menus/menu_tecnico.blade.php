<ul class="sidebar-menu">            

  <!-- Authentication Links -->

  @if (Auth::guest())  

  @else
    <li><a href="{{url('home')}}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

    <li class="treeview">
      <a href="#">
        <i class="fa fa-hdd-o"></i> <span>Instalaciones</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu">
        <li>
          <a href="{{ route('instalaciones.instalar') }}"><i class='fa fa-users'></i>Instalar</a>
        </li> 
        <li>
          <a href="{{ url('instalaciones') }}"><i class='fa fa-edit'></i>Novedades</a>
        </li>
        <li>
          <a href="{{ url('instalaciones/importar/datos') }}"><i class='fa fa-file-text-o'></i>Importar</a>
        </li>
      </ul>
    </li>    
  @endif
</ul>