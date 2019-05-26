<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GeneralException;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\User\UserCollection;
use Illuminate\Validation\ValidationException;
use phpDocumentor\Reflection\Types\Boolean;
use Validator;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\Role\RoleRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public $userRepository;
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
    public function __construct(UserRepository $userRepository, RoleRepository $roleRepository)
    {
        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }

    public function me()
    {
        return new UserResource(auth()->user());
    }

    public function index(Request $request) {
      $this->authorize('viewAll', User::class);
      $conditions = [
        'orderBy' => ($request->order_by ? $request->order_by : 'username'),
        'order' => ($request->order && in_array($request->order, [ 'desc', 'asc' ]) ? $request->order : 'asc'),
        'perPage' => ($request->limit && intval($request->limit) > 0 ? $request->limit: 10),
        'search' => ($request->search ? $request->search: ''),
        'roles' => ($request->roles ? $request->roles: ''),
      ];
      $users = null;
      $keyword = $request->keyword;
      if(!$conditions['roles']) {
        $users = $this->userRepository
          ->with(['roles', 'permissions'])
          ->where('username',"%{$keyword}%", 'like')
          ->orWhere('first_name', 'like', "%{$keyword}%")
          ->orWhere('last_name', 'like', "%{$keyword}%")
          ->orWhere('code', 'like', "%{$keyword}%")
          ->orWhere('email', 'like', "%{$keyword}%")
          ->orderBy($conditions['orderBy'], $conditions['order'])
          ->paginate($conditions['perPage']);
      } else {
        $users = $this->userRepository
          ->with(['roles', 'permissions'])
          ->where('username',"%{$keyword}%", 'like')
          ->orWhere('first_name', 'like', "%{$keyword}%")
          ->orWhere('last_name', 'like', "%{$keyword}%")
          ->orWhere('code', 'like', "%{$keyword}%")
          ->orWhere('email', 'like', "%{$keyword}%")
          ->role($conditions['roles'])
          ->orderBy($conditions['orderBy'], $conditions['order'])
          ->paginate($conditions['perPage']);
      }
      return UserResource::collection($users);
    }

    public function getTeacher(Request $request) {
      $studentRoles = $this->roleRepository->getByColumn(config('access.roles_list.student'), 'name');
      $roleIds = $request->roles ? $request->roles : ($this->roleRepository->getExcept([$studentRoles->id])->pluck('id'));

      $this->authorize('viewAll', User::class);
      $conditions = [
        'orderBy' => ($request->order_by ? $request->order_by : 'username'),
        'order' => ($request->order && in_array($request->order, [ 'desc', 'asc' ]) ? $request->order : 'asc'),
        'perPage' => ($request->limit && intval($request->limit) > 0 ? $request->limit: 10),
        'keyword' => ($request->keyword ? $request->keyword: ''),
        'roles' => ($request->roles ? $request->roles: $roleIds),
      ];

      return UserResource::collection($this->userRepository->getByConditions($conditions));
    }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
    $data = $request->all();
    return $data;
//    $role = $data->role
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Model\Chapter  $chapter
   * @return \Illuminate\Http\Response
   */
  public function show(User $user)
  {
    return new UserResource($user);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \App\Model\Chapter  $chapter
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateUserRequest $request, User $user)
  {
    return $this->userRepository->update($user, $request->all());
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Model\Chapter  $chapter
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user)
  {
    $this->userRepository->deleteByUuid($user->uuid);
  }

  public function storeMulti(Request $request) {
    $data = json_decode($request->getContent(), true);
    $users = $data['users'];
    if($this->checkUnique($users, 'username')) {
      throw new GeneralException('Username người dùng không được trùng nhau', 400);
    } else if($this->checkUnique($users, 'code')) {
      throw new GeneralException('Mã số người dùng không được trùng nhau', 400);
    } else {
      try {
        $this->validate($request, [
          'users' => 'required|array',
          'users.*.first_name' => 'required|string|min:1|max:254',
          'users.*.last_name' => 'required|string|min:1|max:254',
          'users.*.username' => 'required|min:3|max:25|unique:users,username',
          'users.*.code' => 'required|min:3|max:19|unique:users,code',
        ]);
      } catch (ValidationException $exception) {
        throw new GeneralException ('Dữ liệu không hợp lệ vui lòng kiểm tra lại', 400);
      } catch (\Exception $exception) {
        throw new GeneralException ('Xảy ra lỗi. Vui lòng thử lại sau', 400);
      }
    }
    $roles = $data['roles'];
    return $roles;
//    return $roles;
//    $role = $this->roleRepository->getById();
    $this->userRepository->storeMulti($role, $request->getContent());
  }

  /**
   * Check if a value in array is duplicated
   *
   * @param $arr
   * @param $key
   *
   * @return Boolean
   */
  public function checkUnique($arr, $key) {
    $arrLength = count($arr);
    $duplicated = false;
    for($i = 0; $i < $arrLength - 1; $i++) {
      for($j = 1; $j < $arrLength; $j++) {
        if($arr[$i][$key] == $arr[$j][$key]) {
          $duplicated = true;
          break;
        }
      }
      if($duplicated) {
        break;
      }
    }
    return $duplicated;
  }

  public function active(Request $request) {
    $uuid = $request->uuid;
    $user = $this->userRepository->updateByUuid($uuid, [ 'active' => User::ACTIVE_CODE ]);
    return $user;
  }

  public function deactive(Request $request) {
    $uuid = $request->uuid;
    $user = $this->userRepository->updateByUuid($uuid, [ 'active' => User::DEACTIVE_CODE ]);
    return $user;
  }
}
