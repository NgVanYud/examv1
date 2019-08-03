<?php

namespace App\Providers;

use App\Models\Subject;
use App\Models\SubjectTerm;
use App\Models\User;
use App\Policies\SubjectPolicy;
use App\Policies\SubjectTermPolicy;
use App\Policies\UserPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Log;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
      User::class => UserPolicy::class,
      Subject::class => SubjectPolicy::class,
      SubjectTerm::class => SubjectTermPolicy::class
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        Gate::define('view-chapters', function ($user, $subject) {
          $valid = $user->quizMakeSubjects->contains($subject->id);
          return $valid;
        });

        Gate::define('active-quiz', 'App\Policies\SubjectTermPolicy@handleQuiz');
        Gate::define('deactive-quiz', 'App\Policies\SubjectTermPolicy@handleQuiz');
    }
}
