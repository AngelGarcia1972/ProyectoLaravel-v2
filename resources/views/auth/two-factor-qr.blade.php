<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Configurar autenticación de dos factores') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-lg mx-auto bg-white p-6 rounded-lg shadow">
            @if (session('status'))
                <div class="mb-4 text-sm text-green-600">
                    {{ session('status') }}
                </div>
            @endif

            <p class="text-sm text-gray-600 mb-4">
                {{ __('Escanea este código con Google Authenticator, Authy, o Microsoft Authenticator.') }}
            </p>

            <div class="flex justify-center mb-4">
                {!! $qrCode !!}
            </div>

            <div class="mb-4 p-4 bg-gray-50 rounded-md">
                <p class="text-xs text-gray-500 mb-1">{{ __('Si no puedes escanear el código, ingresa esta clave manualmente:') }}</p>
                <code class="text-sm font-mono bg-gray-200 px-2 py-1 rounded block text-center">{{ $secretKey }}</code>
            </div>

            <p class="text-sm text-gray-600 mb-4">
                {{ __('Después de escanear el código, ingresa el código de 6 dígitos generado por la aplicación para confirmar la configuración.') }}
            </p>

            <form method="POST" action="/user/confirmed-two-factor-authentication">
                @csrf

                <div class="mb-4">
                    <label for="code" class="block text-sm font-medium text-gray-700">{{ __('Código de verificación') }}</label>
                    <input id="code" type="text" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="off"
                        class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center text-2xl tracking-widest"
                        placeholder="000000" required>
                    @error('code')
                        <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        {{ __('Confirmar') }}
                    </button>
                    <a href="{{ url('/perfil') }}" class="text-sm text-gray-600 hover:text-gray-800">
                        {{ __('Cancelar') }}
                    </a>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
