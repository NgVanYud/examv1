<?php

namespace App\Http\Controllers\API;

use App\Exceptions\GeneralException;
use App\Http\Resources\Role\RoleResource;
use App\Http\Resources\User\UserResource;
use App\Repositories\Role\RoleRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class RoleController extends Controller
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
  }


  /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
      return RoleResource::collection($this->roleRepository->get());
    }

  /**
   * @param $roles (an array of ids)
   * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
   *
   * Get roles except a specified roles
   *
   */
    public function getExcept($roles) {
      return $this->roleRepository->getExcept($roles);
    }

    public function getTeacher() {
      $studentRoles = $this->roleRepository->getByColumn(config('access.roles_list.student'), 'name');
      $adminRole = $this->roleRepository->getByColumn(config('access.roles_list.admin'), 'name');
      $except = [ $studentRoles->id, $adminRole->id ];
      return RoleResource::collection($this->getExcept($except));
    }

  /**
   * Get user by role name
   * @param Request $request
   * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
   * @throws GeneralException
   */
    public function getUsersByRole(Request $request) {
      $roleInfo = $request->role_name;
      $role = $this->roleRepository->getByColumn($roleInfo, 'name');

      $conditions = [
        'orderBy' => ($request->order_by ? $request->order_by : 'username'),
        'order' => ($request->order && in_array($request->order, [ 'desc', 'asc' ]) ? $request->order : 'asc'),
        'perPage' => ($request->limit && intval($request->limit) > 0 ? $request->limit: 10),
        'keyword' => ($request->keyword ? $request->keyword: ''),
        'exceptUsers' => ($request->except_users ? $request->except_users: [])
      ];
      $users = $role->users()
        ->whereNotIn('uuid', $conditions['exceptUsers'])
        ->orderBy($conditions['orderBy'], $conditions['order'])
        ->paginate($conditions['perPage']);
      return UserResource::collection($users);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

}
