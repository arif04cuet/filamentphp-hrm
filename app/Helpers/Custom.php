<?php

	if(!function_exists('isRoutes'))
	{
		function isRoutes ($routes=[])
		{
			if(is_array($routes)) foreach ($routes as $route) 
			{
				if(request()->routeIs($route)) return true;
			}
			return false;
		}
	}

	if(!function_exists('str_slug'))
	{
		function str_slug ($string='')
		{
			return strtolower(str_replace(' ', '-', $string));
		}
	}

	if(!function_exists('msg'))
	{
		function msg ($msg='', $type='success')
		{
			return session()->flash('msg', "toastr.{$type}(`{$msg}`)");
		}
	}