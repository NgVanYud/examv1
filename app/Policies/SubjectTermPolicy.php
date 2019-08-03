<?php

namespace App\Policies;

use App\Models\Manager;
use App\Models\SubjectTerm;
use Illuminate\Auth\Access\HandlesAuthorization;

class SubjectTermPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view the subject term.
     *
     * @param  \App\Models\Manager  $user
     * @param  \App\Models\SubjectTerm  $subjectTerm
     * @return mixed
     */
    public function view(Manager $user, SubjectTerm $subjectTerm)
    {
        //
    }

    /**
     * Determine whether the user can create subject terms.
     *
     * @param  \App\Models\Manager  $user
     * @return mixed
     */
    public function create(Manager $user)
    {
        //
    }

    /**
     * Determine whether the user can update the subject term.
     *
     * @param  \App\Models\Manager  $user
     * @param  \App\Models\SubjectTerm  $subjectTerm
     * @return mixed
     */
    public function update(Manager $user, SubjectTerm $subjectTerm)
    {
        //
    }

    /**
     * Determine whether the user can delete the subject term.
     *
     * @param  \App\Models\Manager  $user
     * @param  \App\Models\SubjectTerm  $subjectTerm
     * @return mixed
     */
    public function delete(Manager $user, SubjectTerm $subjectTerm)
    {
        //
    }

    /**
     * Determine whether the user can restore the subject term.
     *
     * @param  \App\Models\Manager  $user
     * @param  \App\Models\SubjectTerm  $subjectTerm
     * @return mixed
     */
    public function restore(Manager $user, SubjectTerm $subjectTerm)
    {
        //
    }

    /**
     * Determine whether the user can permanently delete the subject term.
     *
     * @param  \App\Models\Manager  $user
     * @param  \App\Models\SubjectTerm  $subjectTerm
     * @return mixed
     */
    public function forceDelete(Manager $user, SubjectTerm $subjectTerm)
    {
        //
    }

    public function handleQuiz(Manager $user, SubjectTerm $subjectTerm) {
      $hasRole = $user->hasRole(config('access.roles_list.protor'));
      $validRole = $subjectTerm->protors()->where('protor_id', $user->id)->exists();
      return $hasRole && $validRole;
    }
}
