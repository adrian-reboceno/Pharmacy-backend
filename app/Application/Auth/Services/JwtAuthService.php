<?php
# app/Application/Auth/Services/JwtAuthService.php
namespace App\Application\Auth\Services;

use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\Entities\User as UserEntity;
use App\Presentation\Exceptions\V1\Auth\InvalidCredentialsException;
use App\Presentation\Exceptions\V1\Auth\UserNotAuthenticatedException;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Auth;

class JwtAuthService
{
    protected ?UserEntity $user = null;
    protected UserRepositoryInterface $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Login con JWT y retorno de datos listos para Controller
     *
     * @throws InvalidCredentialsException
     */
    public function attemptLogin(array $credentials): array
    {
        if (!Auth::attempt($credentials)) {
            throw new InvalidCredentialsException();
        }

        $eloquentUser = Auth::user();
        $this->user = $this->userRepository->findByEmail($eloquentUser->email);

        $claims = [
            'permissions' => $this->user->permissions,
            'role' => $this->user->role,
        ];

        $token = JWTAuth::claims($claims)->fromUser($eloquentUser);

        return [
            'token' => $token,
            'user' => $this->user,
            'roles' => $this->user->role ? [$this->user->role] : [],
            'permissions' => $this->user->permissions,
        ];
    }

    /**
     * Retorna la Entity User siempre cargada.
     *
     * @throws UserNotAuthenticatedException
     */
    public function user(): UserEntity
    {
        if ($this->user) {
            return $this->user;
        }

        $eloquentUser = Auth::user();

        if (!$eloquentUser) {
            throw new UserNotAuthenticatedException('No hay usuario autenticado.');
        }

        $this->user = $this->userRepository->findByEmail($eloquentUser->email);

        if (!$this->user) {
            throw new \RuntimeException('No se pudo cargar la entidad User.');
        }

        return $this->user;
    }

    /**
     * Refrescar token JWT
     *
     * @throws UserNotAuthenticatedException
     */
    public function refreshToken(): array
    {
        $token = Auth::refresh();

        $eloquentUser = Auth::user();
        if (!$eloquentUser) {
            throw new UserNotAuthenticatedException('No hay usuario autenticado para refrescar token.');
        }

        $this->user = $this->userRepository->findByEmail($eloquentUser->email);

        return [
            'token' => $token,
            'user' => $this->user,
            'roles' => $this->user->role ? [$this->user->role] : [],
            'permissions' => $this->user->permissions,
        ];
    }

    /**
     * Logout e invalidación del token actual
     */
    public function logout(): void
    {
        Auth::logout();
        $this->user = null;
    }

    /**
     * Estructura base de respuesta con token para el Controller
     */
    public function baseTokenResponse(string $token, string $message = 'Autenticación exitosa'): array
    {
        return [
            'access_token' => $token,
            'token_type' => 'bearer',
            'expires_in' => Auth::guard('api')->factory()->getTTL() * 60,
            'message' => $message,
        ];
    }
}
