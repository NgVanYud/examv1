<?php

namespace App\Repositories\User;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\Role\RoleRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use App\Exceptions\GeneralException;
use App\Repositories\BaseRepository;
use App\Events\UserCreated;
use App\Events\UserUpdated;
//use App\Events\Backend\Auth\User\UserCreated;
//use App\Events\Backend\Auth\User\UserUpdated;
//use App\Events\Backend\Auth\User\UserRestored;
//use App\Events\Backend\Auth\User\UserConfirmed;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

//use App\Events\Backend\Auth\User\UserDeactivated;
//use App\Events\Backend\Auth\User\UserReactivated;
//use App\Events\Backend\Auth\User\UserUnconfirmed;
//use App\Events\Backend\Auth\User\UserPasswordChanged;
//use App\Notifications\Backend\Auth\UserAccountActive;
//use App\Events\Backend\Auth\User\UserPermanentlyDeleted;
//use App\Notifications\Frontend\Auth\UserNeedsConfirmation;

/**
 * Class UserRepository.
 */
class UserRepository extends BaseRepository
{
  public $roleRepository;

  /**
   * PHP 5 allows developers to declare constructor methods for classes.
   * Classes which have a constructor method call this method on each newly-created object,
   * so it is suitable for any initialization that the object may need before it is used.
   *
   * Note: Parent constructors are not called implicitly if the child class defines a constructor.
   * In order to run a parent constructor, a call to parent::__construct() within the child constructor is required.
   *
   * param [ mixed $args [, $... ]]
   * @link https://php.net/manual/en/language.oop5.decon.php
   */
  public function __construct(RoleRepository $roleRepository)
  {
    $this->roleRepository = $roleRepository;
    $this->makeModel();
  }


  /**
     * @return string
     */
    public function model()
    {
        return User::class;
    }

    /**
     * @return mixed
     */
    public function getUnconfirmedCount() : int
    {
        return $this->model
            ->where('confirmed', 0)
            ->count();
    }

    /**
     * @param int    $paged
     * @param string $orderBy
     * @param string $sort
     *
     * @return mixed
     */
    public function getActivePaginated($paged = 25, $orderBy = 'created_at', $sort = 'desc') : LengthAwarePaginator
    {
        return $this->model
            ->with('roles', 'permissions', 'providers')
            ->active()
            ->orderBy($orderBy, $sort)
            ->paginate($paged);
    }

    /**
     * @param int    $paged
     * @param string $orderBy
     * @param string $sort
     *
     * @return LengthAwarePaginator
     */
    public function getInactivePaginated($paged = 25, $orderBy = 'created_at', $sort = 'desc') : LengthAwarePaginator
    {
        return $this->model
            ->with('roles', 'permissions', 'providers')
            ->active(false)
            ->orderBy($orderBy, $sort)
            ->paginate($paged);
    }

    /**
     * @param int    $paged
     * @param string $orderBy
     * @param string $sort
     *
     * @return LengthAwarePaginator
     */
    public function getDeletedPaginated($paged = 25, $orderBy = 'created_at', $sort = 'desc') : LengthAwarePaginator
    {
        return $this->model
            ->with('roles', 'permissions', 'providers')
            ->onlyTrashed()
            ->orderBy($orderBy, $sort)
            ->paginate($paged);
    }

