<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateChatsShema extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('chats', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable(true);
            $table->boolean('is_group_chat')->default(false);
            $table->unsignedBigInteger('author_id')->nullable(true);
            $table->string('logo')->nullable(true);

            $table->foreign('author_id')
                ->references('id')
                ->on('directory_users')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('chat_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sender_id')->nullable(true);
//            $table->unsignedBigInteger('recipient_id')->nullable(true);
            $table->unsignedBigInteger('chat_id')->nullable(true);
            $table->text('text')->nullable(true);
            $table->json('params')->nullable(true);

            $table->foreign('sender_id')
                ->references('id')
                ->on('directory_users')
                ->onDelete('cascade');

//            $table->foreign('recipient_id')
//                ->references('id')
//                ->on('chats')
//                ->onDelete('cascade');

            $table->foreign('chat_id')
                ->references('id')
                ->on('chats')
                ->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('chat_user', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('chat_id')->nullable(true);
            $table->unsignedBigInteger('user_id')->nullable(true);

            $table->boolean('is_fixed')->default(false);
            $table->boolean('is_notification')->default(true);
            $table->boolean('is_delete')->default(false);

            $table->foreign('chat_id')
                ->references('id')
                ->on('chats')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('directory_users')
                ->onDelete('cascade');
        });

        Schema::create('chat_message_status', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('message_id')->nullable(true);
            $table->unsignedBigInteger('user_id')->nullable(true);
            $table->boolean('is_read')->default(false);

            $table->foreign('message_id')
                ->references('id')
                ->on('chat_messages')
                ->onDelete('cascade');

            $table->foreign('user_id')
                ->references('id')
                ->on('directory_users')
                ->onDelete('cascade');

            $table->timestamps();
        });

        DB::table('chats')->insert([
            ['name' => __('default.chat_title'), 'is_group_chat' => \App\Core\Model\Yesno::YES]
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('chats');
        Schema::dropIfExists('chat_messages');
        Schema::dropIfExists('chat_message_status');
        Schema::dropIfExists('chat_user');
    }
}
