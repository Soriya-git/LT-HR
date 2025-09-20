<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('create users') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name'              => ['required','string','max:255'],
            'email'             => ['required','email','max:255','unique:users,email'],
            'password'          => ['required','string','min:8'],

            // optional profile
            'users_txid'        => ['nullable','string','max:191'],
            'phone'             => ['nullable','string','max:50'],
            'department'        => ['nullable','string','max:100'],
            'position'          => ['nullable','string','max:100'],
            'hire_date'         => ['nullable','date'],
            'salary_monthly'    => ['nullable','numeric','min:0'],
            'employment_status' => ['nullable','in:active,probation,resigned,suspended'],
            'manager_id'        => ['nullable','exists:users,id'],

            // role mapping
            'role'              => ['required','in:user,leader,manager,admin,super-admin'],
        ];
    }
}