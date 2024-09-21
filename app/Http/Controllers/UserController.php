<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Interfaces\UserServiceInterface;

class UserController extends Controller
{
    protected $userService;
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        return $this->userService->getAllUsers();
    }

    public function store(StoreUserRequest $request)
    {
        $data = $request->validated();
        return $this->userService->createUser($data);
    }

    public function show(string $id)
    {
        return $this->userService->getUserById($id);
    }

    public function update(UpdateUserRequest $request, string $id)
    {
        $data = $request->validated();
        return $this->userService->updateUser($id, $data);
    }

    public function destroy(string $id)
    {
        return $this->userService->deleteUser($id);
    }
}