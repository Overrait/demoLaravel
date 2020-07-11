<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Link;

/**
 * Class LinkController
 * @package App\Http\Controllers
 */
class LinkController extends Controller
{
    private $count_per_page = 5;

    private $order = 'id';
    private $sort = 'asc';

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function list(Request $request) {
        $links = Link::orderBy($this->order, $this->sort)
            ->paginate($this->count_per_page);

        return view(
            'short_links.list',
            array(
                'links' => $links
            )
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function userLinkList(Request $request) {
        $user = $request->user();

        if(!$user) {
            abort(404);
        }

        $links = Link::where('user_id', $user->id)
            ->orderBy($this->order, $this->sort)
            ->paginate($this->count_per_page);

        return view(
            'short_links.list',
            array(
                'links' => $links
            )
        );
    }

    /**
     * @param $id
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function link($id) {
        $link = Link::where(array(
            array('short_link', $id),
            array('active', true)
        ))->first();

        if (!$link) {
            abort(404);
        }

        return redirect($link->link);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function addForm(Request $request) {
        return view('short_links.add');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function add(Request $request) {
        $rules = array(
            'link' => 'required|url|unique:App\Link,link'
        );
        $this->validate($request, $rules);

        do {
            $link = Link::firstOrNew(array('link' => $request->link, 'short_link' => Str::random(8)));
        } while(!$link);

        $user = $request->user();
        if($user) {
            $link->user_id = $user->id;
        }

        $link->save();

        $request->session()->flash(
            'success',
            'You short link is generated! <a href="'
                .route('short_link', array('id' => $link->short_link))
                .'">you short link ('.route('short_link', array('id' => $link->short_link)).')</a><br />'
                .'It will not be active until moderated.'
        );
        return redirect()->route('short_links');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function editForm(Request $request, $id) {
        $user = $request->user();

        $link = Link::where(array(
            array('short_link', $id),
            array('user_id', $user->id)
        ))->first();

        if (!$link) {
            abort(404);
        }

        return view('user.short_link.edit', array('link' => $link->link, 'short_link' => $link->short_link));
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function edit(Request $request, $id) {
        $user = $request->user();

        $link = Link::where(array(
            array('short_link', $id),
            array('user_id', $user->id)
        ))->first();

        if (!$link) {
            abort(404);
        }

        $rules = array(
            'link' => 'required|url|unique:App\Link,link'
        );
        $this->validate($request, $rules);

        $link->link = $request->link;
        $link->active = false;
        $link->save();

        $request->session()->flash(
            'success',
            'You short link is update! <a href="'
            .route('short_link', array('id' => $link->short_link))
            .'">you short link ('.route('short_link', array('id' => $link->short_link)).')</a><br />'
            .'It will not be active until moderated.'
        );
        return redirect()->route('user_link');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function delete(Request $request, $id) {
        $user = $request->user();

        $link = Link::where(array(
            array('short_link', $id),
            array('user_id', $user->id)
        ))->delete();

        $request->session()->flash(
            'success',
            'You short link is delete!'
        );
        return redirect()->route('user_link');
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function moderateLinkList(Request $request) {
        $links = Link::where(function ($query) use ($request) {
            $query
                ->where('user_id', '<>', $request->user()->id)
                ->orwhereNull('user_id');
        })
            ->orderBy($this->order, $this->sort)
            ->paginate($this->count_per_page);

        return view(
            'short_links.list',
            array(
                'links' => $links
            )
        );
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function activateLink(Request $request, $id) {
        $link = Link::where(array(
            array('short_link', $id),
            array('active', false)
        ))->first();

        if (!$link) {
            abort(404);
        }

        $link->active = true;
        $link->save();

        $request->session()->flash(
            'success',
            'Link ' . $link->short_link . ' activated!'
        );
        return redirect()->route('moderate_links');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deactivateLink(Request $request, $id) {
        $link = Link::where(array(
            array('short_link', $id),
            array('active', true)
        ))->first();

        if (!$link) {
            abort(404);
        }

        $link->active = false;
        $link->save();

        $request->session()->flash(
            'success',
            'Link ' . $link->short_link . ' deactivated!'
        );
        return redirect()->route('moderate_links');
    }

    /**
     * @param Request $request
     * @param $id
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function deleteLink(Request $request, $id) {
        $link = Link::where('short_link', $id)->delete();

        if (!$link) {
            abort(404);
        }

        return view('user.short_link.message', array('message' => 'Short link is delete'));
    }
}
