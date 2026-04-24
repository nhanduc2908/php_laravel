<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable implements JWTSubject
{
    use SoftDeletes;

    protected $table = 'users';
    protected $primaryKey = 'id';
    public $timestamps = true;

    protected $fillable = [
        'name', 'email', 'password', 'role_id', 'avatar', 
        'is_active', 'two_factor_secret', 'two_factor_enabled',
        'last_login_at', 'last_login_ip'
    ];

    protected $hidden = ['password', 'two_factor_secret', 'remember_token'];
    protected $casts = [
        'is_active' => 'boolean',
        'two_factor_enabled' => 'boolean',
        'last_login_at' => 'datetime'
    ];

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [
            'user_id' => $this->id,
            'email' => $this->email,
            'role' => $this->role->slug ?? null
        ];
    }

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function servers()
    {
        return $this->belongsToMany(Server::class, 'user_server');
    }

    public function assessments()
    {
        return $this->hasMany(AssessmentReport::class, 'created_by');
    }

    public function files()
    {
        return $this->hasMany(AssessmentFile::class, 'created_by');
    }

    public function auditLogs()
    {
        return $this->hasMany(AuditLog::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function hasPermission($permission)
    {
        return $this->role->permissions->contains('slug', $permission);
    }

    public function hasRole($role)
    {
        return $this->role->slug === $role;
    }

    public function isAdmin()
    {
        return in_array($this->role->slug, ['super_admin', 'admin']);
    }
}