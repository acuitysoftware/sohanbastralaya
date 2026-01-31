<div class="leftside-menu leftside-menu-detached">

    <div class="leftbar-user">
        <a href="javascript: void(0);">
            <!-- <img src="assets/images/users/avatar-1.jpg" alt="user-image" height="42" class="rounded-circle shadow-sm"> -->
            <span class="leftbar-user-name">Admin</span>
        </a>
    </div>
    @php
        $user_type = Auth::user()->type == 'A' ? 'admin' : 'user';
        $user = Auth::user();
    @endphp
    <!--- Sidemenu -->
    @if ($user_type == 'admin')
        <ul class="side-nav">
            <li class="side-nav-item">
                <a href="{{ route('dashboard') }}" class="side-nav-link">
                    <i class="dripicons-home"></i>
                    <span>Dashboard</span>
                </a>

            </li>


            <li class="side-nav-item {{ Route::is('sub_admin_orders.view') ? 'menuitem-active' : '' }}">
                <a data-bs-toggle="collapse" href="#sba5" aria-expanded="false" aria-controls="sba5"
                    class="side-nav-link">
                    <i class="mdi mdi-order-alphabetical-ascending"></i>
                    <span>Orders</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ Route::is('sub_admin_orders.view') ? 'show' : '' }}" id="sba5">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('order_list') }}">Order List</a></li>
                        <li><a href="{{ route('order_report') }}">Daily Order</a></li>
                        <li class="{{ Route::is('sub_admin_orders.view') ? 'menuitem-active' : '' }}"><a
                                href="{{ route('sub_admin_report') }}"
                                class="{{ Route::is('sub_admin_orders.view') ? 'active' : '' }}">Sub Admin Orders</a></li>
                    </ul>
                </div>
            </li>

            <li
                class="side-nav-item {{ Route::is('add_to_cart') ? 'menuitem-active' : '' }} {{ Route::is('product.order') ? 'menuitem-active' : '' }}">
                <a data-bs-toggle="collapse" href="#sba3" aria-expanded="false" aria-controls="sba3"
                    class="side-nav-link">
                    <i class="uil-briefcase"></i>
                    <span>Product</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ Route::is('add_to_cart') ? 'show' : '' }}{{ Route::is('product.order') ? 'show' : '' }}"
                    id="sba3">
                    <ul class="side-nav-second-level">
                        <li
                            class="{{ Route::is('add_to_cart') ? 'menuitem-active' : '' }} {{ Route::is('product.order') ? 'menuitem-active' : '' }}">
                            <a href="{{ route('product_index') }}"
                                class="{{ Route::is('add_to_cart') ? 'active' : '' }} {{ Route::is('product.order') ? 'active' : '' }}">Product
                                List</a></li>
                        <li><a href="{{ route('limited_product') }}">Limited Stock</a></li>
                        <li><a href="{{ route('out_of_product') }}">Out of Stock</a></li>
                        <li><a href="{{ route('upload_product_image') }}">No Image Product</a></li>
                    </ul>
                </div>
            </li>



            <li
                class="side-nav-item {{ Route::is('customer_view') ? 'menuitem-active' : '' }} {{ Route::is('customer.details') ? 'menuitem-active' : '' }}">
                <a data-bs-toggle="collapse" href="#sba6" aria-expanded="false" aria-controls="sba6"
                    class="side-nav-link">
                    <i class="uil uil-user"></i>
                    <span>Customer Mgt</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ Route::is('customer_view') ? 'show' : '' }} {{ Route::is('customer.details') ? 'show' : '' }}"
                    id="sba6">
                    <ul class="side-nav-second-level">
                        <li class="{{ Route::is('customer_view') ? 'menuitem-active' : '' }}"><a
                                href="{{ route('customer_list') }}">Customer List</a></li>
                        <li><a href="{{ route('customer_card_list') }}">Membership Card List </a></li>
                        <li class="{{ Route::is('customer.details') ? 'menuitem-active' : '' }}"><a
                                href="{{ route('customer.index') }}">Customer’s Total Order</a></li>
                    </ul>
                </div>
            </li>

            <li class="side-nav-item {{ Route::is('loginDetails') ? 'menuitem-active' : '' }}">
                <a data-bs-toggle="collapse" href="#sba4" aria-expanded="false" aria-controls="sba4"
                    class="side-nav-link">
                    <i class="mdi mdi-account-supervisor-outline"></i>
                    <span>User Management</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse {{ Route::is('loginDetails') ? 'show' : '' }}" id="sba4">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('sub-admins.index') }}">User List</a></li>
                        <li class="{{ Route::is('loginDetails') ? 'menuitem-active' : '' }}"><a
                                href="{{ route('loginDetails') }}">User Log </a></li>
                    </ul>
                </div>
            </li>





            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sba7" aria-expanded="false" aria-controls="sba7"
                    class="side-nav-link">
                    <i class="dripicons-box"></i>
                    <span>Reports</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sba7">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('product_report') }}">Purchase Report</a></li>
                        <li><a href="{{ route('stock_report') }}">Stock Report</a></li>
                        <li><a href="{{ route('stock_inserted') }}">Insert Product Report</a></li>
                        <li><a href="{{ route('product_selling_order') }}">Qnt Sold by Product</a></li>
                        <li><a href="{{ route('purchase_report') }}">Expenses </a></li>
                        <li><a href="{{ route('user_purchase_report') }}">Purchase Report by User</a></li>
                    </ul>
                </div>
            </li>


            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sba1" aria-expanded="false" class="side-nav-link">
                    <i class="dripicons-gear"></i>
                    <span>Site Settings</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sba1">
                    <ul class="side-nav-second-level">
                        <li><a href="{{ route('site.settings') }}">Admin Setting</a></li>
                        <li><a href="{{ route('change_password') }}">Change Password</a></li>
                    </ul>
                </div>
            </li>
        </ul>
    @else
        <ul class="side-nav">
            <li class="side-nav-item">
                <a href="{{ route('dashboard') }}" class="side-nav-link">
                    <i class="dripicons-home"></i>
                    <span>Dashboard</span>
                </a>

            </li>


            <!-- @if (in_array('godown-management', $user->permissions()->pluck('permission')->toArray()))
            <li class="side-nav-item">
                <a data-bs-toggle="collapse" href="#sba2" aria-expanded="false" aria-controls="sba2" class="side-nav-link">
                    <i class="uil-store"></i>
                    <span>Godown Management</span>
                    <span class="menu-arrow"></span>
                </a>
                <div class="collapse" id="sba2">
                    <ul class="side-nav-second-level">
                        @if (in_array('godown-stock', $user->permissions()->pluck('permission')->toArray()))
