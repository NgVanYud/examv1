<?php


namespace App\Repositories\User;


use App\Events\UserCreated;
use App\Events\UserUpdated;
use App\Exceptions\GeneralException;
use App\Models\Manager;
use App\Models\User;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Events\ManagerCreated;

class ManagerRepository extends BaseRepository
{
  /**
   * Specify Model class name.
   *
   * @return mixed
   */
  public function model()
  {
    return Manager::class;
  }

  /**
   * @param array $data
   *
   * @return Manager
   * @throws \Exception
   * @throws \Throwable
   */
  public function create(array $data) : Manager
  {
    $pwd = Str::random(config('access.password_length', 10));
    return DB::transaction(function () use ($data, $pwd) {
      $user = parent::create([
        'first_name' => $data['first_name'],
        'last_name' => $data['last_name'],
        'email' => isset($data['email']) ? $data['email']:'',
        'code' => $data['code'],
        'username' => $data['username'],
        'password' => Hash::make($pwd),
        'is_actived' => (isset($data['active']) && $data['active'] == '1') ? 1 : 0,
      ]);

      // See if adding any additional permissions
      if (! isset($data['permissions']) || ! count($data['permissions'])) {
        $data['permissions'] = [];
      }

      if ($user) {
        // User must have at least one role
        if (! count($data['role_ids'])) {
          throw new GeneralException(__('exceptions.backend.access.users.role_needed_create'));
        }

        // Add selected roles/permissions
        $user->syncRoles($data['role_ids']);
        $user->syncPermissions($data['permissions']);

        //Send confirmation email if requested and account approval is off
        if (isset($data['confirmation_email']) && $user->confirmed == 0 && ! config('access.users.requires_approval')) {
          $user->notify(new UserNeedsConfirmation($user->confirmation_code));
        }

        event(new ManagerCreated($user));

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
   * @param User  $user
   * @param array $data
   *
   * @return User
   * @throws GeneralException
   * @throws \Exception
   * @throws \Throwable
   */
  public function update(Manager $user, array $data) : Manager
  {
    $this->checkUserByEmail($user, $data['email']);

    // See if adding any additional permissions
    if (! isset($data['permissions']) || ! count($data['permissions'])) {
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
   * Kiểm tra email đã tồn tại chưa
   * @param Manager $user
   * @param      $email
   *
   * @throws GeneralException
   */
  protected function checkUserByEmail(Manager $user, $email)
  {
    //Figure out if email is not the same
    if ($user->email != $email) {
      //Check to see if email exists
      if ($this->model->where('email', '=', $email)->first()) {
        throw new GeneralException(trans('exceptions.backend.access.users.email_error'));
      }
    }
  }
}
