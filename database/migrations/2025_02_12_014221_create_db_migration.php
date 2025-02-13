<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

return new class extends Migration {
    public function up()
    {
        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamps();
        });
        Schema::create('contacts', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->unsignedBigInteger('created_by')->nullable();
            $table->string('phone');
            $table->unsignedBigInteger('manager_id')->nullable();
            $table->timestamps();
            $table->foreign('manager_id')->references('id')->on('managers')->onDelete('set null');
        });
        Schema::create('pipelines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('pipeline_columns', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('pipeline_id');
            $table->integer('order');
            $table->timestamps();
            $table->foreign('pipeline_id')->references('id')->on('pipelines')->onDelete('cascade');
        });
        Schema::create('opportunities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('manager_id');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('pipeline_column_id')->nullable();
            $table->timestamps();
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('manager_id')->references('id')->on('managers')->onDelete('cascade');
            $table->foreign('pipeline_column_id')->references('id')->on('pipeline_columns')->onDelete('set null');
        });


        Schema::create('lists', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('tags', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });




        Schema::create('opportunity_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('opportunity_id');
            $table->unsignedBigInteger('tag_id');
            $table->primary(['opportunity_id', 'tag_id']);
            $table->foreign('opportunity_id')->references('id')->on('opportunities')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });

        Schema::create('tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->date('due_date');
            $table->string('status');
            $table->unsignedBigInteger('opportunity_id')->nullable();
            $table->unsignedBigInteger('contact_id')->nullable();
            $table->unsignedBigInteger('manager_id');
            $table->timestamps();
            $table->foreign('opportunity_id')->references('id')->on('opportunities')->onDelete('set null');
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('set null');
            $table->foreign('manager_id')->references('id')->on('managers')->onDelete('cascade');
        });

        Schema::create('contact_lists', function (Blueprint $table) {
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('list_id');
            $table->primary(['contact_id', 'list_id']);
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('list_id')->references('id')->on('lists')->onDelete('cascade');
        });

        Schema::create('contact_tags', function (Blueprint $table) {
            $table->unsignedBigInteger('contact_id');
            $table->unsignedBigInteger('tag_id');
            $table->primary(['contact_id', 'tag_id']);
            $table->foreign('contact_id')->references('id')->on('contacts')->onDelete('cascade');
            $table->foreign('tag_id')->references('id')->on('tags')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('contact_tags');
        Schema::dropIfExists('contact_lists');
        Schema::dropIfExists('tasks');
        Schema::dropIfExists('opportunity_tags');
        Schema::dropIfExists('opportunity_pipeline_columns');
        Schema::dropIfExists('pipeline_columns');
        Schema::dropIfExists('pipelines');
        Schema::dropIfExists('opportunities');
        Schema::dropIfExists('tags');
        Schema::dropIfExists('lists');
        Schema::dropIfExists('managers');
        Schema::dropIfExists('contacts');
    }
};
