<x-app-layout>
    <x-slot:header>
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Perfil') }}
        </h2>
    </x-slot:header>

    <div class="py-12">
        <div class="max-w-2xl mx-auto space-y-6">
            @if (session('status'))
                <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-md text-sm">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-md text-sm">
                    {{ session('error') }}
                </div>
            @endif

            {{-- Información del perfil --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Información del perfil') }}</h3>
                <dl class="space-y-3">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">{{ __('Nombre') }}</dt>
                        <dd class="text-sm text-gray-900">{{ auth()->user()->name }}</dd>
                    </div>
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Email</dt>
                        <dd class="text-sm text-gray-900">{{ auth()->user()->email }}</dd>
                    </div>
                </dl>
            </div>

            {{-- Autenticación de dos factores --}}
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-900 mb-4">{{ __('Autenticación de dos factores (2FA)') }}</h3>

                @php
                    $user = auth()->user();
                    $twoFactorEnabled = !is_null($user->two_factor_confirmed_at);
                @endphp

                @if ($twoFactorEnabled)
                    <div class="flex items-center mb-4">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            {{ __('Activado') }}
                        </span>
                        <span class="ml-2 text-sm text-gray-600">
                            {{ __('Confirmado el') }} {{ $user->two_factor_confirmed_at->format('d/m/Y H:i') }}
                        </span>
                    </div>

                    <div class="space-y-2">
                        <a href="{{ route('perfil.2fa.qr') }}"
                            class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                            {{ __('Ver código QR') }}
                        </a>

                        <form method="POST" action="{{ route('perfil.2fa.disable') }}" class="inline" onsubmit="return confirm('{{ __('¿Estás seguro? Si no tienes acceso a tu aplicación autenticadora, necesitarás un código de recuperación para volver a iniciar sesión.') }}')">
                            @csrf
                            <button type="submit"
                                class="inline-flex items-center px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition">
                                {{ __('Deshabilitar 2FA') }}
                            </button>
                        </form>
                    </div>
                @else
                    <p class="text-sm text-gray-600 mb-4">
                        {{ __('Agrega una capa adicional de seguridad a tu cuenta usando una aplicación autenticadora como Google Authenticator o Authy.') }}
                    </p>

                    <a href="{{ route('perfil.2fa.habilitar') }}"
                        class="inline-flex items-center px-4 py-2 bg-indigo-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition">
                        {{ __('Habilitar 2FA') }}
                    </a>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
