<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('support_tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket_number')->unique();

            $table->string('customer_name');
            $table->string('customer_email');
            $table->string('customer_phone')->nullable();

            $table->enum('category', ['delivery', 'payment', 'product', 'print_quality', 'order_issue', 'account', 'other'])->default('other');
            $table->enum('priority', ['low', 'medium', 'high', 'urgent'])->default('medium');

            $table->string('subject');
            $table->text('description');

            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('order_number')->nullable();
            $table->foreignId('product_id')->nullable()->constrained()->nullOnDelete();

            $table->enum('status', ['open', 'in_progress', 'waiting_customer', 'resolved', 'closed'])->default('open');

            $table->foreignId('assigned_to')->nullable()->constrained('users')->nullOnDelete();
            $table->text('admin_note')->nullable();
            $table->text('resolution_note')->nullable();

            $table->string('source', 20)->default('app');
            $table->string('api_token')->nullable();

            $table->timestamp('resolved_at')->nullable();
            $table->timestamp('closed_at')->nullable();

            $table->timestamps();

            $table->index('status');
            $table->index('category');
            $table->index('priority');
            $table->index('customer_email');
            $table->index('created_at');
        });

        Schema::create('support_ticket_attachments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->string('file_path');
            $table->string('original_name')->nullable();
            $table->string('mime_type')->nullable();
            $table->integer('file_size')->nullable();
            $table->boolean('is_screenshot')->default(0);
            $table->timestamps();
        });

        Schema::create('support_ticket_messages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_id')->constrained('support_tickets')->cascadeOnDelete();
            $table->enum('sender_type', ['customer', 'admin'])->default('customer');
            $table->string('sender_name');
            $table->text('message');
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();

            $table->index(['ticket_id', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('support_ticket_messages');
        Schema::dropIfExists('support_ticket_attachments');
        Schema::dropIfExists('support_tickets');
    }
};
