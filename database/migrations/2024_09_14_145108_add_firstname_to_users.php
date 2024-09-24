<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename 'name' to 'username'
            $table->renameColumn('name', 'username');

            // Add 'firstname' and 'lastname' columns
            $table->string('firstname')->after('username');
            $table->string('lastname')->after('firstname');
        });

        // Run the SQL to update firstname and lastname
        DB::statement(
            "UPDATE users u
            JOIN admin a ON u.email = a.mail
            SET u.lastname = a.nom, u.firstname = a.prenom
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Rename 'username' back to 'name'
            $table->renameColumn('username', 'name');

            // Drop 'firstname' and 'lastname' columns
            $table->dropColumn('firstname');
            $table->dropColumn('lastname');
        });
    }
};
