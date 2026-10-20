<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('episodes', function (Blueprint $table) {
            // kn = Kannada, en = English, both = bilingual; null = not tagged yet
            $table->string('language', 10)->nullable()->after('title')->index();
        });

        // Tag episodes whose titles say which language they are in. Everything
        // else stays untagged so it can be set by hand in the admin.
        DB::table('episodes')->where('title', 'like', '%English Podcast%')->update(['language' => 'en']);

        DB::table('episodes')->whereNull('language')->get(['id', 'title'])->each(function ($episode) {
            if (preg_match('/Kannada Podcast|Business Giseness Kannada|\p{Kannada}/u', $episode->title)) {
                DB::table('episodes')->where('id', $episode->id)->update(['language' => 'kn']);
            }
        });
    }

    public function down(): void
    {
        Schema::table('episodes', function (Blueprint $table) {
            $table->dropIndex(['language']);
            $table->dropColumn('language');
        });
    }
};
