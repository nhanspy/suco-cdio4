<?php

namespace App\Console\Commands;

use App\Entities\SearchHistory;
use App\Entities\TranslationStatistic;
use App\Services\StatisticService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Exception;
use Illuminate\Support\Facades\Log;

class StatisticsTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'statistic:translations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Statistic Translation by Day';

    /** @var StatisticService */
    private $statService;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();

        $this->statService = app(StatisticService::class);
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $dayBefore = Carbon::today()->subDay(1);

        try {
            SearchHistory::groupBy('translation_id')
                ->selectRaw('count(*) as total, translation_id')
                ->orderBy('total', 'desc')
                ->whereDate('created_at', $dayBefore)
                ->chunk(100, function ($list) use ($dayBefore) {
                    foreach ($list as $item) {
                        TranslationStatistic::create([
                            'translation_id' => $item->translation_id,
                            'count' => $item->total,
                            'statistic_at' => $dayBefore,
                        ]);
                    }
                });

            Log::info("[STATISTIC_TRANSLATION][SUCCESS] => Statistic successfully");
            $this->info("[STATISTIC_TRANSLATION][SUCCESS] => Statistic successfully");
        } catch (Exception $e) {
            Log::error("[STATISTIC_TRANSLATION][FAILURE] => " . $e->getMessage());
            $this->error("[STATISTIC_TRANSLATION][FAILURE] => " . $e->getMessage());
        }
    }
}
