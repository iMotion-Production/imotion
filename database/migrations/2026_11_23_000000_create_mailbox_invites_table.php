<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

/**
 * One-time "set your password" links an administrator sends to a mailbox user.
 * Only the SHA-256 of the token is stored; the plain token exists just in the one admin flash.
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::create('mailbox_invites', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('mail_domain');
            $table->string('mailbox_user', 64);
            $table->char('token_hash', 64)->unique();
            $table->timestamp('expires_at');
            $table->timestamp('used_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('mailbox_invites');
    }
};