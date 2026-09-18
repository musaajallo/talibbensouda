<?php

declare(strict_types=1);

namespace App\Policies;

use App\Models\CampaignSignup;
use Illuminate\Auth\Access\HandlesAuthorization;
use Illuminate\Foundation\Auth\User as AuthUser;

class CampaignSignupPolicy
{
    use HandlesAuthorization;

    public function viewAny(AuthUser $authUser): bool
    {
        return $authUser->can('ViewAny:CampaignSignup');
    }

    public function view(AuthUser $authUser, CampaignSignup $campaignSignup): bool
    {
        return $authUser->can('View:CampaignSignup');
    }

    public function create(AuthUser $authUser): bool
    {
        return $authUser->can('Create:CampaignSignup');
    }

    public function update(AuthUser $authUser, CampaignSignup $campaignSignup): bool
    {
        return $authUser->can('Update:CampaignSignup');
    }

    public function delete(AuthUser $authUser, CampaignSignup $campaignSignup): bool
    {
        return $authUser->can('Delete:CampaignSignup');
    }

    public function deleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('DeleteAny:CampaignSignup');
    }

    public function restore(AuthUser $authUser, CampaignSignup $campaignSignup): bool
    {
        return $authUser->can('Restore:CampaignSignup');
    }

    public function forceDelete(AuthUser $authUser, CampaignSignup $campaignSignup): bool
    {
        return $authUser->can('ForceDelete:CampaignSignup');
    }

    public function forceDeleteAny(AuthUser $authUser): bool
    {
        return $authUser->can('ForceDeleteAny:CampaignSignup');
    }

    public function restoreAny(AuthUser $authUser): bool
    {
        return $authUser->can('RestoreAny:CampaignSignup');
    }

    public function replicate(AuthUser $authUser, CampaignSignup $campaignSignup): bool
    {
        return $authUser->can('Replicate:CampaignSignup');
    }

    public function reorder(AuthUser $authUser): bool
    {
        return $authUser->can('Reorder:CampaignSignup');
    }
}
