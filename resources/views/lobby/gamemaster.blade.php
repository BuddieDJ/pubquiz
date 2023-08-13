@extends('layouts.app')

@section('content')
    <div class="w-full sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl mx-auto p-4">
        <div class="bg-gray-800 min-h-screen p-4">
            <div class="grid grid-cols-4 gap-4" id="grid">

                @foreach ($players as $player)
                <div class="bg-gray-700 p-4 rounded">
                    <img src="{{ $player->latestAnswer->answer }}" style="height: 300px; width: auto; display: block; margin: 0 auto;" alt="Beschrijving 1" class="w-full object-cover rounded">
                    <h2 class="text-gray-200 text-center mt-2">{{ $player->name }}</h2>
                </div>
                @endforeach

            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            function fetchData() {
                $.get('{{ route('gamemaster.answers') }}', function(data) {
                    data.data.forEach(function(player) {

                        const grid = $('#grid');
                        grid.empty();

                        const cardHTML = `
                            <div class="bg-gray-700 p-4 rounded">
                                <img src="${player.answer}" style="height: 300px; width: auto; display: block; margin: 0 auto;" alt="Beschrijving 1" class="w-full object-cover rounded">
                                <h2 class="text-gray-200 text-center mt-2">${player.name}</h2>
                            </div>
                        `;

                        grid.append(cardHTML);
                    })
                });
            }

            setInterval(fetchData, 5000);
        });
    </script>
@endsection
