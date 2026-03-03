<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Trello Board')</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-white border-b border-gray-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex">
                        <div class="flex-shrink-0 flex items-center">
                            <h1 class="text-xl font-bold text-gray-900">Trello Board</h1>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <span class="text-gray-700 mr-4">{{ Auth::user()->name }}</span>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="text-gray-600 hover:text-gray-900">
                                Logout
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </nav>

        <div class="flex">
            <!-- Sidebar -->
            @include('trello::components.sidebar')

            <!-- Main Content -->
            <main class="flex-1 p-6">
                @include('trello::components.flash-message')
                
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>
