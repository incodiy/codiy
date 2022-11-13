<?php
/**
 * Created on 10 Mar 2021
 * Time Created	: 10:01:45
 *
 * @filesource	index.blade.php
 *
 * @author		wisnuwidi@gmail.com - 2021
 * @copyright	wisnuwidi
 * @email		wisnuwidi@gmail.com
 */
?>

@extends('default.template.admin.index')

@section('content')
	
    @foreach($content_page as $content)
    	@if (!is_array($content))
    	{!! $content !!}
    	@endif
    @endforeach
    
@endsection