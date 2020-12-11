<aside class="sidebar">
    <div class="sidebar-wrapper ps">
        <div class="logo">
            <a href="#" class="simple-text logo-mini mblack">{{ __('BD') }}</a>
            <a href="#" class="simple-text logo-normal mblack">{{ config('app.name', 'Black Dashboard') }}</a>
        </div>
        <ul class="nav">
            @if(@Auth::user()->hasPermissionTo('product_permission'))
            <li @if ($pageSlug == 'products') class="active " @endif>
                <a href="{{ route('products.view') }}" class="mblack">
                    <i class="tim-icons icon-atom"></i>
                    <p>{{ __('Productos') }}</p>
                </a>
            </li>
            @endif
            @if(@Auth::user()->hasPermissionTo('sales_permission'))
            <li @if ($pageSlug == 'sales') class="active " @endif>
                <a href="{{ route('sales.view') }}" class="mblack">
                    <i class="tim-icons icon-money-coins"></i>
                    <p>{{ __('Ventas por Mesa') }}</p>
                </a>
            </li>
            @endif
            @if(@Auth::user()->hasPermissionTo('income_permission'))
            {{-- icon-bag-16 --}}
            <li @if ($pageSlug == 'income') class="active " @endif>
                <a href="{{ route('income.view') }}" class="mblack">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Ingreso de pedidos') }}</p>
                </a>
            </li>
            @endif
            @if(@Auth::user()->hasPermissionTo('sales_permission'))
            <li @if ($pageSlug == 'sales_run') class="active " @endif>
                <a href="{{ route('sales_run.view') }}" class="mblack">
                    <i class="tim-icons icon-bag-16"></i>
                    <p>{{ __('Venta rapida') }}</p>
                </a>
            </li>
            @endif
            @if(@Auth::user()->hasPermissionTo('document_permission'))
            <li @if ($pageSlug == 'documents') class="active " @endif>
                <a href="{{ route('documents.view') }}" class="mblack">
                    <i class="tim-icons icon-notes"></i>
                    <p>{{ __('Listado de Documentos') }}</p>
                </a>
            </li>
            @endif
            @if(@Auth::user()->hasPermissionTo('inventory_permission'))
            <li @if ($pageSlug == 'inventory') class="active " @endif>
                <a href="{{ route('inventory.view') }}" class="mblack">
                    <i class="tim-icons icon-settings"></i>
                    <p>{{ __('Inventario') }}</p>
                </a>
            </li>
            @endif
        </ul>
    </div>
</aside>