<li><a href="#">Godown Stock</a></li>
@endif
                    </ul>
                </div>
            </li>
            @endif -->




            @if (in_array('order-management', $user->permissions()->pluck('permission')->toArray()))
                <li class="side-nav-item {{ Route::is('sub_admin_orders.view') ? 'menuitem-active' : '' }}">
                    <a data-bs-toggle="collapse" href="#sba5" aria-expanded="false" aria-controls="sba5"
                        class="side-nav-link">
                        <i class="mdi mdi-order-alphabetical-ascending"></i>
                        <span>Orders</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ Route::is('sub_admin_orders.view') ? 'show' : '' }}" id="sba5">
                        <ul class="side-nav-second-level">
                            @if (in_array('order-list', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('order_list') }}">Order List</a></li>
                            @endif
                            @if (in_array('daily-order-report', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('order_report') }}">Daily Order</a></li>
                            @endif
                            @if (in_array('sub-admin-orders', $user->permissions()->pluck('permission')->toArray()))
                                <li class="{{ Route::is('sub_admin_orders.view') ? 'menuitem-active' : '' }}""><a
                                        href="{{ route('sub_admin_report') }}"
                                        class="{{ Route::is('sub_admin_orders.view') ? 'active' : '' }}">Sub Admin
                                        Orders</a></li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if (in_array('product-management', $user->permissions()->pluck('permission')->toArray()))
                <li
                    class="side-nav-item {{ Route::is('add_to_cart') ? 'menuitem-active' : '' }} {{ Route::is('product.order') ? 'menuitem-active' : '' }}">
                    <a data-bs-toggle="collapse" href="#sba3" aria-expanded="false" aria-controls="sba3"
                        class="side-nav-link">
                        <i class="uil-briefcase"></i>
                        <span>Product</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ Route::is('add_to_cart') ? 'show' : '' }}{{ Route::is('product.order') ? 'show' : '' }}"
                        id="sba3">
                        <ul class="side-nav-second-level">
                            @if (in_array('product-list', $user->permissions()->pluck('permission')->toArray()))
                                <li
                                    class="{{ Route::is('add_to_cart') ? 'menuitem-active' : '' }} {{ Route::is('product.order') ? 'menuitem-active' : '' }}">
                                    <a href="{{ route('product_index') }}"
                                        class="{{ Route::is('add_to_cart') ? 'active' : '' }} {{ Route::is('product.order') ? 'active' : '' }}">Product
                                        List</a></li>
                            @endif
                            @if (in_array('limited-products-list', $user->permissions()->pluck('permission')->toArray()))
                            <li><a href="{{ route('limited_product') }}">Limited Stock</a></li>
                            @endif
                            @if (in_array('out-of-stock', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('out_of_product') }}">Out of Stock</a></li>
                            @endif
                            @if (in_array('upload-product-image', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('upload_product_image') }}">No Image Product</a></li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if (in_array('customer-management', $user->permissions()->pluck('permission')->toArray()))
                <li
                    class="side-nav-item {{ Route::is('customer_view') ? 'menuitem-active' : '' }}  {{ Route::is('customer.details') ? 'menuitem-active' : '' }}">
                    <a data-bs-toggle="collapse" href="#sba6" aria-expanded="false" aria-controls="sba6"
                        class="side-nav-link">
                        <i class="uil uil-user"></i>
                        <span>Customer Mgt</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse {{ Route::is('customer_view') ? 'show' : '' }} {{ Route::is('customer.details') ? 'show' : '' }}"
                        id="sba6">
                        <ul class="side-nav-second-level">
                            @if (in_array('customer-list', $user->permissions()->pluck('permission')->toArray()))
                                <li class="{{ Route::is('customer_view') ? 'menuitem-active' : '' }}"><a
                                        href="{{ route('customer_list') }}">Customer List</a></li>
                            @endif
                            @if (in_array('customers-card-list', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('customer_card_list') }}">Membership Card List </a></li>
                            @endif
                            @if (in_array('customers-total-order', $user->permissions()->pluck('permission')->toArray()))
                                <li class="{{ Route::is('customer.details') ? 'menuitem-active' : '' }}"><a
                                        href="{{ route('customer.index') }}">Customer’s Total Order</a></li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif

            @if (in_array('sub-admin-management', $user->permissions()->pluck('permission')->toArray()))
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sba4" aria-expanded="false" aria-controls="sba4"
                        class="side-nav-link">
                        <i class="mdi mdi-account-supervisor-outline"></i>
                        <span>User Management</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sba4">
                        <ul class="side-nav-second-level">
                            @if (in_array('user-list', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('sub-admins.index') }}">User List</a></li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif



            @if (in_array('product-report', $user->permissions()->pluck('permission')->toArray()))
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sba7" aria-expanded="false" aria-controls="sba7"
                        class="side-nav-link">
                        <i class="dripicons-box"></i>
                        <span>Reports</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sba7">
                        <ul class="side-nav-second-level">
                            @if (in_array('report', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('product_report') }}">Purchase Report</a></li>
                            @endif
                            @if (in_array('stock-report', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('stock_report') }}">Stock Report</a></li>
                            @endif
                            @if (in_array('stock-inserted', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('stock_inserted') }}">Insert Product Report</a></li>
                            @endif
                            @if (in_array('product-selling-order', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('product_selling_order') }}">Qnt Sold by Product</a></li>
                            @endif
                            @if (in_array('purchase-report', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('purchase_report') }}">Expenses </a></li>
                            @endif
                            @if (in_array('user-purchase-report', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('user_purchase_report') }}">Purchase Report by User</a></li>
                            @endif
                        </ul>
                    </div>
                </li>
            @endif
            @if (in_array('site-settings', $user->permissions()->pluck('permission')->toArray()))
                <li class="side-nav-item">
                    <a data-bs-toggle="collapse" href="#sba1" aria-expanded="false" class="side-nav-link">
                        <i class="dripicons-gear"></i>
                        <span>Site Settings</span>
                        <span class="menu-arrow"></span>
                    </a>
                    <div class="collapse" id="sba1">
                        <ul class="side-nav-second-level">
                            @if (in_array('admin-setting', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('site.settings') }}">Admin Setting</a></li>
                            @endif
                            @if (in_array('change-password', $user->permissions()->pluck('permission')->toArray()))
                                <li><a href="{{ route('change_password') }}">Change Password</a></li>
                            @endif

                        </ul>
                    </div>
                </li>
            @endif
        </ul>
    @endif
    <!-- End Sidebar -->
    <div class="clearfix"></div>
    <!-- Sidebar -left -->
</div>
<!-- Left Sidebar End
