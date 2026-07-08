<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Nuevo Comentario — Demostración CSRF
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            {{-- Formulario 1: CON @csrf (protegido) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-8">
                <div class="p-6 border-b border-gray-200 bg-green-50">
                    <h3 class="font-semibold text-lg text-green-800">Formulario PROTEGIDO (con @csrf)</h3>
                    <p class="text-sm text-green-700">Este formulario incluye el token CSRF. Al enviarlo, la petición será aceptada por el servidor.</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('comentarios.store') }}">
                        @csrf

                        <div class="mb-4">
                            <label for="titulo" class="block text-sm font-medium text-gray-700">Título</label>
                            <input type="text" name="titulo" id="titulo" value="{{ old('titulo') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            @error('titulo') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="contenido" class="block text-sm font-medium text-gray-700">Contenido</label>
                            <textarea name="contenido" id="contenido" rows="4" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">{{ old('contenido') }}</textarea>
                            @error('contenido') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-green-500 focus:border-green-500">
                            @error('email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded hover:bg-green-700">
                            Enviar (protegido)
                        </button>
                    </form>
                </div>
            </div>

            {{-- Formulario 2: SIN @csrf (intencionalmente vulnerable) --}}
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200 bg-red-50">
                    <h3 class="font-semibold text-lg text-red-800">Formulario VULNERABLE (sin @csrf)</h3>
                    <p class="text-sm text-red-700">Este formulario NO incluye token CSRF. Al enviarlo, Laravel devolverá error 419 (expired/page expired).</p>
                </div>
                <div class="p-6">
                    <form method="POST" action="{{ route('comentarios.store.sinCsrf') }}">
                        {{-- Intencionadamente OMITIMOS @csrf --}}

                        <div class="mb-4">
                            <label for="titulo_sin" class="block text-sm font-medium text-gray-700">Título</label>
                            <input type="text" name="titulo" id="titulo_sin" value="{{ old('titulo') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>

                        <div class="mb-4">
                            <label for="contenido_sin" class="block text-sm font-medium text-gray-700">Contenido</label>
                            <textarea name="contenido" id="contenido_sin" rows="4" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">{{ old('contenido') }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="email_sin" class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" name="email" id="email_sin" value="{{ old('email') }}" required
                                class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-red-500 focus:border-red-500">
                        </div>

                        <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                            Enviar SIN protección (demo)
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
