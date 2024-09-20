 <aside class="main-sidebar">
     <!-- sidebar: style can be found in sidebar.less -->
     <section class="sidebar">
         <!-- Sidebar user panel -->
         <div class="user-panel">
             <div class="pull-left image">
                 <img src="{{ assetsUrl() }}/img/user2-160x160.jpg" class="img-circle" alt="User Image">
             </div>
             <div class="pull-left info">
                 <p> {{ Auth::user()->name }}</p>
             </div>
         </div>
         <ul class="sidebar-menu" data-widget="tree">
             <li class="header">MAIN NAVIGATION</li>

             <li class="{{ Request::is('admin/dashboard') ? 'active' : '' }}">
                 <a href="{{ url('admin/dashboard') }}"><i class="fa fa-book"></i><span>Dashboard</span></a>
             </li>

             @if (Auth::user()->type == 'admin')
                 <li class="treeview {{ Request::is('*static_page*') ? 'active' : '' }}">
                     <a href="#"><i class="fa fa-files-o"></i> <span>CMS Pages</span>
                         <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                     </a>
                     <ul class="treeview-menu">
                         @if (isset($pages_static))
                             @foreach ($pages_static as $page_static)
                                <li class="{{ Request::is('static_page/'.$page_static->id.'/edit') ? 'active' : '' }}">
                                    <a href="{{ route('static_page.edit', $page_static->id) }}">{{ $page_static->title }}</a>
                                </li>
                             @endforeach
                         @endif
                     </ul>
                 </li>
                <li class="treeview {{ Request::is('*amenity*') ? 'active' : '' }}">
                    <a href="#"><i class="fa fa-files-o"></i> <span>Proman Setup</span>
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left pull-right"></i>
                        </span>
                    </a>
                    <ul class="treeview-menu">
                        <li class="{{ Request::is('admin/amenities') ? 'active' : '' }}">
                            <a href="{{ route('admin.amenities.index') }}">Amenities</a>
                        </li>
                    </ul>
                </li>
                 <li
                     class="treeview {{ request()->is('*email_templates*', '*admin/time-slots*', '*admin/categories*', '*admin/attribute-types*', '*admin/attribute-values*', '*admin/products*', '*admin/skis*', '*admin/ski_boot*', '*admin/inventories*', '*addons*', '*maintenance*') ? 'active' : '' }}">

                 <li class="{{ request()->is('*email_templates*') ? 'active' : '' }}">
                     <a href="{{ route('admin.email_templates.index') }}"><i class="fa fa-envelope"></i>Templates</a>
                 </li>

             @endif

             @if (Auth::user()->type == 'admin')
                 <li
                     class="treeview {{ request()->is('*admin/settings*') || request()->is('admin/users*') ? 'active' : '' }}">
                     <a href="#">
                         <i class="fa fa-gear"></i>
                         <span>Settings</span>
                         <span class="pull-right-container">
                             <i class="fa fa-angle-left pull-right"></i>
                         </span>
                     </a>
                     <ul class="treeview-menu">
                         <li class="{{ Request::is('admin/users') ? 'active' : '' }}">
                            <a href="{{ route('admin.users.index') }}"><i class="fa fa-users"></i>Admins</a>
                        </li>
                         <li class="{{ Request::is('admin/settings/general') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.general') }}"><i class="fa fa-globe"></i>General</a>
                         </li>
                         <li class="{{ request()->is('*site_settings*') ? 'active' : '' }}">
                             <a href="{{ route('admin.settings.site') }}"><i class="fa fa-globe"></i>Site
                                 Settings</a>
                         </li>
                         <li class="{{ Request::is('admin/settings/mail') ? 'active' : '' }}">
                            <a href="{{ route('admin.settings.mail') }}">
                                <i class="fa fa-envelope-o"></i>Email Settings
                            </a>
                        </li>

                         @if (config('site.payment_type') != 'no_payment')
                             <li class="{{ Request::is('admin/settings/gateway') ? 'active' : '' }}">
                                <a href="{{ route('admin.settings.gateway') }}">
                                    <i class="fa fa-credit-card"></i>Gateway
                                </a>
                            </li>

                             @if (
                                 (Auth::user()->email = 'programmer@rezosystems.com' || Auth::user()->email == 'marc@rezosystems.com') &&
                                     config('rezo_gateway.service'))
                                 <li><a href="{{ route('admin.settings.rezo_gateway') }}"><i
                                             class="fa fa-credit-card"></i>Rezo Gateway Settings</a></li>
                             @endif

                         @endif
                     </ul>
                 </li>
             @endif
         </ul>
     </section>
 </aside>
