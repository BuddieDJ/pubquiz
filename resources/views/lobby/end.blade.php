@extends('layouts.app')

@section('content')
    <div class="w-full sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl mx-auto p-4">
        <h1 class="text-white text-4xl font-bold text-center">Dit was de MEUP Pubquiz</h1>
        <table class="w-full bg-white">
            <thead class="bg-gray-800 text-white">
            <tr>
                <th class="w-1/2 py-2 px-4 text-left" style="text-align: left !important;">Speler</th>
                <th class="w-1/2 py-2 px-4 text-right">Score</th>
            </tr>
            </thead>
            <tbody class="text-gray-700" id="table">
                @foreach ($stats as $player)
                    <tr class="border-t border-gray-200">
                        <td class="py-2 px-4 text-left">{{ $player['name'] }}</td>
                        <td class="py-2 px-4 text-right">{{ $player['score'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
