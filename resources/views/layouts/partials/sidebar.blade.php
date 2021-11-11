<div class="page-sidebar box-shadow">
    <i class="icon-close" id="sidebar-toggle-button-close"></i>
    <div class="logo-box">
        <img width="130px" src="{{asset('images/logo.png')}}" alt="">
    </div>
    <div class="logo-brand">
        <img width="130px" src="{{asset('images/logo.png')}}" alt="">
    </div>
    <div class="page-sidebar-inner">
        <div class="page-sidebar-menu">
            <ul class="accordion-menu">
                <li class="{{ isset($page) && $page == 'home' ? 'active-page' : '' }}">
                    <a href="{{route('home')}}">
                        <i class="menu-icon icon-home4"></i><span>Dashboard</span>
                    </a>
                </li>
                @role('member')
                    <li class="{{ isset($page) && $page == 'user' ? 'active-page' : '' }}">
                        <a href="{{route('user.profile')}}">
                            <i class="menu-icon icon-user"></i><span>Profile</span>
                        </a>
                    </li>
                    <li class="{{ isset($page) && $page == 'bank' ? 'active-page' : '' }}">
                        <a href="{{route('user.bank')}}">
                            <i class="menu-icon icon-account_balance"></i><span>Rekening</span>
                        </a>
                    </li>
                    <li class="{{ isset($page) && $page == 'koin' ? 'active-page' : '' }}">
                        <a href="{{route('koin.index')}}">
                            <i class="menu-icon fa fa-gg-circle"></i><span>Koin</span>
                        </a>
                    </li>
                    <li class="{{ isset($page) && $page == 'member' ? 'active-page' : '' }}">
                        <a href="{{route('team.index')}}">
                            <i class="menu-icon icon-users"></i><span>Member</span>
                        </a>
                    </li>
                    <li class="{{ isset($page) && $page == 'add_member' ? 'active-page' : '' }}">
                        <a href="{{route('team.add_member')}}">
                            <i class="menu-icon icon-user-plus"></i><span>Daftar Member</span>
                        </a>
                    </li>
                    <li class="{{ isset($page) && $page == 'transaksi' ? 'active-page' : '' }}">
                        <a href="{{route('transaksi.index')}}">
                            <i class="menu-icon icon-account_balance_wallet"></i><span>Transaksi</span>
                        </a>
                    </li>
                    <li class="{{ isset($page) && $page == 'notifikasi' ? 'active-page' : '' }}">
                        <a href="{{route('notifikasi.index')}}">
                            <i class="menu-icon fa fa-bell"></i><span>Notifikasi</span>
                        </a>
                    </li>
                @endrole
                @role(['admin','super_admin'])
                    <li class="{{ isset($page) && $page == 'user' ? 'active-page' : '' }}">
                        <a href="javascript:void(0);">
                            <i class="menu-icon icon-user"></i><span>Member</span><i class="accordion-icon fa fa-angle-left"></i>
                        </a>
                        <ul class="sub-menu">
                            <li><a class="{{ isset($active) && $active == 'tambah_member' ? 'active' : '' }}" href="{{route('user.create')}}">Tambah Member</a></li>
                            <li><a class="{{ isset($active) && $active == 'admin' ? 'active' : '' }}" href="{{route('user.list','admin')}}">Daftar Admin</a></li>
                            <li><a class="{{ isset($active) && $active == 'member' ? 'active' : '' }}" href="{{route('user.list','member')}}">Daftar Member</a></li>
                            <li><a class="{{ isset($active) && $active == 'koin_member' ? 'active' : '' }}" href="{{route('balance.index')}}">Koin Member</a></li>
                            <li><a class="{{ isset($active) && $active == 'team_member' ? 'active' : '' }}" href="{{route('user.list_sponsor')}}">Team Member</a></li>
                        </ul>
                    </li>
                    <li class="{{ isset($page) && $page == 'transaksi' ? 'active-page' : '' }}">
                        <a href="javascript:void(0);">
                            <i class="menu-icon icon-account_balance_wallet"></i><span>Transaksi</span><i class="accordion-icon fa fa-angle-left"></i>
                        </a>
                        <ul class="sub-menu">
                            <li><a class="{{ isset($active) && $active == 'list_buy' ? 'active' : '' }}" href="{{route('koin.list.buy')}}">Beli Koin</a></li>
                            <li><a class="{{ isset($active) && $active == 'buy_sell' ? 'active' : '' }}" href="{{route('koin.list.buy_sell')}}">Jual/Beli Koin Member</a></li>
                            <li><a class="{{ isset($active) && $active == 'donasi' ? 'active' : '' }}" href="{{route('transaksi.donasi.list')}}">Donasi</a></li>
                        </ul>
                    </li>
                    <li class="{{ isset($page) && $page == 'setting' ? 'active-page' : '' }}">
                        <a href="javascript:void(0);">
                            <i class="menu-icon icon-settings"></i><span>Pengaturan</span><i class="accordion-icon fa fa-angle-left"></i>
                        </a>
                        <ul class="sub-menu">
                            <li><a class="{{ isset($active) && $active == 'method' ? 'active' : '' }}" href="{{route('setting.method')}}">Tipe Pembayaran</a></li>
                            <li><a class="{{ isset($active) && $active == 'account' ? 'active' : '' }}" href="{{route('setting.account')}}">Akun Perusahaan</a></li>
                            <li><a class="{{ isset($active) && $active == 'contact' ? 'active' : '' }}" href="{{route('setting.contact')}}">Kontak Perusahaan</a></li>
                            <li><a class="{{ isset($active) && $active == 'price' ? 'active' : '' }}" href="{{route('setting.index')}}">Harga Koin</a></li>
                            <li><a class="{{ isset($active) && $active == 'level' ? 'active' : '' }}" href="{{route('setting.level')}}">Level</a></li>
                        </ul>
                    </li>
                @endrole

                <li class="logout">
                    <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <i class="menu-icon fa fa-sign-out fa-rotate-180"></i><span>Logout</span>
                    </a>
                    <form id="logout-form" action="{{ url('/logout') }}" method="POST" style="display: none;">
                        {{ csrf_field() }}
                        <input type="submit" value="logout" style="display: none;">
                    </form>
                </li>
            </ul>
        </div>
    </div>
</div>