    /**
     * @param array $data
     *
     * @return User
     * @throws \Exception
     * @throws \Throwable
     */
    public function create(array $data, $knowPwd = false) : User
    {
        $pwd = Str::random(config('access.password_length', 10));
        return DB::transaction(function () use ($data, $pwd, $knowPwd) {
            $user = parent::create([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'code' => $data['code'],
                'username' => $data['username'],
                'password' => Hash::make($pwd),
                'active' => (isset($data['active']) && $data['active'] == '1') ? 1 : 0,
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

                event(new UserCreated($user));

                if ($knowPwd) {
                  $user->plain_pwd = $pwd;
                }
                return $user;
            }

            throw new GeneralException(__('exceptions.backend.access.users.create_error'));
        });
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
    public function update(User $user, array $data) : User
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
                'code' => $data['code'],
                'active' => $data['active']
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
     * @param User $user
     * @param      $input
     *
     * @return User
     * @throws GeneralException
     */
    public function updatePassword(User $user, $input) : User
    {
        if ($user->update(['password' => $input['password']])) {
            event(new UserPasswordChanged($user));

            return $user;
        }

        throw new GeneralException(__('exceptions.backend.access.users.update_password_error'));
    }

    /**
     * @param User $user
     * @param      $status
     *
     * @return User
     * @throws GeneralException
     */
    public function mark(User $user, $status) : User
    {
        if (auth()->id() == $user->id && $status == 0) {
            throw new GeneralException(__('exceptions.backend.access.users.cant_deactivate_self'));
        }

        $user->active = $status;

        switch ($status) {
            case 0:
                event(new UserDeactivated($user));
            break;

            case 1:
                event(new UserReactivated($user));
            break;
        }

        if ($user->save()) {
            return $user;
        }

        throw new GeneralException(__('exceptions.backend.access.users.mark_error'));
    }

    /**
     * @param User $user
     *
     * @return User
     * @throws GeneralException
     */
    public function confirm(User $user) : User
    {
        if ($user->confirmed) {
            throw new GeneralException(__('exceptions.backend.access.users.already_confirmed'));
        }

        $user->confirmed = 1;
        $confirmed = $user->save();

        if ($confirmed) {
            event(new UserConfirmed($user));

            // Let user know their account was approved
            if (config('access.users.requires_approval')) {
                $user->notify(new UserAccountActive);
            }

            return $user;
        }

        throw new GeneralException(__('exceptions.backend.access.users.cant_confirm'));
    }

    /**
     * @param User $user
     *
     * @return User
     * @throws GeneralException
     */
    public function unconfirm(User $user) : User
    {
        if (! $user->confirmed) {
            throw new GeneralException(__('exceptions.backend.access.users.not_confirmed'));
        }

        if ($user->id == 1) {
            // Cant un-confirm admin
            throw new GeneralException(__('exceptions.backend.access.users.cant_unconfirm_admin'));
        }

        if ($user->id == auth()->id()) {
            // Cant un-confirm self
            throw new GeneralException(__('exceptions.backend.access.users.cant_unconfirm_self'));
        }

        $user->confirmed = 0;
        $unconfirmed = $user->save();

        if ($unconfirmed) {
            event(new UserUnconfirmed($user));

            return $user;
        }

        throw new GeneralException(__('exceptions.backend.access.users.cant_unconfirm'));
    }

    /**
     * @param User $user
     *
     * @return User
     * @throws GeneralException
     * @throws \Exception
     * @throws \Throwable
     */
    public function forceDelete(User $user) : User
    {
        if (is_null($user->deleted_at)) {
            throw new GeneralException(__('exceptions.backend.access.users.delete_first'));
        }

        return DB::transaction(function () use ($user) {
            // Delete associated relationships
            $user->passwordHistories()->delete();
            $user->providers()->delete();
            $user->sessions()->delete();

            if ($user->forceDelete()) {
                event(new UserPermanentlyDeleted($user));

                return $user;
            }

            throw new GeneralException(__('exceptions.backend.access.users.delete_error'));
        });
    }

    /**
     * @param User $user
     *
     * @return User
     * @throws GeneralException
     */
    public function restore(User $user) : User
    {
        if (is_null($user->deleted_at)) {
            throw new GeneralException(__('exceptions.backend.access.users.cant_restore'));
        }

        if ($user->restore()) {
            event(new UserRestored($user));

            return $user;
        }

        throw new GeneralException(__('exceptions.backend.access.users.restore_error'));
    }

    /**
     * @param User $user
     * @param      $email
     *
     * @throws GeneralException
     */
    protected function checkUserByEmail(User $user, $email)
    {
        //Figure out if email is not the same
        if ($user->email != $email) {
            //Check to see if email exists
            if ($this->model->where('email', '=', $email)->first()) {
                throw new GeneralException(trans('exceptions.backend.access.users.email_error'));
            }
        }
    }


  /**
   * Store multiple users with a specifed role
   *
   * @param Role $role
   * @param $data (array of data users)
   */
    public function storeMulti($role, $data) {
      return DB::transaction(function() use ($role, $data) {
        $users = [];
        foreach ($data as $user) {
          \Log::info(print_r($user, true));
//          $newUser = $this->create($user);
//          $newUser->assignRole($role);
//          $users[] = $newUser;
        }
        return $users;
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

  public function getByConditions($info = []) {
    $conditions = [
      'orderBy' => ($info['orderBy'] ? $info['orderBy'] : 'username'),
      'order' => ($info['order'] && in_array($info['order'], [ 'desc', 'asc' ]) ? $info['order'] : 'asc'),
      'perPage' => ($info['perPage'] && intval($info['perPage']) > 0 ? $info['perPage']: 10),
      'keyword' => ($info['keyword'] ? $info['keyword']: ''),
      'roles' => ($info['roles'] ? $info['roles'] : ''),
    ];
    $users = null;
//    $currentUser = auth()->user();
    if(!$conditions['roles']) {
      $users = $this
        ->with(['roles', 'permissions'])
//        ->whereNotIn('id', [ $currentUser->id ])
        ->where('username',"%{$conditions['keyword']}%", 'like')
        ->orWhere('first_name', 'like', "%{$conditions['keyword']}%")
        ->orWhere('last_name', 'like', "%{$conditions['keyword']}%")
        ->orWhere('code', 'like', "%{$conditions['keyword']}%")
        ->orWhere('email', 'like', "%{$conditions['keyword']}%")
        ->orderBy($conditions['orderBy'], $conditions['order'])
        ->paginate($conditions['perPage']);
    } else {
      $users = $this
        ->with(['roles', 'permissions'])
//        ->whereNotIn('id', [ $currentUser->id ])
        ->where('username',"%{$conditions['keyword']}%", 'like')
        ->orWhere('first_name', 'like', "%{$conditions['keyword']}%")
        ->orWhere('last_name', 'like', "%{$conditions['keyword']}%")
        ->orWhere('code', 'like', "%{$conditions['keyword']}%")
        ->orWhere('email', 'like', "%{$conditions['keyword']}%")
        ->role($conditions['roles'])
        ->orderBy($conditions['orderBy'], $conditions['order'])
        ->paginate($conditions['perPage']);
    }
    return $users;
  }

}
