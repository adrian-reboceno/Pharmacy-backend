<?php
#app/Application/Auth/UseCases/V1/LoginUser.php
/*namespace App\Application\Auth\UseCases\V1;

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
*/


# app/Application/Auth/UseCases/LoginUser.php
namespace App\Application\Auth\UseCases\V1;

use App\Application\Auth\Services\JwtAuthService;
use App\Presentation\DTOs\V1\UserDTO;
use App\Presentation\Exceptions\V1\Auth\InvalidCredentialsException;

class LoginUser
{
    protected JwtAuthService $jwtService;

    public function __construct(JwtAuthService $jwtService)
    {
        $this->jwtService = $jwtService;
    }

    /**
     * Ejecuta el caso de uso de login
     *
     * @param array $credentials ['email' => string, 'password' => string]
     * @return array
     * @throws InvalidCredentialsException
     */
    public function execute(array $credentials): array
    {
        // Login vía servicio de autenticación
        $data = $this->jwtService->attemptLogin($credentials);

        // Convertir la entity User a DTO para respuesta limpia
        $userDTO = UserDTO::fromEntity($data['user']);
        $data['user'] = $userDTO->toArray();

        return $data; // incluye token, roles y permisos
    }
}
