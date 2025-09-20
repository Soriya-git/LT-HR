<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl leading-tight">Edit Staff</h2>
    </x-slot>

    <div class="py-6">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow-sm rounded p-6">
                <form method="POST" action="{{ route('admin.staff.update', $user->id) }}">
                    @csrf
                    @method('PATCH')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <x-input-label for="name" value="Full Name" />
                            <x-text-input id="name" name="name" type="text" class="mt-1 block w-full" value="{{ old('name',$user->name) }}" />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="email" value="Email" />
                            <x-text-input id="email" name="email" type="email" class="mt-1 block w-full" value="{{ old('email',$user->email) }}" />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="password" value="Password (leave blank to keep)" />
                            <x-text-input id="password" name="password" type="password" class="mt-1 block w-full" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="users_txid" value="User TXID" />
                            <x-text-input id="users_txid" name="users_txid" type="text" class="mt-1 block w-full" value="{{ old('users_txid',$user->users_txid) }}" />
                            <x-input-error :messages="$errors->get('users_txid')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="department" value="Department" />
                            <x-text-input id="department" name="department" type="text" class="mt-1 block w-full" value="{{ old('department',$user->department) }}" />
                            <x-input-error :messages="$errors->get('department')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="position" value="Position" />
                            <x-text-input id="position" name="position" type="text" class="mt-1 block w-full" value="{{ old('position',$user->position) }}" />
                            <x-input-error :messages="$errors->get('position')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="hire_date" value="Hire Date" />
                            <x-text-input id="hire_date" name="hire_date" type="date" class="mt-1 block w-full" value="{{ old('hire_date', optional($user->hire_date)?->toDateString()) }}" />
                            <x-input-error :messages="$errors->get('hire_date')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="salary_monthly" value="Monthly Salary" />
                            <x-text-input id="salary_monthly" name="salary_monthly" type="number" step="0.01" class="mt-1 block w-full" value="{{ old('salary_monthly',$user->salary_monthly) }}" />
                            <x-input-error :messages="$errors->get('salary_monthly')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="employment_status" value="Status" />
                            <select id="employment_status" name="employment_status" class="mt-1 block w-full border rounded">
                                @foreach (['active','probation','resigned','suspended'] as $s)
                                    <option value="{{ $s }}" @selected(old('employment_status',$user->employment_status)===$s)>{{ $s }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('employment_status')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="manager_id" value="Manager" />
                            <select id="manager_id" name="manager_id" class="mt-1 block w-full border rounded">
                                <option value="">(none)</option>
                                @foreach($managers as $m)
                                    <option value="{{ $m->id }}" @selected(old('manager_id',$user->manager_id)===$m->id)>{{ $m->name }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('manager_id')" class="mt-2" />
                        </div>

                        <div>
                            <x-input-label for="role" value="Role" />
                            <select id="role" name="role" class="mt-1 block w-full border rounded">
                                @foreach($roles as $r)
                                    <option value="{{ $r }}" @selected($user->getRoleNames()->first()===$r)>{{ $r }}</option>
                                @endforeach
                            </select>
                            <x-input-error :messages="$errors->get('role')" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-6 flex items-center gap-3">
                        <x-primary-button>Save</x-primary-button>
                        <a href="{{ route('admin.staff.index') }}" class="text-gray-600 hover:underline">Back</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
