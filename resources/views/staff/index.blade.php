<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">
            Staff Management
        </h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            @if (session('status'))
                <div class="mb-4 text-green-700 bg-green-100 border border-green-200 rounded p-3">
                    {{ session('status') }}
                </div>
            @endif

            @if (session('reset_password_value'))
                <div class="mb-4 text-amber-800 bg-amber-100 border border-amber-200 rounded p-3">
                    <div class="font-semibold">New password (copy now – shown only once):</div>
                    <code class="text-sm">{{ session('reset_password_value') }}</code>
                    <div class="text-xs text-gray-600 mt-1">For user: {{ session('reset_password_for') }}</div>
                </div>
            @endif

            <div class="mb-4 flex justify-between">
                <form method="GET" class="flex gap-2">
                    <input type="text" name="department" value="{{ request('department') }}"
                        class="border rounded px-3 py-2" placeholder="Filter by department">
                    <x-primary-button>Filter</x-primary-button>
                </form>

                @can('create users')
                    <a href="{{ route('admin.staff.create') }}" class="inline-flex items-center px-4 py-2 bg-indigo-600 text-white rounded">
                        + New Staff
                    </a>
                @endcan
            </div>

            <div class="bg-white overflow-hidden shadow-sm rounded">
                <table class="min-w-full text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-4 py-3 text-left">Name</th>
                            <th class="px-4 py-3 text-left">Email</th>
                            <th class="px-4 py-3 text-left">Department</th>
                            <th class="px-4 py-3 text-left">Position</th>
                            <th class="px-4 py-3 text-left">Manager</th>
                            <th style="width: 30px;" class="px-4 py-3 text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($staff as $u)
                            <tr class="border-t">
                                <td class="px-4 py-2">{{ $u->name }}</td>
                                <td class="px-4 py-2">{{ $u->email }}</td>
                                <td class="px-4 py-2">{{ $u->department }}</td>
                                <td class="px-4 py-2">{{ $u->position }}</td>
                                <td class="px-4 py-2">{{ $u->manager?->name ?? '-' }}</td>
                                {{--action button value --}}
                                <td style="width: 30px;" class="px-4 py-2 text-right space-x-3">

                                    {{-- Edit --}}
                                    @can('edit users')
                                        <a href="{{ route('admin.staff.edit', $u->id) }}"
                                        title="Edit"
                                        class="inline-block text-indigo-600 hover:text-indigo-800">
                                            {{-- Heroicon: Pencil --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M16.862 3.487a2.25 2.25 0 013.182 3.182L7.5 19.313 3 21l1.687-4.5L16.862 3.487z"/>
                                            </svg>
                                        </a>
                                    @endcan

                                    {{-- Assign Manager --}}
                                    @can('manage reporting lines')
                                        <a href="{{ route('admin.staff.manager.form', $u->id) }}"
                                        title="Assign Manager"
                                        class="inline-block text-blue-600 hover:text-blue-800">
                                            {{-- Heroicon: User Plus --}}
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M15 19.128a9 9 0 10-6 0M12 4.5v15m0-15C7.305 7.354 4.5 11.97 4.5 16.5h15c0-4.53-2.805-9.146-7.5-12z"/>
                                            </svg>
                                        </a>
                                    @endcan

                                    {{-- Reset Password --}}
                                    @can('reset passwords')
                                        @if (! $u->hasRole('super-admin'))
                                            <form action="{{ route('admin.staff.reset.password', $u->id) }}"
                                                method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Reset password for {{ $u->email }}?');">
                                                @csrf
                                                <button type="submit" title="Reset Password"
                                                        class="text-red-600 hover:text-red-800">
                                                    {{-- Heroicon: Key --}}
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                                        viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M15.75 5.25a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM19.5 21h-3v-2.25a3 3 0 00-6 0V21h-3"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        @endif
                                    @endcan

                                </td>

                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $staff->withQueryString()->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
