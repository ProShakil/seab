<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Attributes\Hidden;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

#[Fillable([
    'name',
    'user_name',
    'email',
    'password',
    'membership_id',
    'user_fathers_name',
    'user_mothers_name',
    'dob',
    'id_type',
    'national_identity_number',
    'gender',
    'present_address_detail',
    'permanent_address_details',
    'contact_no',
    'tech_id',
    'occupation_id',
    'employer_name',
    'designation',
    'office_address_details',
    'latest_degree_name',
    'latest_institute_name',
    'membership_type_id',
    'emergency_contact_name',
    'relationship_id',
    'emergency_contact_no'
])]
#[Hidden(['password', 'remember_token'])]
class User extends Authenticatable
{
    /** @use HasFactory<UserFactory> */
    use HasFactory, Notifiable;

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
    public function relationship()
    {
        return $this->belongsTo(Relationship::class, 'relationship_id');
    }

    public function technology()
    {
        return $this->belongsTo(Technology::class, 'tech_id');
    }
    public function occupation()
    {
        return $this->belongsTo(Occupation::class, 'occupation_id');
    }
    
    public function committees()
    {
        return $this->hasMany(Committee::class);
    }
    public function membershipType()
    {
        return $this->belongsTo(MembershipType::class, 'membership_type_id');
    }

    public function blogPosts()
    {
        return $this->hasMany(BlogPost::class);
    }
}
