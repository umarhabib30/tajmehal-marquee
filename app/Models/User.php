<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    public const ROLE_SUPER_ADMIN = 1;

    public const ROLE_STAFF = 2;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'permissions',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'permissions' => 'array',
    ];

    public function isSuperAdmin(): bool
    {
        return (int) $this->role === self::ROLE_SUPER_ADMIN;
    }

    public function isStaffUser(): bool
    {
        return (int) $this->role === self::ROLE_STAFF;
    }

    public function hasModulePermission(string $module, string $action): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (! $this->isStaffUser()) {
            return false;
        }

        $permissions = $this->permissions ?? [];
        if (! isset($permissions[$module]) || ! is_array($permissions[$module])) {
            return false;
        }

        return ! empty($permissions[$module][$action]);
    }

    public function canAccessModuleNav(string $module): bool
    {
        if ($this->isSuperAdmin()) {
            return true;
        }

        if (! $this->isStaffUser()) {
            return false;
        }

        foreach (array_keys(config('admin_modules.actions', [])) as $action) {
            if ($this->hasModulePermission($module, $action)) {
                return true;
            }
        }

        return false;
    }

    public function defaultPostLoginPath(): ?string
    {
        if ($this->isSuperAdmin()) {
            return '/admin/dashboard';
        }

        if (! $this->isStaffUser()) {
            return null;
        }

        foreach (config('admin_modules.landing_paths', []) as $module => $path) {
            if ($this->hasModulePermission($module, 'view')) {
                return $path;
            }
        }

        return null;
    }

    public static function normalizePermissionsArray(?array $input): array
    {
        $modules = array_keys(config('admin_modules.modules', []));
        $actions = array_keys(config('admin_modules.actions', []));
        $out = [];

        foreach ($modules as $module) {
            $out[$module] = [];
            foreach ($actions as $action) {
                $out[$module][$action] = ! empty($input[$module][$action] ?? false);
            }
        }

        return $out;
    }
}
