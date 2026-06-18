<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlogPost extends Model
{
    protected $fillable = [
        'title', 'slug', 'excerpt', 'content', 'featured_image', 'is_published', 'published_at'
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    public function authors()
    {
        return $this->belongsToMany(User::class, 'blog_post_author');
    }

    public function categories()
    {
        return $this->belongsToMany(BlogCategory::class, 'blog_post_blog_category');
    }

    public function tags()
    {
        return $this->belongsToMany(BlogTag::class, 'blog_post_blog_tag');
    }
}
