<?php

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

if (!function_exists('help')) {
	function help()
	{
		return app(\App\Library\Help::class);
	}
}

if (!function_exists('authUser')) {
	function authUser()
	{
		return Auth::user();
	}
}

if (!function_exists('parseDate')) {
	function parseDate($date, $format = null){
		if($format){
			if ($format == 'date-picker') {
				return Carbon::parse($date)->format('M d Y');
			} else {
				return Carbon::parse($date)->format($format);
			}
		} else{
			return Carbon::parse($date)->format('d M Y');
		}
	}
}

if (!function_exists('parseTime')) {
	function parseTime($date, $format = null){
		if($format){
			return Carbon::parse($date)->format($format);
		} else{
			return Carbon::parse($date)->format('h:i A');
		}
	}
}

if (!function_exists('parseDateTime')) {
	function parseDateTime($date, $format = null){
		if($format){
			return Carbon::parse($date)->format($format);
		} else{
			return Carbon::parse($date)->format('d M Y h:i A');
		}
	}
}

if (!function_exists('check_dbconn')) {
	function check_dbconn()
	{
		try {
            if (count(\DB::connection()->getPdo())) {
                $db = true;
            }
        } catch (\Exception $e) {
            $db = false;
        }

        return $db;
	}
}

if (!function_exists('check_systables') && !function_exists('table_exists')) {

	function check_systables()
	{
		$tables = array();

		foreach (config('system-table') as $table) {
			$tables[] = $table['table_name'];
		}

        foreach ($tables as $table) {
            if (!table_exists($table)) {
                return false;
            }
        }

        return true;
	}

	function table_exists($table)
	{
		if (Schema::hasTable($table)) {
            return true;
        }

        return false;
	}
}

if (!function_exists('slug')) {
	function slug($replacement, $string)
	{
		return preg_replace("/[\W_\s]+/", $replacement, $string);
	}
}

if (!function_exists('remove_temp_env_file')) {
	function remove_temp_env_file($filename) {
        if (\File::exists(base_path($filename))) {
            try {
            	\File::delete(base_path($filename));
            } catch (\Exception $e) {
            	return false;
            }
        }

        return true;
	}
}

if (!function_exists('system_migration_names')) {
	function system_migration_names($tables)
	{
		$migrations = [];

		foreach ($tables as $table) {
            $name = $table['migration_name'];
            $files = glob(database_path("migrations/*$name.*"));
            foreach ($files as $file) {
                $migrations[] = pathinfo($file)['filename'];
            }
        }

        return array_unique($migrations);
	}
}


if (!function_exists('delete_migrations')) {
	function delete_migrations()
	{
	    try {
	        $migrations = system_migration_names(config('system-table'));
	        \DB::table('migrations')
	            ->whereIn('migration', $migrations)->delete();

	        return true;
	    } catch (\Exception $e) {
	        return false;
	    }
	}
}

if (! function_exists('trans_fb')) {
    function trans_fb($id, $fallback, $parameters = [], $domain = 'messages', $locale = null)
    {
        return ($id === ($translation = trans($id, $parameters, $domain, $locale))) ? $fallback : $translation;
    }
}

if (!function_exists('all_middlewares')) {
	function all_middlewares($exceptions = array())
	{
		$middlewares = array();
		$routeMiddlewares = app()->router->getMiddleware();
        $routeGroupMiddlewares = app()->router->getMiddlewareGroups();

        foreach ($routeMiddlewares as $key => $routeMiddleware) {
        	if (!in_array($key, $exceptions)) {
        		$middlewares[] = $key;
        	}
        }

        foreach ($routeGroupMiddlewares as $key => $routeGroupMiddleware) {
        	if (!in_array($key, $exceptions)) {
        		$middlewares[] = $key;
        	}
        }

        array_unshift($middlewares, 'pintu');
        unset($middlewares[0]);
        return $middlewares;
	}
}

if (!function_exists('all_controllers')) {
	function all_controllers($dir, $baseNamespace = null)
	{
		$controllers = array();
		$files = \File::allFiles($dir);

		foreach ($files as $file) {
			$filename = $file->getFilename();
			$extension = $file->getExtension();
			$namespace = Help::namespace($file);
			$controllerName = $namespace.'\\'.str_replace('.'.$extension, '', $filename);

			if ($baseNamespace) {
				$controllers[] = str_replace($baseNamespace.'\\', '', $controllerName);
			} else {
				$controllers[] = $controllerName;
			}
		}

		array_unshift($controllers, 'none');
		unset($controllers[0]);
	    return $controllers;
	}
}

if (!function_exists('get_controller_name')) {
	function get_controller_name($id)
	{
		$controllers = all_controllers(app_path('Http/Controllers'), 'App\Http\Controllers');

		if (array_key_exists($id, $controllers)) {
			return $controllers[$id];
		}

		return false;
	}
}

