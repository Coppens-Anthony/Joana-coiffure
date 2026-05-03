<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('appointment_service', function (Blueprint $table) {
            $table->foreignId('appointment_id')->constrained();
            $table->foreignId('service_id')->constrained();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('appointment_service');
    }
};
