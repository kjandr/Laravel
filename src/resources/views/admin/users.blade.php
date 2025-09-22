<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Benutzerübersicht
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">

                    @if(session('success'))
                        <div class="mb-4 text-green-600">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="mb-4 text-red-600">
                            {{ session('error') }}
                        </div>
                    @endif

                    <table class="table-auto w-full border">
                        <thead>
                            <tr class="bg-gray-100">
                                <th class="px-4 py-2">ID</th>
                                <th class="px-4 py-2">Name</th>
                                <th class="px-4 py-2">Email</th>
                                <th class="px-4 py-2">Rolle</th>
                                <th class="px-4 py-2">Registriert</th>
                                <th class="px-4 py-2">Aktionen</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr>
                                    <td class="border px-4 py-2">{{ $user->id }}</td>
                                    <td class="border px-4 py-2">{{ $user->name }}</td>
                                    <td class="border px-4 py-2">{{ $user->email }}</td>
                                    <td class="border px-4 py-2">
                                        @if(is_null($user->role))
                                            ❌ deaktiviert
                                        @elseif($user->role === 'anwender')
                                            👤 Anwender
                                        @elseif($user->role === 'haendler')
                                            🛒 Händler
                                        @elseif($user->role === 'admin')
                                            👑 Admin
                                        @endif
                                    </td>
                                    <td class="border px-4 py-2">{{ $user->created_at }}</td>

                                    {{-- 👉 Flex direkt auf die Tabellenzelle --}}
                                    <td class="border px-4 py-2 flex items-center gap-3">

                                        {{-- Rolle ändern --}}
                                        <form method="POST" 
                                              action="{{ url('/admin/users/'.$user->id.'/role') }}" 
                                              class="flex items-center gap-2">
                                            @csrf
                                            @method('PUT')

                                            <select name="role"
                                                    class="border px-2 py-1 rounded"
                                                    style="width: auto; min-width: fit-content; padding-right: 2.0rem;">
                                                <option value=""          {{ is_null($user->role) ? 'selected' : '' }}>deaktiviert</option>
                                                <option value="anwender"  {{ $user->role === 'anwender' ? 'selected' : '' }}>Anwender</option>
                                                <option value="haendler"  {{ $user->role === 'haendler' ? 'selected' : '' }}>Händler</option>
                                                <option value="admin"     {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                                            </select>

                                            {{-- Speichern --}}
                                            <button type="submit"
                                                    class="inline-flex items-center justify-center text-gray-700 hover:text-black px-2">
                                                💾
                                            </button>
                                            </form>   {{-- ← hier das erste Form schließen --}}

                                            {{-- Löschen --}}
                                            <form method="POST"
                                                action="{{ url('/admin/users/'.$user->id) }}"
                                                onsubmit="return confirm('Willst du diesen User wirklich löschen?');"
                                                class="inline ml-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="inline-flex items-center justify-center text-gray-700 hover:text-black px-2">
                                                    🗑️
                                                </button>
                                            </form>

                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>
