<?php

namespace App\Services;

use App\Models\User;
use App\Models\ManagerLocation;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserService
{
    public function createUser(User $authUser, array $data)
    {
        $this->validateRoleAndScope($authUser, $data);

        return DB::transaction(function () use ($authUser, $data) {
            $user = User::create([
                'organization_id' => $authUser->organization_id,
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'role' => $data['role'],
            ]);

            if ($data['role'] === 'manager' && !empty($data['location_ids'])) {
                $syncData = [];
                foreach ($data['location_ids'] as $locationId) {
                    $syncData[$locationId] = ['assigned_by' => $authUser->id];
                }
                $user->locations()->sync($syncData);
            }

            return $user;
        });
    }

    public function updateUser(User $userToUpdate, User $authUser, array $data)
    {
        $this->validateRoleAndScope($authUser, $data);

        return DB::transaction(function () use ($userToUpdate, $authUser, $data) {
            $updateData = [
                'name' => $data['name'],
                'email' => $data['email'],
                'role' => $data['role'],
            ];

            if (!empty($data['password'])) {
                $updateData['password'] = Hash::make($data['password']);
            }

            $userToUpdate->update($updateData);

            if ($data['role'] === 'manager') {
                $syncData = [];
                if (!empty($data['location_ids'])) {
                    foreach ($data['location_ids'] as $locationId) {
                        $syncData[$locationId] = ['assigned_by' => $authUser->id];
                    }
                }
                $userToUpdate->locations()->sync($syncData);
            } else {
                $userToUpdate->locations()->detach();
            }

            return $userToUpdate;
        });
    }

    protected function validateRoleAndScope(User $authUser, array $data): void
    {
        if ($authUser->role === 'manager') {
            if (($data['role'] ?? '') === 'admin') {
                abort(403, 'Managers cannot create or update admin users.');
            }
            
            $allowedLocationIds = $authUser->locations->pluck('id')->toArray();
            $requestedLocationIds = $data['location_ids'] ?? [];
            if (array_diff($requestedLocationIds, $allowedLocationIds)) {
                abort(403, 'Cannot assign users to locations outside your scope.');
            }
        }
    }
}
