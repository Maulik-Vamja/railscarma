<?php

use App\Enums\PriorityEnum;
use App\Enums\ProjectStatusEnum;
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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name')->nullable()->comment('Name of the project');
            $table->text('description')->nullable()->comment('Description of the project');
            $table->string('status')->default(ProjectStatusEnum::PENDING)->comment('Status of the project');
            $table->string('url')->nullable()->comment('URL to the project or repository');
            $table->date('start_date')->nullable()->comment('Date when the project started');
            $table->date('end_date')->nullable()->comment('Date when the project ended');
            $table->string('image')->nullable()->comment('URL or path to the project image');
            $table->string('priority')->default(PriorityEnum::NORMAL)->comment('Priority of the project');
            $table->enum('is_active', ['y', 'n'])->default('y')->comment('Indicates if the project is currently active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
