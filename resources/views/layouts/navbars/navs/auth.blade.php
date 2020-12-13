<nav class="navbar navbar-expand-lg navbar-absolute navbar-transparent navbar-expand-lg">
    <div class="container-fluid">
        <div class="navbar-wrapper d-none">
            <div class="navbar-toggle d-inline">
                <button class="minimize-sidebar btn btn-link btn-just-icon" rel="tooltip" data-original-title="Sidebar toggle" data-placement="right">
                    <i class="tim-icons icon-align-center visible-on-sidebar-regular"></i>
                    <i class="tim-icons icon-bullet-list-67 visible-on-sidebar-mini"></i>
                </button>
            </div>
            <a class="navbar-brand" href="#">{{ $page ?? __('Dashboard') }}</a>
        </div>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navigation" aria-expanded="false" aria-label="{{ __('Toggle navigation') }}">
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
            <span class="navbar-toggler-bar navbar-kebab"></span>
        </button>
        <div class="collapse navbar-collapse" id="navigation">
            <ul class="navbar-nav ml-auto">
                <li class="search-bar input-group">
                    <button class="btn btn-link" id="search-button" data-toggle="modal" data-target="#searchModal"><i class="tim-icons icon-zoom-split"></i>
                        <span class="d-lg-none d-md-block">{{ __('Search') }}</span>
                    </button>
                </li>
                <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <div class="notification d-none d-lg-block d-xl-block"></div>
                        <i class="tim-icons icon-sound-wave"></i>
                        <p class="d-lg-none"> {{ __('Opciones') }} </p>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-right dropdown-navbar">
                        @if(@Auth::user()->hasPermissionTo('product_permission'))
                        <li class="nav-link">
                            <a href="{{ route('products.view') }}" class="nav-item dropdown-item">{{ __('Productos') }}</a>
                        </li>
                        @endif
                        @if(@Auth::user()->hasPermissionTo('sales_permission'))
                        <li class="nav-link">
                            <a href="{{ route('sales.view') }}" class="nav-item dropdown-item">{{ __('Ventas por Mesa') }}</a>
                        </li>
                        @endif
                        @if(@Auth::user()->hasPermissionTo('income_permission'))
                        {{-- icon-bag-16 --}}
                        <li class="nav-link">
                            <a href="{{ route('income.view') }}" class="nav-item dropdown-item">{{ __('Ingreso de pedidos') }}</a>
                        </li>
                        @endif
                        @if(@Auth::user()->hasPermissionTo('sales_permission'))
                        <li class="nav-link">
                            <a href="{{ route('sales_run.view') }}" class="nav-item dropdown-item">{{ __('Venta rapida') }}</a>
                        </li>
                        @endif
                        @if(@Auth::user()->hasPermissionTo('document_permission'))
                        <li class="nav-link">
                            <a href="{{ route('documents.view') }}" class="nav-item dropdown-item">{{ __('Listado de Documentos') }}</a>
                        </li>
                        @endif
                        @if(@Auth::user()->hasPermissionTo('inventory_permission'))
                        <li class="nav-link">
                            <a href="{{ route('inventory.view') }}" class="nav-item dropdown-item">{{ __('Inventario') }}</a>
                        </li>
                        @endif
                        @if(@Auth::user()->hasPermissionTo('gastos_permission'))
                        <li class="nav-link">
                            <a href="{{ route('expense.view') }}" class="nav-item dropdown-item">{{ __('Gastos') }}</a>
                        </li>
                        @endif
                    </ul>
                </li>
                <li class="dropdown nav-item">
                    <a href="#" class="dropdown-toggle nav-link" data-toggle="dropdown">
                        <div class="photo">
                            <img src="{{ asset('black') }}/img/anime3.png" alt="{{ __('Profile Photo') }}">
                        </div>
                        <b class="caret d-none d-lg-block d-xl-block"></b>
                        <p class="d-lg-none">{{ __('Log out') }}</p>
                    </a>
                    <ul class="dropdown-menu dropdown-navbar">
                        <li class="nav-link">
                            <a href="#" class="nav-item dropdown-item">{{ __('Perfil') }}</a>
                        </li>
                        @if(@Auth::user()->hasPermissionTo('roles_permission'))
                        <li class="nav-link">
                            <a href="{{ route('roles.view') }}" class="nav-item dropdown-item">{{ __('Role y Permisos') }}</a>
                        </li>
                        @endif
                        @if(@Auth::user()->hasPermissionTo('gastos_permission'))
                        <li class="nav-link">
                            <a href="{{ route('expense_type.view') }}" class="nav-item dropdown-item">{{ __('Tipos de gasto') }}</a>
                        </li>
                        @endif
                        <li class="dropdown-divider"></li>
                        <li class="nav-link">
                            <a href="{{ route('logout') }}" class="nav-item dropdown-item" onclick="event.preventDefault();  document.getElementById('logout-form').submit();">{{ __('Cerrar sesión') }}</a>
                        </li>
                    </ul>
                </li>
                <li class="separator d-lg-none"></li>
            </ul>
        </div>
    </div>
</nav>
<div class="modal modal-search fade" id="searchModal" tabindex="-1" role="dialog" aria-labelledby="searchModal" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <input type="text" class="form-control" id="inlineFormInputGroup" placeholder="{{ __('SEARCH') }}">
                <button type="button" class="close" data-dismiss="modal" aria-label="{{ __('Close') }}">
                    <i class="tim-icons icon-simple-remove"></i>
              </button>
            </div>
        </div>
    </div>
</div>