if (!function_exists('get_middleware_name')) {
	function get_middleware_name($values)
	{
		$availableMiddlewares = all_middlewares(['web']);
        $middlewares = array();

        foreach ($values as $id) {
        	if (array_key_exists($id, $availableMiddlewares)) {
        		$middlewares[] = $availableMiddlewares[$id];
        	}
        }

        return $middlewares;
	}
}

if (!function_exists('class_methods')) {
	function class_methods($className, $excludeConstructor = false)
	{
		$classWithFullNamespace = 'App\Http\Controllers'.'\\'.$className;
        $lowerClassName = strtolower($classWithFullNamespace);
        $reflector = new \ReflectionClass($classWithFullNamespace);
        $methods = array();

        foreach ($reflector->getMethods() as $method) {
            if (strtolower($method->class) == $lowerClassName) {
                if ($excludeConstructor) {
                	if ($method->name != '__construct') {
                		$methods[] = $method->name;
                	}
                } else {
                	$methods[] = $method->name;
                }
            }
        }

        return $methods;
	}
}

if (!function_exists('class_methods_collection')) {
	function class_methods_collection($classes)
	{
		$collection = null;

		foreach ($classes as $id => $name) {
            $class = new \stdClass();
            $class->id = $id;
            $class->name = $name;

            $methods = class_methods($name, true);

            $class->methods = $methods;
            
            $collection[] = $class;
        }

        return collect($collection);
	}
}

if (!function_exists('resourceful_controller_methods')) {
	function resourceful_controller_methods()
	{
		$arrangeMethods = array();
	    $methods = array('Index', 'Create', 'Show', 'Edit', 'Update', 'Store', 'Destroy');

	    foreach ($methods as $key => $name) {
	    	$arrangeMethods[$key+1] = $name;
	    }

	    return $arrangeMethods;
	}
}

if (!function_exists('add_request')) {
	function add_request(Request $request, $attributes = array())
	{
		foreach ($attributes as $attribute) {
			$request[$attribute] = null;
		}
	}
}

/*if (!function_exists('get_agents_info')) {
	function get_agents_info()
	{
		$agent = array(
			'device' => Agent::device(),
			'platform' => array(
				'name' => Agent::platform(),
				'version' => Agent::version(Agent::platform()),
			),
			'browser' => array(
				'name' => Agent::browser(),
				'version' => Agent::version(Agent::browser()),
			),
			'languages' => Agent::languages(),
			'is_mobile' => Agent::isMobile(),
			'is_phone' => Agent::isPhone(),
			'is_desktop' => Agent::isDesktop(),
			'is_tablet' => Agent::isTablet(),
		);

		return $agent;
	}
}*/

/*if (!function_exists('activation_code')) {
	function activation_code()
	{
		if (cache('active_after_signup')) {
			return null;
		}

		return str_random(20);
	}
}

if (!function_exists('send_activation_code')) {
	function send_activation_code($user)
	{
		if (!tpms_config('active_after_signup')) {
			$template = config('site.user_activation_template');
		}
	}
}*/

if (!function_exists('flash_message')) {
	function flash_message($type, $title, $text)
	{
		session()->flash('title', $title);
        session()->flash('text', $text);
        session()->flash('type', $type);
	}
}

/*if (!function_exists('load_config_into_cache')) {
	function load_config_into_cache()
	{
		$cacheConfigs = array();
		try {
			// load all configuration into cache
	        if (!cache()->has('configurations') && !cache('configurations')) {
	            $configs = Configuration::select('key','value','pair')->get();

	            foreach ($configs as $config) {
	                $cacheConfigs[$config->key] = $config->value;
	            }
	            cache()->forever('configurations', $cacheConfigs);
	        }
		} catch (\Exception $e) {
			return false;
		}
	}
}*/

/*if (!function_exists('resourceful_route_name')) {
	function resourceful_route_name($nav, $index)
	{
		$as = $nav['group_params']['as'];
		$perfix = $nav['group_params']['prefix'];
		$url = $nav['url'];

		if ($as) {
			return \Route::has($as . $url . '.' . $index) ? route($as . $url . '.' . $index) : 'javascript:void(0);';
		} else {
			return \Route::has($url . '.' . $index) ? $url . '.' . $index : 'javascript:void(0);';
		}
	}
}*/

/*if (!function_exists('load_routes_into_cache')) {
	function load_routes_into_cache()
	{
		try {
			if (!cache()->has('routes')) {
				$webroutes = \App\Models\WebRoute::with('children')->get()->toArray();
				cache()->forever('routes', $webroutes);
			}
		} catch (\Exception $e) {
			return false;
		}
	}
}*/