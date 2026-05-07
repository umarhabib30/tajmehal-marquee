<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckAdminRoutePermission
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();
        if (! $user) {
            return redirect()->guest(route('login'));
        }

        if ($user->isSuperAdmin()) {
            return $next($request);
        }

        if (! $user->isStaffUser()) {
            return $this->denyAccess($request);
        }

        $routeName = $request->route()?->getName();
        $map = config('route_permissions.routes', []);

        if (! $routeName || ! isset($map[$routeName])) {
            return $this->denyAccess($request);
        }

        [$module, $action] = $map[$routeName];

        if (! $user->hasModulePermission($module, $action)) {
            return $this->denyAccess($request, $module, $action);
        }

        return $next($request);
    }

    protected function denyAccess(Request $request, ?string $module = null, ?string $action = null): Response
    {
        $message = $this->buildDeniedMessage($module, $action);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json(['message' => $message], 403);
        }

        $previousUrl = url()->previous();
        $currentUrl = $request->fullUrl();
        $fallbackUrl = Auth::user()?->defaultPostLoginPath() ?? '/admin/dashboard';

        if (empty($previousUrl) || $previousUrl === $currentUrl) {
            return redirect($fallbackUrl)->with('error', $message);
        }

        return redirect()->back()->with('error', $message);
    }

    protected function buildDeniedMessage(?string $module = null, ?string $action = null): string
    {
        if (! $module || ! $action) {
            return 'You do not have permission to access this page.';
        }

        $moduleLabel = strtolower(config("admin_modules.modules.{$module}", $module));
        $actionLabel = strtolower(config("admin_modules.actions.{$action}", $action));

        return "You do not have permission to {$actionLabel} {$moduleLabel}.";
    }
}
