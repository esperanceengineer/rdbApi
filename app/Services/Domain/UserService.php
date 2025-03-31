<?php

namespace App\Services\Domain;

use App\Data\FileType;
use App\Exceptions\ModelException;
use App\Http\Requests\User\StoreUserRequest;
use App\Http\Requests\User\UpdateUserRequest;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserService extends Service
{
    public function getQuery(Request $request): Builder
    {
        $queryBuilder = User::query();

        $queryBuilder->when(
            $request->has('last_update'),
            fn(Builder $query) => $query->whereDate('updated_at', '>', Carbon::parse($request->last_update))
        );

        return $queryBuilder;
    }

    /**
     * @throws ModelException
     * @throws Exception
     */
    public function setUser(User $user, StoreUserRequest|UpdateUserRequest $request, bool $toCreate = true): Model
    {
        return $this->set($user, $request, function (User &$user) use ($toCreate, $request) {
            if ($toCreate) {
                $user->password = Hash::make('123456789012');
                $user->has_password_temp = true;
            }
            $user->is_locked = $user->is_locked ? 1 : 0;
            if ($file = $request->file('image')) {
                $user->addSimpleFile($file, FileType::USER_IMAGE);
            }
        });
    }

    public function deleteUser(User $user): void
    {
        $this->delete($user);
    }

    public function lock(User $user): Model
    {
        $user->is_locked = !$user->is_locked;
        $user->save();
        return $user;
    }

    public function resetPassword(User $user): Model
    {
        $user->password = Hash::make('123456789012');
        $user->has_password_temp = true;
        $user->save();
        return $user;
    }

    public function changePassword(User $user, Request $request): array
    {
        $result = array();

        $newPassword = $request->newPassword;
        $oldPassword = $request->oldPassword;
        $confirm = $request->confirmPassword;
        $check = Hash::check($oldPassword, $user->password);

        if ($check) {
            if ($newPassword == $confirm) {
                $user->password = Hash::make($newPassword);
                $user->has_password_temp = false;
                $user->save();

                $success = true;
                $message = 'Le mot de passe modifié avec succès';
            } else {
                $success = false;
                $message = 'Le mot de passe de confirmation et le nouveau mot de passe doivent être identiques.';
            }
        } else {
            $success = false;
            $message = 'Votre ancien mot de passe ne correspond pas.';
        }

        $result['success'] = $success;
        $result['message'] = $message;

        return $result;
    }
}
