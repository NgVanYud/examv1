<?php
namespace App\Models\Traits;

use Illuminate\Support\Collection;

trait UserMethods {

  public function isActive() {
    return $this->is_actived;
  }

  public function getRoleIds(): Collection
  {
    return $this->roles->pluck('id');
  }

  public function getPermissionIds(): Collection
  {
    return $this->permissions->pluck('id');
  }

}
