<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('project_id')->unsigned(); // ここを追加
            $table->string('task_name', 100); // ここを追加
            $table->date('due_date'); // ここを追加
            $table->integer('task_status')->default(0); // ここを追加
            $table->timestamps();

            // 外部キーの設定
            $table->foreign('project_id')->references('id')->on('projects'); // ここを追加
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tasks');
    }
};