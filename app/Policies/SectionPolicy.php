<?php
namespace App\Policies;

use App\Models\RoleCode;
use App\Models\Section;
use App\Models\User;
use App\Policies\Base\BasePolicy;
use Illuminate\Auth\Access\Response;

/**
 * @extends parent<Section>
 */
class SectionPolicy extends BasePolicy
{
    public function __construct()
    {
        parent::__construct(Section::class, RoleCode::Admin, RoleCode::Evaluator, RoleCode::Hr);
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
    public function view(User $user, Section $section): Response
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
    public function update(User $user, Section $section): Response
    {
        return $this->isInRoles($user);
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Section $section): Response
    {
        return $this->isInRoles($user);
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Section $section): Response
    {
        //
        return $this->isInRoles($user);
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Section $section): Response
    {
        //
        return $this->isInRoles($user);
    }
}
