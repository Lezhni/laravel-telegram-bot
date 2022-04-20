<?php

namespace App\Models\Telegram;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * Class Node
 * @package App\Models\Telegram
 */
class Node extends Model
{
    /**
     * @var string
     */
    protected $table = 'telegram_nodes';

    /**
     * @var string[]
     */
    protected $fillable = [
        'title',
        'text',
        'links',
        'parent_id',
    ];

    /**
     * @var string[]
     */
    protected $casts = [
        'links' => 'array',
        'parent_id' => 'integer',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parentNode(): BelongsTo
    {
        return $this->belongsTo(Node::class, 'parent_id');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function childrenNodes(): HasMany
    {
        return $this->hasMany(Node::class, 'parent_id');
    }
}
