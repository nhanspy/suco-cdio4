<?php

namespace App\Console\Commands;

use App\Entities\Like;
use App\Entities\Translation;
use Illuminate\Console\Command;

class TotalLikeTranslation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translation:countlike';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Set total like translation';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $translations = Translation::all();

        $bar = $this->output->createProgressBar(count($translations));
        $bar->start();

        foreach ($translations as $translation) {
            $translation->total_search = Like::where('translation_id', $translation->id)->count();
            $translation->save();

            $bar->advance();
        }

        $bar->finish();
    }
}
