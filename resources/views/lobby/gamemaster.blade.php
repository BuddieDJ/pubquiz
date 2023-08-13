@extends('layouts.app')

@section('modal')
    <div class="fixed top-0 left-0 w-full h-full flex items-center justify-center" id="myModal" style="display:none; background-color: rgba(0,0,0,0.5);">
        <div class="relative bg-white rounded-lg p-5 md:p-10 w-11/12 md:w-3/4 lg:w-1/2" id="modalContentArea">
            <button id="closeModal" class="absolute top-4 right-8 text-2xl">&times;</button>
            <h2 class="text-xl font-bold mb-4">Tussenstand</h2>
            <div id="modalContent">
                <table class="w-full bg-white">
                    <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="w-1/2 py-2 px-4 text-left" style="text-align: left !important;">Speler</th>
                        <th class="w-1/2 py-2 px-4 text-right">Score</th>
                    </tr>
                    </thead>
                    <tbody class="text-gray-700" id="table">

                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <div class="w-full mx-auto p-4">
        <div class="text-center text-white pt-40">
            <h1 class="text-4xl font-bold"></h1>

            <h1 class="text-4xl font-bold">MEUP Pubquiz</h1>

            <p class="text-xl">Code: {{ $lobby->code }}</p>

            <div class="flex justify-center">
                <form action="{{ route('gamemaster.next') }}" method="POST">
                    @csrf
                    <button type="submit" class="mt-4 mr-2 bg-white hover:bg-gray-100 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Volgende ronde (Ronde: {{ session('lobby')->round + 1 }})
                    </button>
                </form>
                <button id="openModalBtn" type="button" class="mt-4 mr-2 bg-blue-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                    Tussenstand
                </button>
                <form action="{{ route('gamemaster.end') }}" method="POST">
                    @csrf

                    <button type="submit" class="mt-4 bg-red-500 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                        Beëindigen
                    </button>
                </form>
            </div>
        </div>
        <div class="bg-gray-800 min-h-screen p-4">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4" id="grid">
                @foreach ($answers as $answer)
                    <div class="bg-gray-700 p-4 rounded answer border-2 @if ($answer['is_correct']) border-green-400 @else border-red-400 @endif" data-id="{{ $answer['id'] }}">
                        <img src="{{ $answer['answer'] }}" style="height: 300px; width: auto; display: block; margin: 0 auto;" alt="Beschrijving 1" class="w-full object-cover rounded">
                        <h2 class="text-gray-200 text-center font-bold text-xl mt-4">{{ $answer['name'] }}</h2>
                    </div>
                @endforeach
            </div>
        </div>

    </div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#myModal').on('click', function(e) {
                if (!$(e.target).closest('#modalContentArea').length) {
                    $('#myModal').hide();
                }
            });

            $('#openModalBtn').on('click', function() {
                $('#table').empty();
                $.get('{{ route('analytics') }}', function(data) {

                    data.forEach(function (player) {
                        let tr = `
                            <tr class="border-t border-gray-200">
                                <td class="py-2 px-4 text-left">${player.name}</td>
                                <td class="py-2 px-4 text-right">${player.score}</td>
                            </tr>
                        `;

                        $('#table').append(tr);
                    });

                    $('#myModal').show();
                });
            });

            $('#closeModal').on('click', function() {
                $('#myModal').hide();
            });

            $(document).on("click", ".answer", function() {
                let dataId = $(this).attr("data-id");

                $.post("{{ route('gamemaster.correct') }}",
                    {
                        answer_id: dataId,
                        _token: "{{ csrf_token() }}"
                    },
                    function(data, status){

                    }
                );
            });

            function fetchData() {
                $.get('{{ route('gamemaster.answers') }}', function(data) {
                    data.forEach(function(player) {

                        const grid = $('#grid');
                        grid.empty();

                        let correctClass = 'border-transparant'
                        if (player.is_correct != null && player.is_correct) {
                            correctClass = 'border-green-400'
                        } else if(player.is_correct != null && !player.is_correct) {
                            correctClass = 'border-red-400'
                        }

                        const cardHTML = `
                            <div class="bg-gray-700 p-4 rounded answer border-2 ${correctClass}" data-id="${player.id}">
                                <img src="${player.answer}" style="height: 300px; width: auto; display: block; margin: 0 auto;" alt="Beschrijving 1" class="w-full object-cover rounded">
                                <h2 class="text-gray-200 text-center font-bold text-xl mt-4">${player.name}</h2>
                            </div>
                        `;

                        grid.append(cardHTML);
                    })
                });
            }

            setInterval(fetchData, 1000);
        });
    </script>
@endsection
