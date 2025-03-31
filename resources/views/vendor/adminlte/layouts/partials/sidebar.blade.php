<!-- Left side column. contains the logo and sidebar -->
<aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- search form (Optional) -->
        <form action="/home/" method="get" class="sidebar-form">
            <div class="input-group">
                <input type="text" name="cedula" class="form-control" placeholder="{{ trans('adminlte_lang::message.search') }}..." value="{{(isset($_GET['cedula'])? $_GET['cedula']:'')}}" autocomplete="off" />
              <span class="input-group-btn">
                <button type='submit' id='search-btn' class="btn btn-flat"><i class="fa fa-search"></i></button>
              </span>
            </div>
        </form>
        <!-- /.search form -->

         <!-- Sidebar Menu -->
        
            @include('adminlte::layouts.partials.menus.menu_admin')
        
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>
