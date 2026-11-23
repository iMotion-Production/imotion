<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * A one-time set-password invite for one mailbox. The token itself is never stored:
 * the row keeps hash('sha256', $token) and the plain link lives only in the admin's flash.
 */
class MailboxInvite extends Model
{
    /** The invite link lifetime (hours). */
    public const TTL_HOURS = 72;

    protected $fillable = [
        'user_id',
        'mail_domain',
        'mailbox_user',
        'token_hash',
        'expires_at',
        'used_at',
    ];

    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
            'used_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /** The invite a plain token stands for: unused and not yet expired. Null otherwise. */
    public static function findUsable(string $token): ?self
    {
        if ($token === '') {
            return null;
        }

        return static::query()
            ->where('token_hash', hash('sha256', $token))
            ->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->first();
    }
}