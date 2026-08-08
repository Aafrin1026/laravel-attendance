<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('attendance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->date('date');
            $table->enum('status', ['present', 'absent', 'late'])->default('absent');
            $table->timestamp('marked_at')->useCurrent();
            $table->unique(['student_id', 'subject_id', 'date']);
            $table->index(['student_id']);
            $table->index(['subject_id']);
            $table->index(['date']);
        });
    }
    public function down(): void { Schema::dropIfExists('attendance'); }
};
