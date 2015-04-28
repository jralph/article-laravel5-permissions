<?php namespace App\Http\Middleware;

use Closure;

class PermissionsRequiredMiddleware {

	/**
	 * Handle an incoming request.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  \Closure  $next
	 * @return mixed
	 */
	public function handle($request, Closure $next)
	{
		// Check if a user is logged in.
		if (!$user = $request->user())
		{
			// If not, continue the request.
			return $next($request);
		}

		// Get the current route.
		$route = $request->route();

		// Get the current route actions.
		$actions = $route->getAction();

		// Check if we have any permissions to check.
		if (!$permissions = isset($actions['permissons']) ? $actions['permissons'] : null)
		{
			// No permissions to check, allow access.
			return $next($request);
		}

		// Fetch all of the users permissions.
		$userPermissions = array_fetch($user->permissions->toArray(), 'slug');

		// Ensure that the required permissions are an array.
		$permissions = (array) $permissions;

		// Sort both permission arrays for easy comparison.
		sort($permissions);
		sort($userPermissions);

		// Check if we require all permissons or just one.
		if (isset($actions['permissions_require_all']))
		{
			// If the user has EVERY permission required.
			if ($userPermissions == $permissions) {
				// Allow the request.
				return $next($request);
			}
		} else {
			// Loop through each permission to check.
			foreach ($permissions as $permission)
	        {
	        	// If the user has the permission.
	            if (in_array($permission, $userPermissions))
	            {
	            	// Allow access and ignore any remaining permissions.
	                return $next($request);
	            }
	        }
		}

		// Abort the request if we reach here.
		return abort(401);
	}

}
