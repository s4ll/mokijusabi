<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Kasir</title>
    @vite('resources/css/app.css', 'resources/js/app.js')
    <script src="{{ asset('tailwindcharts/js/apexcharts.js') }}"></script>
    <script src="{{ asset('tailwindcharts/js/flowbite.min.js') }}"></script>
</head>
<body>
    <x-sidebar/>
    <div class="p-4 sm:ml-64 bg-white h-screen">
        <div class="px-4">
            <div class="mt-1">
               {{ $slot }}
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/flowbite@3.1.2/dist/flowbite.min.js"></script>
</body>
</html>