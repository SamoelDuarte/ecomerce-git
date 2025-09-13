<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSmtpFieldsToUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('smtp_status')->default(0);
            $table->string('smtp_host')->nullable();
            $table->string('smtp_port')->nullable();
            $table->string('encryption')->default('tls');
            $table->string('smtp_username')->nullable();
            $table->string('smtp_password')->nullable();
            $table->string('from_mail')->nullable();
            $table->string('from_name')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'smtp_status',
                'smtp_host',
                'smtp_port',
                'encryption',
                'smtp_username',
                'smtp_password',
                'from_mail',
                'from_name'
            ]);
        });
    }
}