<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

/** Formats staff (users) for API consumers. */
class StaffResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id'                => $this->id,
            'users_txid'        => $this->users_txid,
            'name'              => $this->name,
            'email'             => $this->email,
            'phone'             => $this->phone,
            'department'        => $this->department,
            'position'          => $this->position,
            'hire_date'         => optional($this->hire_date)?->toDateString(),
            'salary_monthly'    => $this->salary_monthly? (string)$this->salary_monthly : null,
            'employment_status' => $this->employment_status,
            'manager'           => $this->whenLoaded('manager', fn() => [
                'id'   => $this->manager?->id,
                'name' => $this->manager?->name,
            ]),
            'roles'             => $this->getRoleNames(),
        ];
    }
}