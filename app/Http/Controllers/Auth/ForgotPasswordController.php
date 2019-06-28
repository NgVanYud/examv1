<?php

namespace App\Http\Controllers\Auth;

use App\Exceptions\GeneralException;
use App\Http\Controllers\Controller;
use App\Repositories\User\ManagerRepository;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use Mockery\Exception;

class ForgotPasswordController extends Controller
{
    protected $managerRepository;

    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(
      ManagerRepository $managerRepository
    ){
      $this->managerRepository = $managerRepository;
      $this->middleware('guest');
    }

  /**
   * Get the broker to be used during password reset.
   *
   * @return \Illuminate\Contracts\Auth\PasswordBroker
   */
  public function broker()
  {
    return Password::broker('managers');
  }

  /**
   * Send a reset link to the given user.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
   */
  public function sendResetLinkEmail(Request $request)
  {
    $this->validateEmail($request);

    $email = $request->only('email');

    try {
      if ($this->managerRepository->getByColumn($email, 'email')) {
        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.
        $response = $this->broker()->sendResetLink($email);

        if (Password::RESET_LINK_SENT) {
          return response()->json([
            'error' => false,
            'message' => __('notifications.actions.send_email').' '.__('notifications.statuses.success'),
          ]);
        } else {
          throw new GeneralException(
            __('notifications.actions.send_email').' '.__('notifications.statuses.error'),
            502);
        }
      } else {
        throw new GeneralException(__('exceptions.invalid_data'), 422);
      }
    } catch (Exception $exception) {
      throw new GeneralException($exception->getMessage(), $exception->getCode());
    }
  }

}
