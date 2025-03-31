<ul class="sidebar-menu">

    <!-- Authentication Links -->

    @if (Auth::guest())

    @else
    <li>
        <a href="{{ url('home') }}"><i class='fa fa-home'></i> <span>Inicio</span></a>
    </li>

    @permission('proyectos-listar')
    <li>
        <a href="{{ url('proyectos') }}"><i class='fa fa-cubes'></i> <span>Proyectos</span></a>
    </li>
    @endpermission

    @permission('inventarios-listar')
    <li class="treeview">
        <a href="#">
            <i class="fa fa-list-ul"></i> <span>Inventarios</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <!--<li>
            <a href="url('inventarios/proveedores')"><i class='fa fa-industry'></i>Proveedores</a>
          </li>
          <li>
            <a href="url('inventarios/bodegas')"><i class='fa fa-home'></i>Bodegas</a>
          </li>-->
            <li>
                <a href="{{ url('inventarios/insumos') }}"><i class='fa fa-list-ul'></i>Insumos</a>
            </li>
        </ul>
    </li>
    @endpermission

    @permission(['clientes-listar',
    'cambios-reemplazos-listar','contratos-listar','aprovisionar-listar','clientes-suspensiones-listar','clientes-restricciones-listar',
    'auditorias-listar'])
    <li class="treeview">
        <a href="#">
            <i class="fa fa-users"></i> <span>Clientes</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @permission('clientes-listar')
            <li>
                <a href="{{ url('clientes') }}"><i class='fa fa-list-ul'></i>Listar</a>
            </li>
            @endpermission
            @permission('auditorias-listar')
            <li>
                <a href="{{ url('auditorias/clientes') }}"><i class='fa fa-user-secret'></i>Auditar</a>
            </li>
            @endpermission
            @role('vendedor')
            <li>
                <a href="{{ url('clientes/create') }}"><i class='fa fa-file-text-o'></i>Vender</a>
            </li>
            <li>
                <a href="{{ url('clientes/importar/datos') }}"><i class='fa fa-file-text-o'></i>Importar</a>
            </li>
            @endrole
            @permission('cambios-reemplazos-listar')
            <li>
                <a href="{{ url('cambios-reemplazos') }}"><i class='fa fa-retweet'></i>Cambios y Reemplazos</a>
            </li>
            @endpermission
            @permission('contratos-listar')
            <li>
                <a href="{{ url('contratos') }}"><i class='fa fa-file'></i>Contratos</a>
            </li>
            @endpermission

            @permission('metas-clientes-listar')
            <li>
                <a href="{{ url('metas-clientes') }}"><i class='fa fa-tag'></i>Metas</a>
            </li>
            @endpermission

            @permission('aprovisionar-listar')
            <li>
                <a href="{{ url('aprovisionar') }}"><i class='fa fa-hdd-o'></i>Aprovisionar</a>
            </li>
            @endpermission
            @permission('clientes-suspensiones-listar')
            <li>
                <a href="{{ url('clientes-suspensiones') }}"><i class='fa fa-ban'></i>Suspensiones</a>
            </li>
            @endpermission
            @permission('clientes-restricciones-listar')
            <li>
                <a href="{{ url('clientes/restricciones') }}"><i class='fa fa-ban'></i>Restricciones</a>
            </li>
            @endpermission
        </ul>
    </li>
    @endpermission

    @permission(['atencion-clientes-listar','atencion-clientes-crear','solicitudes-listar','encuestas-listar','puntos-atencion-listar','encuestas-respuestas-listar','atencion-clientes-estadisticas'])
    <li class="treeview">
        <a href="#">
            <i class="fa fa-smile-o"></i> <span>Atencion al Cliente</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @permission('atencion-clientes-listar')
            <li>
                <a href="{{ url('atencion-clientes') }}"><i class='fa fa-list-ul'></i>Listar</a>
            </li>
            @endpermission
            @permission('atencion-clientes-crear')
            <li>
                <a href="{{ url('atencion-clientes/create') }}"><i class='fa fa-plus-circle'></i>Atender</a>
            </li>
            @endpermission
            @permission('solicitudes-listar')
            <li>
                <a href="{{ url('solicitudes') }}"><i class='fa fa-commenting-o'></i>Solicitudes</a>
            </li>
            @endpermission

            @permission('suspensiones-temporales-listar')
            <li>
                <a href="{{ url('suspensiones-temporales') }}"><i class='fa fa-chain-broken'></i>Suspensiones
                    Temporales</a>
            </li>
            @endpermission

            @permission('encuestas-listar')
            <li>
                <a href="{{ url('encuestas') }}"><i class='fa fa-question-circle'></i>Encuestas</a>
            </li>
            @endpermission
            @permission('puntos-atencion-listar')
            <li>
                <a href="{{ url('puntos-atencion') }}"><i class='fa fa-map-marker'></i>Puntos de Atención</a>
            </li>
            @endpermission
            @permission('encuestas-respuestas-listar')
            <li>
                <a href="{{ url('encuestas-respuestas') }}"><i class='fa fa-thumbs-o-up'></i>Respuestas</a>
            </li>
            @endpermission
            @permission('atencion-clientes-estadisticas')
            <li>
                <a href="{{ url('atencion-clientes/estadisticas') }}"><i class='fa fa-pie-chart'></i>Estadisticas</a>
            </li>
            @endpermission
        </ul>
    </li>
    @endpermission


    @permission('pqrs-listar')
    <li>
        <a href="{{ url('pqr') }}"><i class='fa fa-comments-o'></i> <span>PQRs</span></a>
    </li>
    @endpermission

    @permission(['instalaciones-listar','instalaciones-infraestructura-listar'])
    <li class="treeview">
        <a href="#">
            <i class="fa fa-hdd-o"></i> <span>Instalaciones</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @permission('instalaciones-listar')
            <li>
                <a href="{{ url('instalaciones') }}"><i class='fa fa-users'></i> <span>Clientes</span></a>
            </li>
            @endpermission
            @permission('instalaciones-infraestructura-listar')
            <li>
                <a href="{{ url('instalaciones/index/infraestructura') }}"><i class='fa fa fa-desktop'></i>Infraestructura</a>
            </li>
            @endpermission
        </ul>
    </li>
    @endpermission
    
    @role('tecnico')
    <li>
        <a href="{{ route('instalaciones.instalar') }}"><i class='fa fa-users'></i>Instalar</a>
    </li>
    <li>
        <a href="{{ url('instalaciones/importar/datos') }}"><i class='fa fa-file-text-o'></i>Importar</a>
    </li>
    @endrole

    @permission('campañas-listar')
    <li>
        <a href="{{ url('campanas') }}"><i class='fa fa-bullhorn '></i> <span>Campañas</span></a>
    </li>
    @endpermission

    @permission('acuerdos-pago-listar')
    <li>
        <a href="{{ url('acuerdos') }}"><i class='fa fa-check-square-o'></i> <span>Acuerdos de Pago</span></a>
    </li>
    @endpermission



    @permission(['facturacion-listar','facturacion-notas-listar','recaudos-listar','novedades-listar','cartera-listar','facturacion-estadisticas-ver'])
    <li class="treeview">
        <a href="#">
            <i class="fa fa-file-text-o"></i> <span>Facturación</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @permission('facturacion-listar')
            <li>
                <a href="{{ url('facturacion') }}"><i class='fa fa-file-text-o'></i>Facturas</a>
            </li>
            @endpermission
            @permission('facturacion-notas-listar')
            <li>
                <a href="{{ url('notas') }}"><i class='fa fa-sticky-note-o'></i>Notas</a>
            </li>
            @endpermission
            @permission('recaudos-listar')
            <li>
                <a href="{{ url('recaudos') }}"><i class='fa fa-dollar'></i>Recaudos</a>
            </li>
            @endpermission
            @permission('novedades-listar')
            <li>
                <a href="{{ url('novedades') }}"><i class='fa fa-edit'></i>Novedades</a>
            </li>
            @endpermission
            @permission('cartera-listar')
            <li>
                <a href="{{ url('cartera') }}"><i class='fa fa-balance-scale'></i>Cartera</a>
            </li>
            @endpermission
            @permission('facturacion-estadisticas-ver')
            <li>
                <a href="{{ url('estadisticas') }}"><i class='fa fa-pie-chart'></i> <span>Estadisticas</span></a>
            </li>
            @endpermission
        </ul>
    </li>
    @endpermission

    @permission('proveedores-listar')
      <li>
        <a href="{{ url('proveedores') }}"><i class='fa fa-building-o'></i> <span>Proveedores</span></a>
      </li>
    @endpermission

    @permission('infraestructura-listar')
      <li>
        <a href="{{ url('infraestructuras') }}"><i class='fa fa-desktop'></i> <span>Infraestructuras</span></a>
      </li>
    @endpermission

    @permission(['olts-listar', 'gestion-red-listar', 'reporte-onts-listar'])
    <li class="treeview">
        <a href="#">
            <i class="fa fa-connectdevelop"></i> <span>Modulo de Red</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            <!--<li>
            <a href="#"><i class='fa fa-map-signs'></i>Nodos</a>
          </li>-->
            @permission('olts-listar')
            <li>
                <a href="{{url('red/olts')}}"><i class='fa fa-server'></i>OLTs</a>
            </li>
            @endpermission

            @permission('gestion-red-listar')
            <li>
                <a href="{{url('red/gestion')}}"><i class='fa fa-internet-explorer'></i>Gestion de Red</a>
            </li>
            @endpermission

            @permission('reporte-onts-listar')
            <li>
                <a href="{{url('red/reporte/onts')}}"><i class='fa fa-exclamation-triangle'></i>Reporte ONTs
                    Fallidas</a>
            </li>
            @endpermission

        </ul>
    </li>
    @endpermission

    @permission(['mantenimientos-listar','tickets-listar', 'mantenimientos-preventivos-listar'])
    <li class="treeview">
        <a href="#">
            <i class="fa fa-wrench"></i> <span>Soporte Tecnico</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @permission(['mantenimientos-listar', 'mantenimientos-preventivos-listar'])
            <li class="treeview" style="height: auto;">
                <a href="#"><i class="fa fa-wrench"></i>Mantenimientos
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu" style="display: none;">
                    <!--<li><a href="#"><i class="fa fa-circle-o"></i>Preventivos</a></li>-->
                    @permission('mantenimientos-listar')
                    <li><a href="{{ url('mantenimientos/correctivos') }}"><i class="fa fa-wrench"></i>Correctivos</a></li>
                    @endpermission
                    @permission('mantenimientos-preventivos-listar')
                    <li><a href="{{ url('mantenimientos/preventivos') }}"><i class="fa fa-calendar-check-o"></i>Preventivos</a></li>
                    @endpermission

                </ul>
            </li>
            @endpermission
            @permission('tickets-listar')
            <li><a href="{{ url('tickets') }}"><i class='fa fa-ticket'></i> <span>Tickets</span></a></li>
            @endpermission
        </ul>
    </li>
    @endpermission

    @permission(['usuarios-listar', 'roles-listar', 'permisos-listar'])
    <!----USUARIOS---->
    <li class="treeview">
        <a href="#">
            <i class="fa fa-users"></i> <span>Usuarios</span>
            <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
            </span>
        </a>
        <ul class="treeview-menu">
            @permission('usuarios-listar')
            <li>
                <a href="{{ url('usuarios') }}"><i class='fa fa-list'></i>Listar</a>
            </li>
            @endpermission

            @permission('roles-listar')
            <li>
                <a href="{{ route('entrust-gui::roles.index') }}"><i class='fa fa-key'></i>Roles</a>
            </li>
            @endpermission

            @permission('permisos-listar')
            <li>
                <a href="{{ route('entrust-gui::permissions.index') }}"><i class='fa fa-wrench'></i>Permisos</a>
            </li>
            @endpermission
        </ul>
    </li>
    @endpermission
    @endif
</ul>