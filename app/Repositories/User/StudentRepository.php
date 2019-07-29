<?php

namespace App\Repositories\User;

use App\Events\StudentCreated;
use App\Events\UserUpdated;
use App\Exceptions\GeneralException;
use App\Models\Manager;
use App\Models\Student;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class StudentRepository extends BaseRepository
{
  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Student::class;
  }

  /**
   * @param array $data
   *
   * @return Manager
   * @throws \Exception
   * @throws \Throwable
   */
  public function create(array $data): Student
  {
    $pwd = random_password_generate(config('access.password_length', 10));
    return DB::transaction(function () use ($data, $pwd) {
      $user = parent::create([
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'code' => $data['code'],
        'username' => $data['username'],
        'password' => Hash::make($pwd),
        'is_actived' => \App\Models\Auth\User::DEACTIVED_CODE,
      ]);

      // See if adding any additional permissions
      if (!isset($data['permissions']) || !count($data['permissions'])) {
        $data['permissions'] = [];
      }

      if ($user) {
        // User must have at least one role
        if (!count($data['role_ids'])) {
          throw new GeneralException(__('exceptions.backend.access.users.role_needed_create'));
        }
        // Add selected roles/permissions
        $user->syncRoles($data['role_ids']);
        $user->syncPermissions($data['permissions']);
        $user->plain_pwd = $pwd;
        event(new StudentCreated($user));
        return $user;
      }
      throw new GeneralException(__('exceptions.backend.access.users.create_error'));
    });
  }

  /**
   * Get the specified model record from the database.
   *
   * @param       $uuid
   * @param array $columns
   *
   * @return Collection|Model
   */
  public function getByUuid($uuid, array $columns = ['*'])
  {
    $this->unsetClauses();

    $this->newQuery()->eagerLoad();

    return $this->query->where('uuid', $uuid)->first($columns);
  }

  /**
   * Update the specified model record in the database.
   *
   * @param       $id
   * @param array $data
   * @param array $options
   *
   * @return Collection|Model
   */
  public function updateByUuid($uuid, array $data, array $options = [])
  {
    $this->unsetClauses();

    $model = $this->getByUuid($uuid);

    $model->update($data, $options);

    return $model;
  }

  /**
   * Delete the specified model record from the database.
   *
   * @param $id
   *
   * @return bool|null
   * @throws \Exception
   */
  public function deleteByUuid($uuid): bool
  {
    $this->unsetClauses();

    return $this->getByUuid($uuid)->delete();
  }

  /**
   * @param User $user
   * @param array $data
   *
   * @return User
   * @throws GeneralException
   * @throws \Exception
   * @throws \Throwable
   */
  public function update(Manager $user, array $data): Manager
  {
    $this->checkUserByEmail($user, $data['email']);

    // See if adding any additional permissions
    if (!isset($data['permissions']) || !count($data['permissions'])) {
      $data['permissions'] = [];
    }

    return DB::transaction(function () use ($user, $data) {
      if ($user->update([
        'username' => $data['username'],
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'email' => $data['email'],
        'code' => $data['code']
//        'active' => $data['active']
      ])) {
        // Add selected roles/permissions
        $user->syncRoles($data['role_ids']);
        $user->syncPermissions($data['permissions']);

        event(new UserUpdated($user));

        return $user;
      }

      throw new GeneralException(__('exceptions.backend.access.users.update_error'));
    });
  }

  /**
   * Store multiple users with a specifed role
   *
   * @param Role $role
   * @param $data (array of data users)
   */
  public function storeMulti($data)
  {
    return DB::transaction(function () use ($data) {
      $users = [];
      foreach ($data as $user) {
        $newUser = $this->create($user);
        $users[] = $newUser;
      }
      return $users;
    });
  }
}
