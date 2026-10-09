<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * True when the current user is an admin or one of the given owner ids.
     */
    protected function isOwnerOrAdmin(...$ownerIds): bool
    {
        $user = auth()->user();
        if (!$user) {
            return false;
        }
        return $user->type === 'admin' || in_array($user->id, array_map('intval', array_filter($ownerIds, fn ($id) => $id !== null)), true);
    }

    protected function forbidden()
    {
        return response()->json(['message' => 'You are not allowed to perform this action'], 403);
    }
}
