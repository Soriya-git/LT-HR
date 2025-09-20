<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateStaffRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->can('edit users') ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $userId = $this->route('id');
        return [
            'name'              => ['sometimes','string','max:255'],
            'email'             => ['sometimes','email','max:255', Rule::unique('users','email')->ignore($userId)],
            'password'          => ['sometimes','string','min:8'],

            'users_txid'        => ['sometimes','nullable','string','max:191'],
            'phone'             => ['sometimes','nullable','string','max:50'],
            'department'        => ['sometimes','nullable','string','max:100'],
            'position'          => ['sometimes','nullable','string','max:100'],
            'hire_date'         => ['sometimes','nullable','date'],
            'salary_monthly'    => ['sometimes','nullable','numeric','min:0'],
            'employment_status' => ['sometimes','in:active,probation,resigned,suspended'],
            'manager_id'        => ['sometimes','nullable','exists:users,id'],

            'role'              => ['sometimes','in:user,leader,manager,admin,super-admin'],
        ];
    }
}
