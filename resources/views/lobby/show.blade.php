@extends('layouts.app')

@section('css')
    <style>
        canvas {
            width: 100% !important;
        }

        .canvas-container {
            width: 100% !important;
        }
    </style>
@endsection

@section('content')
    <div class="w-full sm:max-w-sm md:max-w-md lg:max-w-lg xl:max-w-xl mx-auto p-4">
        <h1 class="text-white font-bold text-center text-xl mb-4">Voer je antwoord in</h1>
        <canvas id="canvas" height="400" style="border:1px solid #000;"></canvas>

        <button type="button" id="submit" class="mt-4 bg-white hover:bg-gray-100 text-black font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
            Antwoord inleveren
        </button>
        <button type="button" id="clear" class="mt-2 bg-transparent hover:bg-white hover:text-black text-white border-2 border-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full">
            Canvas legen
        </button>
    </div>
@endsection

@section('scripts')
    <script>
        const canvas = new fabric.Canvas('canvas');
        let isDrawing = false;

        canvas.isDrawingMode = true;
        canvas.freeDrawingBrush.width = 3;
        canvas.freeDrawingBrush.color = "#ffffff";

        canvas.on('mouse:down', function(options) {
            isDrawing = true;
            canvas.renderAll();
        });

        canvas.on('mouse:move', function(options) {
            if (isDrawing) {
                canvas.renderAll();
            }
        });

        canvas.on('mouse:up', function(options) {
            isDrawing = false;
            canvas.renderAll();
        });

        $(document).ready(function(){
            $('#submit').click(function(){
                $.post("{{ route('lobby.submit') }}",
                    {
                        answer: canvas.toDataURL(),
                        _token: "{{ csrf_token() }}"
                    },
                    function(data, status){
                        canvas.clear();
                        console.log(data);
                    }
                );
            });

            $('#clear').click(function(){
                canvas.clear();
            });
        });
    </script>
@endsection
