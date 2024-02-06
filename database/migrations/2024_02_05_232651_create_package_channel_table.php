<?php

use App\Models\Package;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('package_channel', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('package_id')->unsigned()->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('package_id')->references('id')->on((new Package())->getTable())->nullOnDelete();
            $table->foreign('user_id')->references('id')->on((new User())->getTable())->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_channel');
    }
};
