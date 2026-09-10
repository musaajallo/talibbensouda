<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\GivingProgramme;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class GivingProgrammePolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:GivingProgramme');
    }

    public function view(AuthUser $authUser, GivingProgramme $givingProgramme): bool
    {
        return $authUser->can('View:GivingProgramme');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:GivingProgramme');
    }

    public function update(AuthUser $authUser, GivingProgramme $givingProgramme): bool
    {
        return $authUser->can('Update:GivingProgramme');
    }

    public function delete(AuthUser $authUser, GivingProgramme $givingProgramme): bool
    {
        return $authUser->can('Delete:GivingProgramme');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:GivingProgramme');
    }

    public function restore(AuthUser $authUser, GivingProgramme $givingProgramme): bool
    {
        return $authUser->can('Restore:GivingProgramme');
    }

    public function forceDelete(AuthUser $authUser, GivingProgramme $givingProgramme): bool
    {
        return $authUser->can('ForceDelete:GivingProgramme');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:GivingProgramme');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:GivingProgramme');
    }

    public function replicate(AuthUser $authUser, GivingProgramme $givingProgramme): bool
    {
        return $authUser->can('Replicate:GivingProgramme');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:GivingProgramme');
    }
}
