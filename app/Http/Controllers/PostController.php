<?php

namespace App\Http\Controllers;

use App\Category;
use App\Http\Requests\Posts\CreatePostRequest;
use App\Http\Requests\Posts\UpdatePostRequest;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller
{
    public function __construct()
    {
        $this->middleware('verifyCategoriesCount')->only(['create','store']);
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Post::paginate();
        return view('posts.index')->with([
            'posts' => $posts,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('posts.create')->with('categories',Category::all())->with('tags',Tag::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreatePostRequest $request)
    {
        $image = $request->image->store('posts');
        
        $post = Post::create([
            'title' => $request->title,
            'description' => $request->description,
            'content' => $request->content,
            'category_id' => $request->category_id,
            'image'=> 'storage/'.$image,
            'published_at' => $request->published_at,
            'user_is' => auth()->user()->id
        ]);
        
        if($request->tags){
            $post->tags()->attach($request->tags);
        }
        session()->flash('message','Post created Successfully');
        return redirect(route('posts.index'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function show(Post $post)
    {
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function edit(Post $post)
    {
        return view('posts.create')->with([
            'post' => $post,
            'categories' => Category::all(),
            'tags' => Tag::all()
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePostRequest $request, Post $post)
    {
        $data = $request->only(['title','description','published_at','content','category_id']);

        if ($request->hasFile('image')) {
            $image = $request->image->store('posts');
            $post->deleteImage();
            $data['image'] = $image;
        }

        if($request->tags){
            $post->tags()->sync($request->tags);
        }

        $post->update($data);

        session()->flash('message','Post updated successfully');

        return redirect(route('posts.index'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $post = Post::withTrashed()->where('id',$id)->firstOrFail();
        
        if($post->trashed()){
            $post->deleteImage();
            $post->forceDelete();
        } else { 
            $post->delete();
        }
        
        session()->flash('message','Post trashed Successfully');
        
        return redirect(route('posts.index'));
    }

    /**
     * return the trashed post.
     *
     * @param  \App\Post  $post
     * @return \Illuminate\Http\Response
     */
    public function trashed()
    {
        $trashed = Post::onlyTrashed()->get();

        return view('posts.index')->with('posts',$trashed);
    }

    public function restore($id){

        $post = Post::withTrashed()->where('id',$id)->firstOrFail();
        $post->restore();

        session()->flash('message','Post restored Successfully');
        
        return redirect(route('posts.index'));
    }
}
