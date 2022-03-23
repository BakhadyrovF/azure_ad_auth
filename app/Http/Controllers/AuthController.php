<?php

namespace App\Http\Controllers;

use App\Models\AdminUser;
use App\UserData\UserDataStore;
use GuzzleHttp\Client;
use Illuminate\Http\Request;
use League\OAuth2\Client\Provider\GenericProvider;
use Microsoft\Graph\Graph;
use Microsoft\Graph\Model;
use Microsoft\Graph\Model\User;

class AuthController extends Controller
{
    public function signIn()
    {
        $provider = new GenericProvider([
            'clientId' => config('azure.client_id'),
            'clientSecret' => config('azure.client_secret'),
            'redirectUri' => config('azure.redirect_uri'),
            'urlAuthorize' => config('azure.authorize_uri') . config('azure.authorize_endpoint'),
            'urlAccessToken' => config('azure.authorize_uri') . config('azure.token_endpoint'),
            'urlResourceOwnerDetails' => '',
            'scopes' => config('azure.scopes')
        ]);

        $authUrl = $provider->getAuthorizationUrl();

        return redirect()->away($authUrl);
    }

    public function callback(Request $request)
    {
        $providedState = $request->query('state');
        $code = $request->query('code');
        $provider = new GenericProvider([
            'clientId' => config('azure.client_id'),
            'clientSecret' => config('azure.client_secret'),
            'redirectUri' => config('azure.redirect_uri'),
            'urlAuthorize' => config('azure.authorize_uri') . config('azure.authorize_endpoint'),
            'urlAccessToken' => config('azure.authorize_uri') . config('azure.token_endpoint'),
            'urlResourceOwnerDetails' => '',
            'scopes' => config('azure.scopes')
        ]);

        // if (!isset($providedState)) {
        //     return redirect('/login');
        // }

        //token request
        $accessToken = $provider->getAccessToken('authorization_code', [
            'code' => $code
        ]);
        $graph = new Graph();
        $graph->setAccessToken($accessToken);
        $user = $graph->createRequest('GET', '/me')
            ->setReturnType(User::class)
            ->execute();

        $model = AdminUser::query()
            ->updateOrCreate([
                'email' => $user->getUserPrincipalName()
            ], [
                'full_name' => $user->getdisplayName(),
                'token' => $accessToken->getToken()
            ]);

        $dataStore = new UserDataStore();
        $dataStore->storeTokensAndUser($accessToken, $user);


        return redirect()->route('home');
    }

    public function home(Request $request)
    {
        $token = $_COOKIE['access_token'];

        $user = AdminUser::query()
            ->where('token', '=', $token)
            ->first();

        return view('admin', compact('user'));
    }

    public function logout()
    {
        setcookie('access_token', null, time() + 2);
        setcookie('refresh_token', null, time() + 2);

        return redirect()->route('login');
    }
}
