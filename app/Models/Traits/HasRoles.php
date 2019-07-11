<?php
namespace App\Models\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;
use Spatie\Permission\Contracts\Role;
use Spatie\Permission\Traits\HasRoles as HasRolesRelationship;

trait HasRoles {

  use HasRolesRelationship;

  /**
   * Scope the model query to not certain roles only.
   *
   * @param \Illuminate\Database\Eloquent\Builder $query
   * @param string|array|\Spatie\Permission\Contracts\Role|\Illuminate\Support\Collection $roles
   * @param string $guard
   *
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeNotRole(Builder $query, $roles, $guard) {
    if ($roles instanceof Collection) {
      $roles = $roles->all();
    }

    if (! is_array($roles)) {
      $roles = [$roles];
    }

    $roles = array_map(function ($role) use ($guard) {
      if ($role instanceof Role) {
        return $role;
      }

      $method = is_numeric($role) ? 'findById' : 'findByName';
      $guard = $guard ?: $this->getDefaultGuardName();

      return $this->getRoleClass()->{$method}($role, $guard);
    }, $roles);

    return $query->whereDoesntHave('roles', function ($query) use ($roles) {
      $query->where(function ($query) use ($roles) {
        foreach ($roles as $role) {
          $query->where(config('permission.table_names.roles').'.id', $role->id);
        }
      });
    });
  }
}
