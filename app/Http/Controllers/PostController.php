<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePostRequest;
use App\Models\Photo;
use App\Models\Post;
use Cviebrock\EloquentSluggable\Services\SlugService;
use Facade\FlareClient\View;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PostController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth', ['except' => 'index','show']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
//        $posts = Post::orderBy('created_at','desc')->get();
        $posts = Post::with('photos')->orderBy('created_at','desc')->get();
        return view('blog.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

        return view('blog.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePostRequest $request)
    {
        $newImageName = uniqid() . '_' . $request->title . '.' . $request->image->extension();
        $request->image->move(public_path('images'),$newImageName);

        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'slug' =>   SlugService::createSlug(Post::class, 'slug', $request->title),
            'image_path' => $newImageName,
            'user_id' => auth()->user()->id
        ]);

        Photo::create([
            'imageable_id' => $post->id,
            'imageable_type' => 'App\Post',
            'filename' => $newImageName
        ]);

        return redirect(route('blog.index'))
            ->with('message','Your post has been added');

    }

    /**
     * @param $slug
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View|mixed
     */
    public function show($slug)
    {
        return view('blog.show')
            ->with('post', Post::where('slug', $slug)->first());
    }

    /**
     * @param $slug
     */
    public function edit($slug)
    {
        return view('blog.edit')
            ->with('post', Post::where('slug', $slug)->first());
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required'
        ]);
        Post::where('slug', $slug)
            ->update([
                'title' => $request->title,
                'description' => $request->description,
                'slug' =>   SlugService::createSlug(Post::class, 'slug', $request->title),
                'user_id' => auth()->user()->id
            ]);

        return redirect(route('blog.index'))->with('message', 'Your post has been updated');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
       $post =  Post::where('slug', $slug);
       $post->delete();

        return redirect(route('blog.index'))->with('message', 'Your post has been deleted');
    }

}

