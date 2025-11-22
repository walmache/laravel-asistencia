<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organization_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->enum('status', ['scheduled', 'ongoing', 'finished'])->default('scheduled');
            $table->string('qr_code_path')->nullable();
            $table->string('barcode_code')->unique()->nullable();
            $table->float('face_threshold', 8, 2)->default(0.6);
            $table->boolean('allow_face_checkin')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};