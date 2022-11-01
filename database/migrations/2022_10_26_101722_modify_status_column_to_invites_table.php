<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `invites` CHANGE `status` `status` ENUM ('active', 'inactive', 'pending', 'expired') NOT NULL DEFAULT 'active';");

        DB::statement("UPDATE `invites` SET `status` = 'pending' WHERE `status` = 'active';");

        DB::statement("UPDATE `invites` SET `status` = 'expired' WHERE `status` = 'inactive';");

        DB::statement("ALTER TABLE `invites` CHANGE `status` `status` ENUM ('pending', 'expired') NOT NULL DEFAULT 'pending';");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `invites` CHANGE `status` `status` ENUM ('pending', 'expired', 'active', 'inactive') NOT NULL DEFAULT 'pending';");

        DB::statement("UPDATE `invites` SET status = 'active' WHERE `status` = 'pending';");

        DB::statement("UPDATE `invites` SET `status` = 'inactive' WHERE `status` = 'expired';");

        DB::statement("ALTER TABLE `invites` CHANGE `status` `status` ENUM ('active', 'inactive') NOT NULL DEFAULT 'active';");
    }
};
