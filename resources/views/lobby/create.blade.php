@extends('layouts.app')

@section('content')
    <div class="w-full sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl mx-auto p-4">
        <form action="{{ route('lobby.store') }}" method="POST">
            @csrf
            <input class="shadow appearance-none border rounded w-full py-2 px-3 bg-gray-700 text-gray-200 placeholder-gray-400 leading-tight focus:outline-none focus:shadow-outline" id="game-code" name="code" type="text" value="{{ strtoupper(\Illuminate\Support\Str::random(6)) }}" placeholder="Voer game lobby code in">
            <input class="shadow mt-4 appearance-none border rounded w-full py-2 px-3 bg-gray-700 text-gray-200 placeholder-gray-400 leading-tight focus:outline-none focus:shadow-outline" id="name" name="name" type="text" placeholder="Naam van de gamemaster">

            <button type="submit" class="mt-4 bg-white hover:bg-gray-100 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
                Aanmaken
            </button>
        </form>
    </div>
@endsection
