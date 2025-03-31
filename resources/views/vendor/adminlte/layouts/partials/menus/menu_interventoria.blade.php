<ul class="sidebar-menu">            

  <!-- Authentication Links -->

  @if (Auth::guest())  

  @else
    <li><a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a></li>

    <li class="treeview" style="height: auto;">
      <a href="#">
        <i class="fa fa-cubes"></i> <span>Modulos Interventoria</span>
        <span class="pull-right-container">
          <i class="fa fa-angle-left pull-right"></i>
        </span>
      </a>
      <ul class="treeview-menu" style="display: none;">
        <li>
          <a href="{{url('estudios-demanda')}}"><i class='fa fa-file-o'></i>Estudio de la Demanda</a>
        </li>

        <li>
          <a href="#"><i class='fa fa-barcode'></i>Inventarios</a>
        </li>

        <li class="treeview" style="height: auto;">
          <a href="#"><i class="fa fa-files-o"></i>Gestión Documental
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <li><a href="{{ url('clientes') }}"><i class="fa fa-circle-o"></i>Soportes Suscripciones</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i>Documentos de Planeación</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i>Soportes Facturación</a></li>
          </ul>
        </li>

        <li>
          <a href="{{ url('instalaciones') }}"><i class='fa fa-hdd-o'></i>Instalaciones</a>
        </li>

        <li class="treeview" style="height: auto;">
          <a href="#"><i class="fa fa-list-ul"></i>Operación
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <li><a href="#"><i class="fa fa-circle-o"></i>Usuarios</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i>Comportamiento de Red</a></li>
          </ul>
        </li>

        <li class="treeview" style="height: auto;">
          <a href="#"><i class="fa fa-wrench"></i>PQRs y Mantenimiento
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <li><a href="#"><i class="fa fa-circle-o"></i>PQRs</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i>Mantenimientos Preventivos</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i>Mantenimientos Correctivos</a></li>
          </ul>
        </li>

        <li class="treeview" style="height: auto;">
          <a href="#"><i class="fa fa-bar-chart"></i>Indicadores
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu" style="display: none;">
            <li><a href="#"><i class="fa fa-circle-o"></i>Disponibilidad</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i>Velocidad de Navegación</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i>% Utilización del A.B</a></li>
            <li><a href="#"><i class="fa fa-circle-o"></i>Calidad en la A.U</a></li>
          </ul>
        </li>

      </ul>
    </li>

  @endif
</ul>