<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Facades\Storage;

use App\User;
use App\Role;

/**
 * Class UserController
 * @package App\Http\Controllers
 */
class UserController extends Controller
{
    private $count_per_page = 5;

    private $order = 'id';
    private $sort = 'asc';

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function profile(Request $request) {
        $user = $request->user();

        return view('user.profile', array('user' => $user));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm(Request $request) {
        $user = $request->user();

        return view(
            'user.edit',
            array(
                'user' => $user,
                'src' => ($user->getMedia('avatar')->count() > 0)
                    ? $user->getMedia('avatar')->first()->getUrl('thumb')
                    : ''
            )
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(Request $request) {
        $user = $request->user();

        $rules = array(
            'name' => 'string|max:255',
            'login' => 'sometimes|nullable|string|max:255',
            'email' => 'required|string|email',
            'image' => 'nullable|file|image'
        );
        $this->validate($request, $rules);

        $user->name = $request->name;
        if ($user->login != $request->login) {
            $user->login = $request->login;
        }
        if ($user->email != $request->email) {
            $user->email = $request->email;
        }

        $user->save();

        if ($user->hasMedia('avatar')) {
            $mediaItems = $user->getMedia('avatar');
            foreach ($mediaItems as $mediaItem) {
                $mediaItem->delete();
            }
        }

        $mediaItems = $user->addMediaFromRequest('image')->toMediaCollection('avatar');

        return view('user.edit', array('user' => $user, 'src' => $mediaItems->getUrl('thumb'), 'message' => 'success update user data'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function usersList(Request $request) {
        $users= User::whereDoesntHave('roles', function ($query) {
            $query->where('name', 'admin');
        })
            ->orderBy($this->order, $this->sort)
            ->paginate($this->count_per_page);

        return view('user.list', array( 'users' => $users));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function giveRoleUser(Request $request) {

        $this->validate($request, array('id' => 'required|integer'));

        $user = User::find($request->id);

        if (!$user) {
            return redirect()->route('give_role')->withErrors(array('User is not found'));
        }

        if ($user->hasRole('moderator')) {
            $user->deleteRole('moderator');
            $request->session()->flash('success', $user->email . ' delete role moderator');
        }
        else {
            $user->giveRole('moderator');
            $request->session()->flash('success', $user->email . ' add role moderator');
        }

        return redirect()->route('give_role');
    }
}
