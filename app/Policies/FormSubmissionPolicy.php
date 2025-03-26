<?php
namespace App\Policies;

use App\Models\FormSubmission;
use App\Models\RoleCode;
use App\Models\User;
use App\Policies\Base\BasePolicy;
use Illuminate\Auth\Access\Response;

/**
 * @extends parent<FormSubmission>
 */
class FormSubmissionPolicy extends BasePolicy
{
    public function __construct()
    {
        parent::__construct(FormSubmission::class, RoleCode::Admin, RoleCode::HumanResourcesStaff);
    }

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): Response
    {
        return $this->isInRoles($user);
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, FormSubmission $formSubmission): Response
    {
        return $this->isInRoles($user);
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): Response
    {
        return $this->isInRoles($user);
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, FormSubmission $formSubmission): Response
    {
        return $this->isInRoles($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, FormSubmission $formSubmission): Response
    {
        return $this->isInRoles($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, FormSubmission $formSubmission): Response
    {
        //
        return $this->isInRoles($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, FormSubmission $formSubmission): Response
    {
        //
        return $this->isInRoles($user);
    }

    public function viewEvaluator(User $user): Response
    {
        return $this->isInRoles($user, RoleCode::Admin, RoleCode::Dean, RoleCode::HumanResourcesStaff);
    }

    public function viewEvaluatorRole(User $user): Response
    {

        return $this->isInRoles($user, RoleCode::Admin, RoleCode::HumanResourcesStaff, RoleCode::Dean);
    }

    public function export(User $user)
    {
        return $this->isInRoles($user, RoleCode::Admin, RoleCode::Dean, RoleCode::HumanResourcesStaff, RoleCode::Registrar, RoleCode::Teacher);
    }
}
