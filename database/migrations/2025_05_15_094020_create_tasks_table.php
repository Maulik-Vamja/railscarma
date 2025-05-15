<?php

use App\Enums\{PriorityEnum, TaskStatusEnum};
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
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->string('name')->comment('Name of the task');
            $table->text('description')->nullable()->comment('Description of the task');
            $table->date('due_date')->nullable()->comment('Due date for the task');
            $table->string('priority')->default(PriorityEnum::NORMAL)->comment('Priority of the task');
            $table->string('status')->default(TaskStatusEnum::PENDING)->comment('Status of the task');
            $table->string('url')->nullable()->comment('URL to the task or related resource');
            $table->string('image')->nullable()->comment('URL or path to the task image');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tasks');
    }
};
