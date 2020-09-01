<?php

namespace App\Entities;

use Elasticquent\ElasticquentTrait;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Mongodb\Eloquent\HybridRelations;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\CustomElasticsearchTrait;

class Translation extends Model
{
    use SoftDeletes, ElasticquentTrait, HybridRelations, CustomElasticsearchTrait;

    protected $connection = 'mysql';

    protected $hidden = ['deleted_at'];

    protected $fillable = ['admin_id', 'project_id', 'phrase', 'meaning', 'description', 'total_like', 'total_comment'];

    protected $mappingProperties = [
        'phrase' => [
            'type' => 'text',
            'analyzer' => 'my_kuromoji_analyzer'
        ],
        'meaning' => [
            'type' => 'text',
            'analyzer' => 'standard'
        ],
        'project_id' => [
            'type' => 'integer',
        ]
    ];

    public function admin()
    {
        return $this->belongsTo(Admin::class);
    }

    public function archives()
    {
        return $this->belongsToMany(User::class, 'archives', 'translation_id', 'user_id');
    }

    public function likes()
    {
        return $this->belongsToMany(User::class, 'likes', 'translation_id', 'user_id');
    }

    public function deviceArchives()
    {
        return $this->belongsToMany(Device::class, 'archives', 'translation_id', 'device_id');
    }

    public function deviceLikes()
    {
        return $this->belongsToMany(Device::class, 'likes', 'translation_id', 'device_id');
    }

    public function histories()
    {
        return $this->belongsToMany(User::class, 'search_histories', 'translation_id', 'user_id');
    }

    public function translationHistories()
    {
        return $this->hasMany(TranslationHistory::class);
    }

    public function project()
    {
        return $this->belongsTo(Project::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function devices()
    {
        return $this->belongsToMany(Device::class, 'archives', 'translation_id', 'device_id');
    }
}
