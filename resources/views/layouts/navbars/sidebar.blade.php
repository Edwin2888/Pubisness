<aside class="sidebar">
    <div class="sidebar-wrapper ps">
        <div class="logo">
            <a href="#" class="simple-text logo-mini mblack">{{ __('BD') }}</a>
            <a href="#" class="simple-text logo-normal mblack">{{ __('Black Dashboard') }}</a>
        </div>
        <ul class="nav">
            <li @if ($pageSlug == 'products') class="active " @endif>
                <a href="{{ route('products.view') }}" class="mblack">
                    <i class="tim-icons icon-atom"></i>
                    <p>{{ __('Productos') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'sales') class="active " @endif>
                <a href="{{ route('sales.view') }}" class="mblack">
                    <i class="tim-icons icon-money-coins"></i>
                    <p>{{ __('Ventas') }}</p>
                </a>
            </li>
            {{-- icon-bag-16 --}}
            <li @if ($pageSlug == 'income') class="active " @endif>
                <a href="{{ route('income.view') }}" class="mblack">
                    <i class="tim-icons icon-chart-pie-36"></i>
                    <p>{{ __('Ingreso de pedidos') }}</p>
                </a>
            </li>
            <li>
                <a data-toggle="collapse" href="#laravel-examples" aria-expanded="false">
                    <i class="fab fa-laravel" ></i>
                    <p class="nav-link-text" >{{ __('Laravel Examples') }}</p>
                    <b class="caret mt-1"></b>
                </a>

                <div class="collapse" id="laravel-examples">
                    <ul class="nav pl-4">
                        <li @if ($pageSlug == 'profile') class="active " @endif>
                            <a href="">
                                <i class="tim-icons icon-single-02"></i>
                                <p>{{ __('User Profile') }}</p>
                            </a>
                        </li>
                        <li @if ($pageSlug == 'users') class="active " @endif>
                            <a href="">
                                <i class="tim-icons icon-bullet-list-67"></i>
                                <p>{{ __('User Management') }}</p>
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
            <li @if ($pageSlug == 'notifications') class="active " @endif>
                <a href="">
                    <i class="tim-icons icon-bell-55"></i>
                    <p>{{ __('Notifications') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'tables') class="active " @endif>
                <a href="">
                    <i class="tim-icons icon-puzzle-10"></i>
                    <p>{{ __('Table List') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'typography') class="active " @endif>
                <a href="">
                    <i class="tim-icons icon-align-center"></i>
                    <p>{{ __('Typography') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'rtl') class="active " @endif>
                <a href="">
                    <i class="tim-icons icon-world"></i>
                    <p>{{ __('RTL Support') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'typography') class="active " @endif>
                <a href="">
                    <i class="tim-icons icon-align-center"></i>
                    <p>{{ __('Typography') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'rtl') class="active " @endif>
                <a href="">
                    <i class="tim-icons icon-world"></i>
                    <p>{{ __('RTL Support') }}</p>
                </a>
            </li>
            <li @if ($pageSlug == 'typography') class="active " @endif>
                <a href="">
                    <i class="tim-icons icon-align-center"></i>
                    <p>{{ __('Typography') }}</p>
                </a>
            </li>
        </ul>
    </div>
</aside>
