@extends('layouts.app')

@section('content')
    @if(session()->has('message'))
        <div class="bg-teal-100 border-t-4 border-teal-500 rounded-b text-teal-900 px-4 py-3 shadow-md" role="alert">
            <div class="flex">
                <div>
                    <p class="font-bold"> {{ session()->get('message') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="w-4/5 m-auto text-center">
        <div class="py-15 border-b border-gray-200">
            <h1 class="text-6xl">Blog Posts</h1>
        </div>
    </div>



    @auth()
        <div class="pt-15 w-4/5 mx-auto">
            <a href="{{ route('blog.create') }}" class="bg-blue-500 bg-transparent text-gray-100 text-xs font-extrabold py-3 rounded-3xl px-5">
                Create Post
            </a>
        </div>
    @endauth


        @foreach($posts as $post)
            <div class="sm:grid grid-cols-2 gap-20 w-4/5 mx-auto py-15 border-b border-gray-200">
                <div>
                    <img src="{{ asset('images/'. $post->image_path) }}" alt="{{ $post->title }}">
                </div>
                <div>
                    <h2 class="text-gray-700 font-bold text-5xl pb-4"><a href="{{ route('blog.show', $post->slug) }}">{{ $post->title }}</a></h2>
                    <span class="text-gray-500">
            By <span class="font-bold italic text-gray-800"> {{ $post->user->name }}</span>, {{ $post->created_at->diffForHumans() }}
        </span>
                    <p class="text-xl text-gray-700 pt-8 pb-10 leading-8 font-light">
                        {{ $post->description }}
                    </p>
                    <a href="{{ route('blog.show', $post->slug) }}" class="uppercase bg-blue-500 text-gray-100 text-lg font-extrabold py-4 px-8 rounded-3xl">Keep Reading</a>

                    @if(isset(auth()->user()->id) && auth()->user()->id == $post->user_id)
                        <span class="float-right">
                            <a href="{{ route('blog.edit',$post->slug) }}" class="text-gray-700 italic hover:text-gray-900 pb-1 border-b-2">
                                Edit
                            </a>
                        </span>

                        <span class="float-right">
                            <form action="{{ route('blog.destroy', $post->slug) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="text-red-500 pr-3" type="submit">Delete</button>
                            </form>
                        </span>
                    @endif
                </div>
            </div>
        @endforeach
@endsection
