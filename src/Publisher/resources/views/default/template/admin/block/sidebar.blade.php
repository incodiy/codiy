<?php
/**
 * Created on 10 Mar 2021
 * Time Created	: 10:05:23
 *
 * @filesource	sidebar.blade.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
?>
<div class="sidebar-menu">
	<div class="sidebar-header">
		<div class="logo">
			<a href="{{ URL::to('admin')}}"><img class="logo" src="{{ $logo }}" alt="{{ $appName }}" /></a>
		</div>
	</div>
	@if($sidebar_content)
	{!! $sidebar_content !!}
	@endif
	<nav class="menu-inner">@foreach($menu_sidebar as $menu) {!! $menu !!} @endforeach</nav>
</div>
			<!--  SIDEBAR OPEN ->
			<div class="sidebar-menu">
				<div class="sidebar-header">
					<div class="logo">
						<a href="{{ $baseUrl }}"><img class="logo" src="" alt="Code DIY" /></a>
					</div>
				</div>
				<div class="relative">
					<a data-toggle="collapse" href="#userInfoBox" role="button" aria-expanded="false" aria-controls="userInfoBox" class="btn-sets btn-sets-sm absolute sets-right-bottom sets-top btn-primary shadow1 collapsed"><i class="ti-settings"></i></a>
					<div class="user-panel light">
						<div>
							<div class="float-left image"><img class="user-avatar" src="{{ $assetURL }}/images/user-m.png" alt="wisnuwidi" title="wisnuwidi" /></div>
							<div class="float-left info">
								<h6 class="font-weight-light mt-2 mb-1">wisnuwidi</h6>
								<a href="#"><i class="fa fa-circle text-primary blink"></i> online</a>
							</div>
						</div>
						<div class="clearfix"></div>
						<div class="multi-collapse collapse" id="userInfoBox">
							<div class="list-group mt-3 shadow">
								<a href="{{ $baseUrl }}/system/accounts/user/1" class="list-group-item list-group-item-action "><i class="mr-2 ti-user text-blue"></i>Profile</a>
								<a href="{{ $baseUrl }}/system/accounts/user/1/edit" class="list-group-item list-group-item-action"><i class="mr-2 ti-settings text-yellow"></i>Edit</a>
								<a href="{{ $baseUrl }}/logout" class="list-group-item list-group-item-action"><i class="mr-2 ti-panel text-purple"></i>Log Out</a>
							</div>
						</div>
					</div>
				</div>
				<nav class="menu-inner">
					<ul id="menu" class="main-menu">
						<li id="dashboard" class="submenu"><a href="{{ $baseUrl }}/dashboard"><span class="icon"></span><span class="text">Dashboard</span></a></li>
						<li class="sidebar-category"><span>system</span><span class="pull-right"><i class="fa fa-bookmark"></i></span></li>
						<li id="config" class="submenu">
							<a class="arrow-node" href="javascript:void(0);"><span class="icon"><i class="fa fa-tags"></i></span><span class="text">Config</span><span"></span></a>
							<ul>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/config/module">Module</a></li>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/config/group">Group</a></li>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/config/preference">Preference</a></li>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/config/icon">Icon</a></li>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/config/log">Log</a></li>
								<li class="submenu">
									<a href="javascript:void(0);"><span class="text">Masjid</span><span class="arrow open fa-angle-double-down"></span></a>
									<ul>
										<li id="config-masjid-Type"><a class="menu-url" href="{{ $baseUrl }}/system/config/masjid/type">Type</a></li>
										<li id="config-masjid-Land Status"><a class="menu-url" href="{{ $baseUrl }}/system/config/masjid/land_status">Land Status</a></li>
									</ul>
								</li>
							</ul>
						</li>
						<li id="accounts" class="submenu">
							<a class="arrow-node" href="javascript:void(0);"><span class="icon"><i class="fa fa-tags"></i></span><span class="text">Accounts</span><span"></span></a>
							<ul>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/accounts/user">User</a></li>
							</ul>
						</li>
						<li id="internal" class="submenu">
							<a class="arrow-node" href="javascript:void(0);"><span class="icon"><i class="fa fa-tags"></i></span><span class="text">Internal</span><span"></span></a>
							<ul>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/internal/about">About</a></li>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/internal/teams">Teams</a></li>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/internal/contact">Contact</a></li>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/internal/faq">Faq</a></li>
							</ul>
						</li>
						<li id="banners" class="submenu">
							<a class="arrow-node" href="javascript:void(0);"><span class="icon"><i class="fa fa-tags"></i></span><span class="text">Banners</span><span"></span></a>
							<ul>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/banners/type">Type</a></li>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/banners/contents">Contents</a></li>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/banners/approvals">Approvals</a></li>
							</ul>
						</li>
						<li id="articles" class="submenu">
							<a class="arrow-node" href="javascript:void(0);"><span class="icon"><i class="fa fa-tags"></i></span><span class="text">Articles</span><span"></span></a>
							<ul>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/articles/type">Type</a></li>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/articles/contents">Contents</a></li>
								<li class="menu-active-pointer"><a class="menu-url" href="{{ $baseUrl }}/system/articles/approvals">Approvals</a></li>
							</ul>
						</li>
						<li class="sidebar-category"><span>modules</span><span class="pull-right"><i class="fa fa-bookmark"></i></span></li>
						<li id="masjid" class="submenu"><a href="{{ $baseUrl }}/modules/masjid"><span class="icon"></span><span class="text">Masjid</span></a></li>
						<li class="sidebar-category"><span>developments</span><span class="pull-right"><i class="fa fa-bookmark"></i></span></li>
						<li id="testing" class="submenu"><a href="{{ $baseUrl }}/developments/testing"><span class="icon"><i class="0"></i></span><span class="text">Testing</span></a></li>
					</ul>
				</nav>
			</div>
			<!--  SIDEBAR CLOSE -->