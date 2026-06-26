<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use App\Models\Question;
use App\Models\Answer;

class SanitizeImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'images:sanitize {--dry-run : Simulate the sanitization process without making changes}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sanitize question and answer image filenames on disk and in database preserving directories';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $dryRun = $this->option('dry-run');

        if ($dryRun) {
            $this->info('--- RUNNING IN DRY-RUN MODE (NO CHANGES WILL BE MADE) ---');
        } else {
            $this->warn('--- WARNING: ACTIVE MODE. THIS WILL RENAME FILES ON DISK AND UPDATE DATABASE RECORDS ---');
            if (!$this->confirm('Do you want to proceed?')) {
                $this->info('Cancelled.');
                return;
            }
        }

        $this->sanitizeQuestionImages($dryRun);
        $this->sanitizeAnswerImages($dryRun);

        $this->info('Finished successfully!');
    }

    protected function sanitizeQuestionImages($dryRun)
    {
        $this->comment('Processing Question Images...');
        $dir = public_path('upload/questions/images/');

        if (!File::isDirectory($dir)) {
            $this->error("Directory not found: {$dir}");
            return;
        }

        $questions = Question::where('questions_type', 'image')
            ->whereNotNull('qu_image')
            ->where('qu_image', '!=', '')
            ->get()
            ->groupBy('qu_image');

        $totalChecked = 0;
        $totalRenamed = 0;

        foreach ($questions as $oldName => $group) {
            $totalChecked++;
            
            // Decode and split path
            $decoded = rawurldecode($oldName);
            $dirname = pathinfo($decoded, PATHINFO_DIRNAME);
            $basename = pathinfo($decoded, PATHINFO_FILENAME);
            $extension = pathinfo($decoded, PATHINFO_EXTENSION);

            $cleanBasename = Str::slug($basename);
            if (empty($cleanBasename)) {
                $cleanBasename = 'img_' . uniqid();
            }

            $sanitizedName = $cleanBasename . ($extension ? '.' . strtolower($extension) : '');
            $sanitized = ($dirname && $dirname !== '.') ? $dirname . '/' . $sanitizedName : $sanitizedName;

            if ($decoded === $sanitized && $oldName === $sanitized) {
                continue;
            }

            // Find physical file on disk (accounting for NFC/NFD)
            $physicalPath = $this->findPhysicalFile($dir, $oldName);
            
            if ($physicalPath) {
                // Find a unique name in the same directory
                $newName = $sanitized;
                $counter = 1;
                while (File::exists($dir . $newName) && $newName !== $oldName) {
                    $newName = ($dirname && $dirname !== '.') 
                        ? $dirname . '/' . $cleanBasename . '-' . $counter . ($extension ? '.' . strtolower($extension) : '')
                        : $cleanBasename . '-' . $counter . ($extension ? '.' . strtolower($extension) : '');
                    $counter++;
                }

                $this->info("Rename: '{$oldName}' -> '{$newName}' (Used by " . $group->count() . " questions)");

                if (!$dryRun) {
                    // Ensure subdirectory exists in target directory
                    File::ensureDirectoryExists(dirname($dir . $newName));
                    if (File::move($physicalPath, $dir . $newName)) {
                        Question::where('qu_image', $oldName)->update(['qu_image' => $newName]);
                        $totalRenamed++;
                    } else {
                        $this->error("Failed to rename file: {$physicalPath}");
                    }
                } else {
                    $totalRenamed++;
                }
            } else {
                $this->warn("File missing on disk: '{$oldName}' (Used by " . $group->count() . " questions). DB name will be updated to: '{$sanitized}'");
                if (!$dryRun) {
                    Question::where('qu_image', $oldName)->update(['qu_image' => $sanitized]);
                }
            }
        }

        $this->info("Questions processed: {$totalChecked}, Renamed: {$totalRenamed}");
    }

    protected function sanitizeAnswerImages($dryRun)
    {
        $this->comment('Processing Answer Images...');
        $dir = public_path('upload/answers/images/');

        if (!File::isDirectory($dir)) {
            $this->error("Directory not found: {$dir}");
            return;
        }

        $answers = Answer::where('answer_type', 'image')
            ->whereNotNull('answer_image')
            ->where('answer_image', '!=', '')
            ->get()
            ->groupBy('answer_image');

        $totalChecked = 0;
        $totalRenamed = 0;

        foreach ($answers as $oldName => $group) {
            $totalChecked++;
            
            // Decode and split path
            $decoded = rawurldecode($oldName);
            $dirname = pathinfo($decoded, PATHINFO_DIRNAME);
            $basename = pathinfo($decoded, PATHINFO_FILENAME);
            $extension = pathinfo($decoded, PATHINFO_EXTENSION);

            $cleanBasename = Str::slug($basename);
            if (empty($cleanBasename)) {
                $cleanBasename = 'ans_' . uniqid();
            }

            $sanitizedName = $cleanBasename . ($extension ? '.' . strtolower($extension) : '');
            $sanitized = ($dirname && $dirname !== '.') ? $dirname . '/' . $sanitizedName : $sanitizedName;

            if ($decoded === $sanitized && $oldName === $sanitized) {
                continue;
            }

            // Find physical file on disk (accounting for NFC/NFD)
            $physicalPath = $this->findPhysicalFile($dir, $oldName);
            
            if ($physicalPath) {
                // Find a unique name in the same directory
                $newName = $sanitized;
                $counter = 1;
                while (File::exists($dir . $newName) && $newName !== $oldName) {
                    $newName = ($dirname && $dirname !== '.') 
                        ? $dirname . '/' . $cleanBasename . '-' . $counter . ($extension ? '.' . strtolower($extension) : '')
                        : $cleanBasename . '-' . $counter . ($extension ? '.' . strtolower($extension) : '');
                    $counter++;
                }

                $this->info("Rename: '{$oldName}' -> '{$newName}' (Used by " . $group->count() . " answers)");

                if (!$dryRun) {
                    // Ensure subdirectory exists in target directory
                    File::ensureDirectoryExists(dirname($dir . $newName));
                    if (File::move($physicalPath, $dir . $newName)) {
                        Answer::where('answer_image', $oldName)->update(['answer_image' => $newName]);
                        $totalRenamed++;
                    } else {
                        $this->error("Failed to rename file: {$physicalPath}");
                    }
                } else {
                    $totalRenamed++;
                }
            } else {
                $this->warn("File missing on disk: '{$oldName}' (Used by " . $group->count() . " answers). DB name will be updated to: '{$sanitized}'");
                if (!$dryRun) {
                    Answer::where('answer_image', $oldName)->update(['answer_image' => $sanitized]);
                }
            }
        }

        $this->info("Answers processed: {$totalChecked}, Renamed: {$totalRenamed}");
    }

    /**
     * Find physical file on disk checking exact name, NFC, and NFD.
     */
    protected function findPhysicalFile($dir, $filename)
    {
        $decoded = rawurldecode($filename);
        $paths = [
            $dir . $filename,
            $dir . $decoded
        ];

        if (class_exists('Normalizer')) {
            $paths[] = $dir . \Normalizer::normalize($filename, \Normalizer::FORM_D);
            $paths[] = $dir . \Normalizer::normalize($filename, \Normalizer::FORM_C);
            $paths[] = $dir . \Normalizer::normalize($decoded, \Normalizer::FORM_D);
            $paths[] = $dir . \Normalizer::normalize($decoded, \Normalizer::FORM_C);
        }

        foreach (array_unique($paths) as $path) {
            if (File::exists($path)) {
                return $path;
            }
        }

        return null;
    }
}
