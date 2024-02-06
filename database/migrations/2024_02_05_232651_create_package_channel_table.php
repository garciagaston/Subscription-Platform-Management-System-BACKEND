<?php

use App\Models\Channel;
use App\Models\Package;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class () extends Migration {
    public function up()
    {
        Schema::create('package_channel', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('package_id')->unsigned()->nullable();
            $table->bigInteger('channel_id')->unsigned()->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('package_id')->references('id')->on((new Package())->getTable())->nullOnDelete();
            $table->foreign('channel_id')->references('id')->on((new Channel())->getTable())->nullOnDelete();
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_channel');
    }
};
