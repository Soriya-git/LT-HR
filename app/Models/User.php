<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    // old => use HasFactory, Notifiable;
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name','email','password',
        'users_txid',
        'phone','department','position','hire_date',
        'salary_monthly','employment_status','manager_id',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'hire_date'         => 'date',
        'salary_monthly'    => 'decimal:2',
        'password' => 'hashed',

    ];

    /** Who is my manager? */
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    /** Who reports to me? */
    public function subordinates()
    {
        return $this->hasMany(User::class, 'manager_id');
    }

    /**
     * Visibility scope:
     * - super-admin/admin: all
     * - manager/leader: self + direct reports
     * - user: self only
     */
    public function scopeVisibleTo($query, User $viewer)
    {
        if ($viewer->hasAnyRole(['super-admin','admin'])) {
            return $query;
        }
        if ($viewer->hasAnyRole(['manager','leader'])) {
            return $query->where(function ($q) use ($viewer) {
                $q->where('id', $viewer->id)
                  ->orWhere('manager_id', $viewer->id);
            });
        }
        return $query->where('id', $viewer->id);
    }

    /* old=> protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }*/
}
