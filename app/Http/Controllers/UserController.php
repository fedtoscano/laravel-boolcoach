<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Http\Requests\UpdateUserRequest;
use App\Models\Game;
use Illuminate\Http\Request;

class UserController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index(User $users)
    {
        $users = $users->all()->where('id', 'Auth::id()');

        return view('users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = new User;
        return view('users.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user, Game $games)
    {
        if (auth()->id() === $user->id) {
            $games = Game::all();
            return view('users.edit', compact('user', 'games'));
        } else {
            return 'Non puoi modificare gli account di altri utenti';
        }
    }

    /**
     *
     * Update the specified resource in storage.
     */
    public function update(UpdateUserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->hasFile('img_url')) {
            $file = $request->file('img_url');
            $imageName = time() . '.' . $file->extension();
            $file->storeAs('images', $imageName, 'public');
            $data['img_url'] = 'images/' . $imageName;
        }

        $user->update($data);

        if (isset($data['games'])) {
            $user->games()->sync($data['games']);
        }

        return redirect()->route('users.show', $user);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {

        if (auth()->id() === $user->id) {
            $user->delete();
            return redirect()->route('users.index');
        } else {
            return 'Non puoi cancellare gli account altrui';
        }
    }
}
