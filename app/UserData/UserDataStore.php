<?php

namespace App\UserData;

final class UserDataStore
{
    public function storeTokensAndUser($token, $user)
    {
        setcookie('refresh_token', $token->getRefreshToken(), time() + 604800, '/', '', false, true);
        setcookie('access_token', $token->getToken(), time() + 604800, '', '', false, false);
    }
}
