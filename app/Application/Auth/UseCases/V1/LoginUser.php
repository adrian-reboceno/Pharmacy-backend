<?php
#app/Application/Auth/UseCases/V1/LoginUser.php
namespace App\Application\Auth\UseCases\V1;

use App\Infrastructure\Services\JwtAuthService;
use Illuminate\Support\Facades\Auth;

class LoginUser
{
    private JwtAuthService $jwtAuthService;

    public function __construct(JwtAuthService $jwtAuthService)
    {
        $this->jwtAuthService = $jwtAuthService;
    }

    public function execute(array $credentials): array
    {
        $token = $this->jwtAuthService->attemptLogin($credentials);

        if (!$token) {
            throw new \Exception('Invalid credentials');
        }

        $user = Auth::user()->load('roles', 'permissions');

        return [
            'access_token'  => $token,
            'refresh_token' => $this->jwtAuthService->refresh(),
            'token_type'    => 'bearer',
            'expires_in'    => auth()->factory()->getTTL() * 60,
            'user'          => [
                'id'          => $user->id,
                'name'        => $user->name,
                'email'       => $user->email,
                'roles'       => $user->roles->pluck('name'),
                'permissions' => $user->permissions->pluck('name'),
            ],
        ];
    }
}
