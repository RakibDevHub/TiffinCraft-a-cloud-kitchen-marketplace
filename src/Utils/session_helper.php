<?php

namespace App\Utils;

use App\Core\Database;
use App\Models\Kitchen;
use App\Models\User;

class SessionHelper
{
    public static function refreshUserSession(): void
    {
        if (!empty($_SESSION['user_id'])) {
            $conn = Database::getConnection();
            $user = User::findById($conn, $_SESSION['user_id']);

            if (!$user)
                return;

            $isUserSuspended = false;
            $userSuspendedUntil = null;

            if (!empty($user['suspended_until'])) {
                $timestamp = strtotime($user['suspended_until']);
                if ($timestamp > time()) {
                    $isUserSuspended = true;
                    $userSuspendedUntil = $user['suspended_until'];
                }
            }

            $isKitchenSuspended = false;
            $kitchenSuspendedUntil = null;

            if ($user['role'] === 'seller') {
                $kitchen = Kitchen::getKitchenByOwnerId($conn, $user['user_id']);
                if ($kitchen && !empty($kitchen['suspended_until'])) {
                    $timestamp = strtotime($kitchen['suspended_until']);
                    if ($timestamp > time()) {
                        $isKitchenSuspended = true;
                        $kitchenSuspendedUntil = $kitchen['suspended_until'];
                    }
                }
            }

            $_SESSION['isUserSuspended'] = $isUserSuspended;
            $_SESSION['userSuspendedUntil'] = $userSuspendedUntil;
            $_SESSION['isKitchenSuspended'] = $isKitchenSuspended;
            $_SESSION['kitchenSuspendedUntil'] = $kitchenSuspendedUntil;
            $_SESSION['role'] = $user['role'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['email'] = $user['email'];
            $_SESSION['profile_image'] = $user['profile_image'];
        }
    }

}
