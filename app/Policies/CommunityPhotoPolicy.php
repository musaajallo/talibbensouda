<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CommunityPhoto;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CommunityPhotoPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CommunityPhoto');
    }

    public function view(AuthUser $authUser, CommunityPhoto $communityPhoto): bool
    {
        return $authUser->can('View:CommunityPhoto');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CommunityPhoto');
    }

    public function update(AuthUser $authUser, CommunityPhoto $communityPhoto): bool
    {
        return $authUser->can('Update:CommunityPhoto');
    }

    public function delete(AuthUser $authUser, CommunityPhoto $communityPhoto): bool
    {
        return $authUser->can('Delete:CommunityPhoto');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CommunityPhoto');
    }

    public function restore(AuthUser $authUser, CommunityPhoto $communityPhoto): bool
    {
        return $authUser->can('Restore:CommunityPhoto');
    }

    public function forceDelete(AuthUser $authUser, CommunityPhoto $communityPhoto): bool
    {
        return $authUser->can('ForceDelete:CommunityPhoto');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CommunityPhoto');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CommunityPhoto');
    }

    public function replicate(AuthUser $authUser, CommunityPhoto $communityPhoto): bool
    {
        return $authUser->can('Replicate:CommunityPhoto');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CommunityPhoto');
    }
}
