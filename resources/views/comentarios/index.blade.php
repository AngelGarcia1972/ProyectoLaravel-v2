<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Comentarios &mdash; Comparaci&oacute;n: &#123;&#123; &#125;&#125; vs &#123;&#33;&#33; &#33;&#33;&#125;
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4 flex gap-4">
                <a href="{{ route('comentarios.create') }}" class="inline-block px-4 py-2 bg-blue-600 text-white rounded hover:bg-blue-700">
                    + Nuevo comentario (con CSRF)
                </a>
                <a href="{{ route('comentarios.sin-csrf') }}" class="inline-block px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                    + Nuevo comentario (SIN CSRF &mdash; demo)
                </a>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach ($comentarios as $comentario)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6 border-b border-gray-200">
                            <h3 class="font-bold text-lg">{{ $comentario->titulo }}</h3>
                            <p class="text-sm text-gray-500">{{ $comentario->email }} &middot; {{ $comentario->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        <div class="p-6">
                            <p class="text-xs uppercase tracking-wider text-gray-400 mb-2">Seguro (Blade escapado &mdash; &#123;&#123; &#125;&#125;)</p>
                            <div class="p-3 bg-green-50 border border-green-200 rounded mb-4">
                                {{ $comentario->contenido }}
                            </div>

                            <p class="text-xs uppercase tracking-wider text-gray-400 mb-2">Peligroso (HTML sin escape &mdash; &#123;&#33;&#33; &#33;&#33;&#125;)</p>
                            <div class="p-3 bg-red-50 border border-red-200 rounded">
                                {!! $comentario->contenido !!}
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>

            @if ($comentarios->isEmpty())
                <p class="text-center text-gray-500 py-12">No hay comentarios todav&iacute;a.</p>
            @endif
        </div>
    </div>
</x-app-layout>
