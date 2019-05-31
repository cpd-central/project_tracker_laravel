<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTextIndex extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  { 
    Schema::table('projects', function (Blueprint $table) {
      $table->index(
        [
          "cegproposalauthor" => "text",
          "projectname" => "text",
          "clientcontactname" => "text",
          "clientcompany" => "text",
          "projectstatus" => "text",
          "projectcode" => "text",
          "projectmanager" => "text"

        ],
        'project_full_text',
        null,
        [
          "weights" => [
            "cegproposalauthor" => 1,
            "projectname" => 1,
            "clientcontactname" => 1,
            "clientcompany" => 1,
            "projectstatus" => 1, 
            "projectcode" => 1,
            "projectmanager" => 1
          ],
          'name' => 'project_full_text'
        ] 
      );
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('projects', function (Blueprint $table) {
      $table->dropIndex(['project_full_text']);
    });
  }
}
