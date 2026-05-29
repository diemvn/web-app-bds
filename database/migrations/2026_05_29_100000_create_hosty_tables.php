<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->json('value')->nullable();
            $table->timestamps();
        });

        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('address');
            $table->string('district')->nullable();
            $table->string('city')->default('TP.HCM');
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->json('amenities')->nullable();
            $table->json('images')->nullable();
            $table->text('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->index(['lat', 'lng']);
        });

        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('floor')->default(1);
            $table->string('room_number');
            $table->decimal('area_m2', 8, 2)->nullable();
            $table->unsignedBigInteger('base_price');
            $table->string('status')->default('available');
            $table->json('utilities_config')->nullable();
            $table->json('amenities')->nullable();
            $table->json('images')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
            $table->unique(['building_id', 'room_number']);
        });

        Schema::create('tenants', function (Blueprint $table) {
            $table->id();
            $table->string('full_name');
            $table->string('phone')->unique();
            $table->string('email')->nullable();
            $table->string('cccd', 20)->nullable();
            $table->string('zalo_user_id')->nullable();
            $table->json('documents')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->unique()->after('email');
            $table->foreignId('tenant_id')->nullable()->after('phone')->constrained('tenants')->nullOnDelete();
        });

        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tenant_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedTinyInteger('payment_due_day')->default(5);
            $table->unsignedBigInteger('deposit_amount')->default(0);
            $table->unsignedBigInteger('monthly_rent');
            $table->string('status')->default('draft');
            $table->string('contract_pdf_path')->nullable();
            $table->text('terms')->nullable();
            $table->timestamps();
        });

        Schema::create('utility_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->string('reading_month', 7);
            $table->unsignedInteger('electric_start')->default(0);
            $table->unsignedInteger('electric_end')->default(0);
            $table->unsignedInteger('water_start')->default(0);
            $table->unsignedInteger('water_end')->default(0);
            $table->unsignedInteger('electric_rate')->default(3500);
            $table->unsignedInteger('water_rate')->default(20000);
            $table->timestamps();
            $table->unique(['room_id', 'reading_month']);
        });

        Schema::create('invoices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('contract_id')->constrained()->cascadeOnDelete();
            $table->string('billing_month', 7);
            $table->unsignedBigInteger('room_amount')->default(0);
            $table->unsignedBigInteger('electric_amount')->default(0);
            $table->unsignedBigInteger('water_amount')->default(0);
            $table->unsignedBigInteger('service_amount')->default(0);
            $table->unsignedBigInteger('other_amount')->default(0);
            $table->unsignedBigInteger('total_amount')->default(0);
            $table->date('due_date');
            $table->string('status')->default('draft');
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();
            $table->unique(['contract_id', 'billing_month']);
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('invoice_id')->constrained()->cascadeOnDelete();
            $table->unsignedBigInteger('amount');
            $table->string('method')->default('cash');
            $table->string('transaction_ref')->nullable();
            $table->timestamp('paid_at');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('listings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('room_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->unsignedBigInteger('price');
            $table->decimal('area_m2', 8, 2)->nullable();
            $table->decimal('lat', 10, 7)->nullable();
            $table->decimal('lng', 10, 7)->nullable();
            $table->json('images')->nullable();
            $table->json('amenities')->nullable();
            $table->string('status')->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->index(['lat', 'lng', 'status']);
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('building_id')->nullable()->constrained()->nullOnDelete();
            $table->string('category');
            $table->string('title');
            $table->unsignedBigInteger('amount');
            $table->date('expense_date');
            $table->string('receipt_image')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->string('type');
            $table->string('title');
            $table->text('description')->nullable();
            $table->nullableMorphs('subject');
            $table->json('meta')->nullable();
            $table->timestamps();
        });

        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tenant_id')->nullable()->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->cascadeOnDelete();
            $table->string('type');
            $table->string('title');
            $table->text('body')->nullable();
            $table->string('link')->nullable();
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });

        Schema::create('zns_logs', function (Blueprint $table) {
            $table->id();
            $table->string('template');
            $table->string('phone');
            $table->json('payload')->nullable();
            $table->string('status')->default('queued');
            $table->text('response')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('zns_logs');
        Schema::dropIfExists('notifications');
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('listings');
        Schema::dropIfExists('payments');
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('utility_readings');
        Schema::dropIfExists('contracts');
        Schema::table('users', function (Blueprint $table) {
            $table->dropConstrainedForeignId('tenant_id');
            $table->dropColumn('phone');
        });
        Schema::dropIfExists('tenants');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('buildings');
        Schema::dropIfExists('system_settings');
    }
};
