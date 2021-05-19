@extends('layouts.app')

@section('content')
    <div class="w-4/5 m-auto text-left">
        <div class="py-15">
            <h1 class="text-6xl">{{ $post->title }}</h1>
        </div>
        <div>
            <img src="{{ asset('images/'. $post->image_path) }}" alt="{{ $post->title }}" style="height: 200px!important;">
        </div>
    </div>

    <div class="w-4/5 m-auto pt-20">
        <span class="text-gray-500">
            By <span class="font-bold italic text-gray-800">{{ $post->user->name }}</span>, created {{ $post->created_at->diffForHumans() }}
        </span>

        <p class="text-xl text-gray-700 pt-8 pb-8 leading-8 font-light">
            {{ $post->description }}
        </p>
    </div>
@endsection
