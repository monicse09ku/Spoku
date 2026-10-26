<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->boolean('help_requested_lawyers')->default(false)->after('is_anonymous');
            $table->boolean('help_requested_counselors')->default(false)->after('help_requested_lawyers');
            $table->boolean('help_requested_law_enforcers')->default(false)->after('help_requested_counselors');
            $table->boolean('help_requested_humanitarians')->default(false)->after('help_requested_law_enforcers');
            $table->text('admin_notes')->nullable()->after('help_requested_humanitarians');
        });

        $threshold = (int) (config('spoku.approval_threshold') ?: 7);

        $pendingIds = DB::table('complaints')->where('status', 'pending')->pluck('id');

        foreach ($pendingIds as $id) {
            $approved = DB::table('complaint_approvals')
                ->where('complaint_id', $id)
                ->where('decision', 'approved')
                ->count();

            if ($approved >= $threshold) {
                DB::table('complaints')->where('id', $id)->update([
                    'status' => 'ready_to_publish',
                    'published_at' => null,
                ]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('complaints', function (Blueprint $table) {
            $table->dropColumn([
                'help_requested_lawyers',
                'help_requested_counselors',
                'help_requested_law_enforcers',
                'help_requested_humanitarians',
                'admin_notes',
            ]);
        });
    }
};
