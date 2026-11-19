<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->ulid('uid')->unique()->index();
            $table->string('name');
            $table->string('username')->unique();
            $table->string('password');
            $table->timestamps();
        });

        Schema::create('access_tokens', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->index()->constrained('users')->onDelete('cascade');
            $table->string('token')->unique();
            $table->dateTime('expires_at')->nullable();
            $table->timestamps();
        });


        Schema::create('subscription_plans', function (Blueprint $table) {
            $table->id();
            $table->ulid('subscription_plan_uid')->unique()->index();
            $table->string('subscription_name');
            $table->decimal('subscription_price', 15);
            $table->text('subscription_description')->nullable();
            $table->json('subscription_benefits')->nullable();
            $table->integer('subscription_daily_quota')->default(10);
            $table->integer('subscription_ratelimit_per_minute')->default(5);
            $table->timestamps();
        });

        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id();
            $table->ulid('subscriptions_uid')->unique()->index();
            $table->foreignId('user_id')->index()->constrained('users')->onDelete('cascade');
            $table->foreignId('subscription_id')->index()->constrained('subscription_plans')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('content_providers', function (Blueprint $table) {
            $table->id();
            $table->ulid('provider_uid')->unique()->index();
            $table->string('provider_name');
            $table->string('provider_slug');
            $table->timestamps();
        });

        Schema::create('contents', function (Blueprint $table) {
            $table->id();
            $table->ulid('content_uid')->unique()->index();
            $table->string('title');
            $table->string('slug');
            $table->longText('description')->nullable();
            $table->string('author')->nullable();
            $table->string('play_count')->nullable();
            $table->string('duration')->nullable();
            $table->text('thumb_url')->nullable();
            $table->text('vid_url')->nullable();
            $table->timestamps();
        });

        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
        Schema::dropIfExists('access_token');
        Schema::dropIfExists('sessions');
    }
};
