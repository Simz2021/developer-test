<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\UserRepositoryInterface;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    protected $userRepository;

    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function index()
    {
        $heroImage = asset('storage/hero.jpg');

        $users = $this->userRepository->findAll();
        return view('users.index', ['heroImage' => $heroImage, 'users' => $users]);
    }

    public function store(Request $request)
    {
        try {
            $request->validate([
                'name' => 'required',
                'surname' => 'required',
                'email' => 'required|email',
                'position' => 'required',
            ]);

            $this->userRepository->create([
                'name' => $request->name . ' ' . $request->surname,
                'email' => $request->email,
                'position' => $request->position,
            ]);

            return response()->json(['success' => true, 'message' => 'User added successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }

    public function destroy(User $user)
    {
        try {
            $this->userRepository->delete($user->id);
            return response()->json(['success' => true, 'message' => 'User deleted successfully']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()]);
        }
    }
}
