<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Team extends Model
{
    protected $fillable = [
        'name',
        'owner_id',
        'slug',
        'image',
        'disk',
        'upload_successful',
    ];

    protected static function boot()
    {
        parent::boot();

        // when team is created, add current user as 
        // team member
        //static::created(function ($team) {
        // auth()->user()->teams()->attach($team->id);
        //$team->members()->attach(auth()->id());
        //});

        static::deleting(function ($team) {
            $team->members()->sync([]);
        });
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members()
    {
        return $this->belongsToMany(User::class)
            ->withTimestamps();
    }

    public function designs()
    {
        return $this->hasMany(Design::class);
    }

    public function hasUser(User $user)
    {
        return $this->members()
            ->where('user_id', $user->id)
            ->first() ? true : false;
    }

    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }

    public function hasPendingInvite($email)
    {
        return (bool) $this->invitations()
            ->where('recipient_email', $email)
            ->count();
    }

    public function getImagesAttribute()
    {
        return [
            'thumbnail' => $this->getImagePath('thumbnail'),
            'large' => $this->getImagePath('large'),
            'original' => $this->getImagePath('original'),
        ];
    }

    protected function getImagePath($size)
    {
        return Storage::disk($this->disk)
            ->url("uploads/teams/{$size}/" . $this->image);
    }
}
