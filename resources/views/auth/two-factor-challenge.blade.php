<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Autenticación de dos factores') }}
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
                {{ __('Ingresa el código de 6 dígitos generado por tu aplicación autenticadora.') }}
            </p>

            <form method="POST" action="/two-factor-challenge">
                @csrf

                <div id="code-form">
                    <div class="mb-4">
                        <label for="code" class="block text-sm font-medium text-gray-700">{{ __('Código TOTP') }}</label>
                        <input id="code" type="text" name="code" inputmode="numeric" pattern="[0-9]{6}" maxlength="6" autocomplete="one-time-code"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-center text-2xl tracking-widest"
                            placeholder="000000">
                        @error('code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div id="recovery-form" style="display: none;">
                    <div class="mb-4">
                        <label for="recovery_code" class="block text-sm font-medium text-gray-700">{{ __('Código de recuperación') }}</label>
                        <input id="recovery_code" type="text" name="recovery_code"
                            class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="XXXXX-XXXXX">
                        @error('recovery_code')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        {{ __('Verificar') }}
                    </button>
                    <button type="button" id="toggle-recovery" class="text-sm text-indigo-600 hover:text-indigo-800">
                        {{ __('Usar código de recuperación') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.getElementById('toggle-recovery').addEventListener('click', function() {
            var codeForm = document.getElementById('code-form');
            var recoveryForm = document.getElementById('recovery-form');
            var toggleBtn = document.getElementById('toggle-recovery');

            if (recoveryForm.style.display === 'none') {
                codeForm.style.display = 'none';
                recoveryForm.style.display = 'block';
                toggleBtn.textContent = '{{ __('Usar código TOTP') }}';
            } else {
                codeForm.style.display = 'block';
                recoveryForm.style.display = 'none';
                toggleBtn.textContent = '{{ __('Usar código de recuperación') }}';
            }
        });
    </script>
</x-app-layout>
