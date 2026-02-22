<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Admin - O'G Délice</title>
    @vite(['resources/css/app.css','resources/js/app.js'])
</head>

<body class="bg-gray-100">

    @include('menusPage.navBarAdmin')

        <main class="ml-64 min-h-screen p-10">
            @yield('content')
        </main>

</body>
</html>
