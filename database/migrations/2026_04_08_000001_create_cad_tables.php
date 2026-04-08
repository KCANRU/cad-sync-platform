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
        // 1. stage_configs table
        Schema::create('stage_configs', function (Blueprint $table) {
            $table->id();
            $table->string('stage_code', 30)->unique();
            $table->string('stage_name', 100);
            $table->string('target_view', 50);
            $table->json('validation_rules')->nullable();
            $table->json('allowed_tags')->nullable();
            $table->boolean('inherit_from_parent')->default(true);
            $table->boolean('can_edit_geometry')->default(false);
            $table->boolean('active')->default(true);
            $table->timestamps();
        });

        // 2. drawings table
        Schema::create('drawings', function (Blueprint $table) {
            $table->id();
            $table->string('project_id', 100)->nullable()->index();
            $table->string('line_no', 50)->index();
            $table->integer('rev_no')->default(0);
            $table->foreignId('stage_id')->constrained('stage_configs');
            $table->foreignId('parent_drawing_id')->nullable()->constrained('drawings')->nullOnDelete();
            $table->string('dxf_path', 500)->nullable();
            $table->string('pdf_path', 500)->nullable();
            $table->enum('status', ['draft', 'in_review', 'approved', 'archived'])->default('draft');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        // 3. drawing_attributes table
        Schema::create('drawing_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('drawing_id')->constrained('drawings')->cascadeOnDelete();
            $table->string('tag_name', 50);
            $table->text('value')->nullable();
            $table->enum('source', ['db', 'user', 'sync', 'template', 'inherited'])->default('db');
            $table->timestamp('synced_at')->nullable();
            $table->unique(['drawing_id', 'tag_name']);
        });

        // 4. file_uploads table
        Schema::create('file_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('original_name', 255);
            $table->string('safe_name', 255)->unique();
            $table->string('mime_type', 50);
            $table->unsignedBigInteger('size_bytes');
            $table->string('storage_path', 500);
            $table->enum('file_type', ['dwg', 'dxf', 'pdf', 'other']);
            $table->enum('status', ['uploaded', 'converting', 'ready', 'failed'])->default('uploaded');
            $table->string('converted_dxf_path', 500)->nullable();
            $table->text('error_log')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });

        // 5. pdf_annotations table
        Schema::create('pdf_annotations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('drawing_id')->constrained('drawings')->cascadeOnDelete();
            $table->string('annotation_type', 50);
            $table->json('data');
            $table->integer('page_number')->default(1);
            $table->string('color', 20)->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        // 6. audit_logs table
        Schema::create('audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('drawing_id')->constrained('drawings')->cascadeOnDelete();
            $table->string('action', 50);
            $table->string('entity_type', 50);
            $table->string('entity_ref', 100);
            $table->text('old_value')->nullable();
            $table->text('new_value')->nullable();
            $table->string('batch_id', 36)->nullable()->index();
            $table->string('stage_code', 30)->nullable();
            $table->string('target_view', 50)->nullable();
            $table->json('metadata')->nullable();
            $table->string('ip_address', 45)->nullable();
            $table->timestamps();

            // Composite indexes
            $table->index(['drawing_id', 'created_at']);
            $table->index(['user_id', 'action']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('audit_logs');
        Schema::dropIfExists('pdf_annotations');
        Schema::dropIfExists('file_uploads');
        Schema::dropIfExists('drawing_attributes');
        Schema::dropIfExists('drawings');
        Schema::dropIfExists('stage_configs');
    }
};
