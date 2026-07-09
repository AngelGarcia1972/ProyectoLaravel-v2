<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Recuperar contraseña') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-md mx-auto bg-white p-6 rounded-lg shadow">
            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <p class="text-sm text-gray-600 mb-4">
                {{ __('Ingresa tu email y te enviaremos un enlace para restablecer tu contraseña.') }}
            </p>

            <form method="POST" action="{{ route('password.email') }}">
                @csrf

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    @error('email')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        {{ __('Enviar enlace') }}
                    </button>
                    <a href="{{ route('login') }}" class="text-sm text-indigo-600 hover:text-indigo-800">
                        {{ __('Volver a inicio de sesión') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
