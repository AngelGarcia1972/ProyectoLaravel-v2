<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Demostración XSS — Ruta SIN protección
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            @if (session('success'))
                <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 border-b border-gray-200 bg-yellow-50">
                    <h3 class="font-semibold text-lg text-yellow-800">Formulario SIN PROTECCIÓN (demo aislada)</h3>
                    <p class="text-sm text-yellow-700">
                        Esta ruta está <strong>excluida del middleware CSRF</strong> y el controlador
                        <strong>no aplica strip_tags</strong> al contenido.<br>
                        <strong>ADVERTENCIA:</strong> Solo para demostrar XSS almacenado en entorno controlado.
                    </p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('comentarios.store.sinCsrf') }}">
                        {{-- Intencionadamente sin @csrf — la ruta está excluida del middleware --}}

                        <div class="mb-4">
                            <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                        </div>

                        <div class="mb-4">
                            <label for="contenido" class="block text-sm font-medium text-gray-700">Contenido</label>
                            <textarea name="contenido" id="contenido" rows="4" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">{{ old('contenido') }}</textarea>
                            <p class="text-xs text-gray-500 mt-1">Prueba con: <code>&lt;script&gt;alert('XSS')&lt;/script&gt;</code></p>
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-yellow-500 focus:border-yellow-500">
                        </div>

                        <button type="submit" class="px-4 py-2 bg-yellow-600 text-white rounded hover:bg-yellow-700">
                            Enviar (demo sin protección)
                        </button>
                    </form>
                </div>
            </div>

            {{-- Comentarios existentes — mostrados con ambas modalidades --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="font-semibold text-lg">Comentarios existentes</h3>
                </div>
                <div class="p-6">
                    @forelse ($comentarios as $comentario)
                        <div class="mb-6 pb-6 border-b border-gray-200 last:border-b-0 last:mb-0 last:pb-0">
                            <h4 class="font-bold">{{ $comentario->titulo }}</h4>
                            <p class="text-sm text-gray-500 mb-2">{{ $comentario->email }} · {{ $comentario->created_at->format('d/m/Y H:i') }}</p>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-gray-400 mb-1">Seguro (escapado)</p>
                                    <div class="p-3 bg-green-50 border border-green-200 rounded">
                                        {{ $comentario->contenido }}
                                    </div>
                                </div>
                                <div>
                                    <p class="text-xs uppercase tracking-wider text-gray-400 mb-1">Peligroso (sin escapar)</p>
                                    <div class="p-3 bg-red-50 border border-red-200 rounded">
                                        {!! $comentario->contenido !!}
                                    </div>
                                </div>
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">No hay comentarios todavía. ¡Crea uno arriba!</p>
                    @endforelse
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
