<?php

namespace Innerent\Authentication\Http\Controllers;

use Nwidart\Modules\Routing\Controller;
use Innerent\Authentication\Models\User;
use Innerent\Authentication\Http\Requests\UserRequest;
use Innerent\Authentication\Services\UserService;

class UserController extends Controller
{
    protected $userService;

    function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function index()
    {
        $this->authorize('index', User::class);

        return response()->json(User::jsonPaginate(), 200);
    }

    public function store(UserRequest $request)
    {
        $this->authorize('create', User::class);

        $user = $this->userService->make($request->all());

        return response()->json($user->toArray(), 201);
    }

    public function show($id)
    {
        $user = $this->userService->get($id);

        $this->authorize('view', $user);

        return response()->json($user->toArray(), 200);
    }

    public function update(UserRequest $request, $id)
    {
        $user = $this->userService->get($id);

        $this->authorize('update', $user);

        $user = $this->userService->update($id, $request->all());

        return response()->json($user->toArray(), 200);
    }

    public function destroy($id)
    {
        $user = $this->userService->get($id);

        $this->authorize('delete', $user);

        $this->userService->delete($id);

        return response()->json(['message' => 'The user has been deleted'], 204);
    }

    public function loggedUser()
    {
        $user = $this->userService->get(auth()->user()->uuid);

        return response()->json($user->toArray(), 200);
    }
}
