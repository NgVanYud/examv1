<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Responses\JsonResponse;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    public $maxAttempts = 5;

    public $decayMinutes = 1;

    protected $additionalCondition = [
      'is_actived' => \App\Models\Auth\User::ACTIVED_CODE
    ];
    protected $userGroup = null;

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
    public function __construct()
    {

    }

    /**
     * Get a JWT via given credentials.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(Request $request)
    {
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if ($this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);
            return $this->sendLockoutResponse($request);
        }
        /**
         * Info user is valid
         * Token is created
         */
        $token = $this->attemptLogin($request);
        if ($token) {
            $this->clearLoginAttempts($request);
            return $this->respondWithToken($token);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);
        return $this->sendFailedLoginResponse($request);
    }

    /**
     * Get the token array structure.
     *
     * @param  string $token
     *
     * @return \Illuminate\Http\JsonResponse
     */
    protected function respondWithToken($token)
    {
        return new JsonResponse(__('messages.login.success'), [
                'access_token' => $token,
                'group' => $this->userGroup,
                'token_type' => 'bearer',
                'expires_in' => auth()->factory()->getTTL() * 60
            ], []
        );
    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function username()
    {
        return config('access.users.username', 'email');
    }

    public function attemptLogin($request) {
        if(Auth::guard('manager')->attempt($this->credentials($request))) {
          $this->userGroup = config('access.user_groups.manager');
          return $this->getGuard('manager')->attempt($this->credentials($request));
        } else if (Auth::guard('student')->attempt($this->credentials($request))) {
          $this->userGroup = config('access.user_groups.student');
          return $this->getGuard('student')->attempt($this->credentials($request));
        }
        return $this->getGuard('student')->attempt($this->credentials($request));
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateLogin(Request $request)
    {
        return Validator::make(
            $request->only(['username', 'password']),[
            $this->username() => "required|string|min:4|max:25",
            'password' => "required|string|min:4|max:255"
            ], [
                'required' => __('validation.required'),
                'string' => __('validation.login_form.invalid'),
                'min' => __('validation.login_form.invalid'),
            ]
        )->validate();
    }

    /**
     * Get the guard to be used during authentication.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function getGuard($guardName)
    {
        return Auth::guard($guardName);
    }

    /**
     * Get the needed authorization credentials from the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return array_merge($request->only([$this->username(), 'password']), $this->additionalCondition);
    }


}
