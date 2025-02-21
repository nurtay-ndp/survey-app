<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('user_responses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('question_id')->constrained()->onDelete('cascade');
            $table->foreignId('answer_id')->nullable()->constrained()->onDelete('cascade');
            $table->string('custom_answer')->nullable(); // Кастомный ответ
            $table->ipAddress('user_ip'); // IP пользователя
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_responses');
    }
};

