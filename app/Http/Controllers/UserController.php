<?php

namespace App\Http\Controllers;

use App\Http\Resources\User\UserResource;
use App\Repositories\User\UserRepository;
use Illuminate\Http\Request;

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
        $this->middleware('jwt.auth');
        $this->userRepository = $userRepository;
    }

    public function me()
    {
        return new UserResource(auth()->user());
    }
}
