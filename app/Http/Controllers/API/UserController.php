<?php

namespace App\Http\Controllers\API;

use App\Http\Resources\User\UserCollection;
use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserController extends Controller
{
    public $userRepository;

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
    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function me()
    {
        return new UserResource(auth()->user());
    }

    public function index(Request $request) {
      $conditions = [
        'orderBy' => ($request->order_by ? $request->order_by : 'username'),
        'order' => ($request->order && in_array($request->order, [ 'desc', 'asc' ]) ? $request->order : 'asc'),
        'perPage' => ($request->limit && intval($request->limit) > 0 ? $request->limit: 10),
        'search' => ($request->search ? $request->search: ''),
        'roles' => ($request->roles ? $request->roles: ''),
      ];
      $users = null;
      if(!$conditions['roles']) {
        $users = $this->userRepository
          ->orderBy($conditions['orderBy'], $conditions['order'])
          ->paginate($conditions['perPage']);
      } else {
        $users = $this->userRepository
          ->role($conditions['roles'])
          ->orderBy($conditions['orderBy'], $conditions['order'])
          ->paginate($conditions['perPage']);
      }
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
  public function update(Request $request, User $user)
  {
    //
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Model\Chapter  $chapter
   * @return \Illuminate\Http\Response
   */
  public function destroy(User $user)
  {
    //
  }
}
