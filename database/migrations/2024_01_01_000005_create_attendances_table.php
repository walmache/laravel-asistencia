<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->dateTime('check_in_at');
            $table->enum('method', ['manual', 'qr', 'barcode', 'face_recognition']);
            $table->enum('status', ['present', 'absent', 'justified'])->default('present');
            $table->json('metadata')->nullable(); // Store device info, IP, confidence, etc.
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('attendances');
    }
};