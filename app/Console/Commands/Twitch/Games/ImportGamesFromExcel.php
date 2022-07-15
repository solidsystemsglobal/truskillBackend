<?php

namespace App\Console\Commands\Twitch\Games;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use App\Services\Twitch\TwitchGameService;

class ImportGamesFromExcel extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'games:import-from-excel {--path=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import twitch game data from excel file.';

    /**
     * @var \Illuminate\Http\UploadedFile
     */
    protected $file;

    /**
     * @var \PhpOffice\PhpSpreadsheet\Reader\Xlsx
     */
    protected $reader;

    /**
     * @var string
     */
    protected $path;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->reader = new Xlsx();

        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $this->path = $this->option('path');

        if (file_exists($this->path)) {
            $this->info("File \"$this->path\" detected!");
            $spreadsheet = $this->reader->load($this->path);
            $sheetsCount = $spreadsheet->getSheetCount();
            $this->info("Detected $sheetsCount sheets.");
            $gameService = resolve(TwitchGameService::class);

            try {
                for ($i = 1; $i <= $sheetsCount; $i++) {
                    $this->info("Reading $i sheet...");
                    $workSheet = $spreadsheet->getSheet($i - 1);
                    $sheetData = $workSheet->toArray();
                    unset($sheetData[0]);
                    $rowsCount = count($sheetData);
                    $this->info("Detected $rowsCount rows.");
                    $index = 0;
                    $gamesData = [];

                    foreach ($sheetData as $key => $row) {
                        $this->info("Reading $key row.");
                        $gamesData[] = [
                            'twitch_id' => '',
                            'name' => $row[2],
                            'box_art_url' => $row[1],
                        ];
                        $index++;


                        if ($index >= 100 ) {
                            $gameService->createMany($gamesData, true);
                            $index = 0;
                            $gamesData = [];
                        }
                        $this->info("Game '{$row[2]}' created.");
                    }

                    $gameService->createMany($gamesData, true);
                    $this->info("#$i sheet was scanned!");
                }
            } catch (\Exception $exp) {
                $this->error("Something went wrong! (Check log file!)");

                Log::error("Games upload from excel field!", ['Exception' => $exp]);
            }

            $this->info("All file was scanned!");
        } else {
            $this->error("File \"$this->path\" doesn't exists!");
        }
    }
}
