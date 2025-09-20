<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">Assign Manager — {{ $user->name }}</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded p-6">
                <form method="POST" action="{{ route('admin.staff.manager.assign', $user->id) }}">
                    @csrf
                    @method('PATCH')

                    <div>
                        <x-input-label for="manager_id" value="Select Manager" />
                        <select id="manager_id" name="manager_id" class="mt-1 block w-full border rounded">
                            @foreach($managers as $m)
                                <option value="{{ $m->id }}" @selected($user->manager_id===$m->id)>{{ $m->name }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('manager_id')" class="mt-2" />
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <x-primary-button>Assign</x-primary-button>
                        <a href="{{ route('admin.staff.index') }}" class="text-gray-600 hover:underline">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
