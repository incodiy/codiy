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