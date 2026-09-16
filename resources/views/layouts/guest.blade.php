<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - To Meet Cafe</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>

<body class="bg-[#f7f3ee] text-amber-950 font-sans antialiased min-h-screen flex items-center justify-center p-4">

    {{ $slot }}

    @livewireScripts
</body>

</html>