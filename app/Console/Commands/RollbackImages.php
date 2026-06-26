<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use App\Models\Question;
use App\Models\Answer;

class RollbackImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:rollback';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Rollback sanitization changes based on the log file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $logPath = "/Users/mahmoudessa/.gemini/antigravity-ide/brain/efad9bae-c126-4420-9609-d1d171fa6b62/.system_generated/tasks/task-119.log";

        if (!File::exists($logPath)) {
            $this->error("Log file not found: {$logPath}");
            return;
        }

        $lines = file($logPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        $mode = ''; // 'questions' or 'answers'
        $totalRollbacked = 0;

        foreach ($lines as $line) {
            if (str_contains($line, 'Processing Question Images...')) {
                $mode = 'questions';
                $this->info("Switching to Questions rollback...");
                continue;
            }
            if (str_contains($line, 'Processing Answer Images...')) {
                $mode = 'answers';
                $this->info("Switching to Answers rollback...");
                continue;
            }

            // Match: Rename: 'old_name' -> 'new_name' (Used by X ...)
            if (preg_match("/Rename:\s+'(.*)'\s+->\s+'(.*)'\s+\(Used by/", $line, $matches)) {
                $oldName = $matches[1];
                $newName = $matches[2];

                if ($mode === 'questions') {
                    $dir = public_path('upload/questions/images/');
                    $currentPath = $dir . $newName;
                    $targetPath = $dir . $oldName;

                    if (File::exists($currentPath)) {
                        // Ensure parent directory exists
                        File::ensureDirectoryExists(dirname($targetPath));
                        File::move($currentPath, $targetPath);
                        Question::where('qu_image', $newName)->update(['qu_image' => $oldName]);
                        $totalRollbacked++;
                    }
                } elseif ($mode === 'answers') {
                    $dir = public_path('upload/answers/images/');
                    $currentPath = $dir . $newName;
                    $targetPath = $dir . $oldName;

                    if (File::exists($currentPath)) {
                        // Ensure parent directory exists
                        File::ensureDirectoryExists(dirname($targetPath));
                        File::move($currentPath, $targetPath);
                        Answer::where('answer_image', $newName)->update(['answer_image' => $oldName]);
                        $totalRollbacked++;
                    }
                }
            }
        }

        $this->info("Rollback complete! Total files restored: {$totalRollbacked}");
    }
}
