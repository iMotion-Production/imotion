<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Client's own portal hostname, e.g. mailbox.fokusteguhdinamik.com.my.
            $table->string('portal_host')->nullable()->index()->after('mail_domain');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['portal_host']);
            $table->dropColumn('portal_host');
        });
    }
};
