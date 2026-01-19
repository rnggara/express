<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class MergeSchemas extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'merge:schemas';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Merge schema (tables + columns) from teamplus into sanatel without data';

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
        $dbTarget = DB::connection('db_target');
        $dbFrom = DB::connection('db_from');

        $dbTargetTables = $dbTarget->select('SHOW TABLES');
        $dbFromTables = $dbFrom->select('SHOW TABLES');

        $dbTargetTableNames = array_map(fn($t) => reset($t), $dbTargetTables);
        $dbFromTableNames = array_map(fn($t) => reset($t), $dbFromTables);

        // 1. Copy missing tables
        $missingTables = array_diff($dbFromTableNames, $dbTargetTableNames);

        foreach ($missingTables as $table) {
            $this->info("Copying table structure: $table");

            $createTableSQL = $dbFrom->select("SHOW CREATE TABLE `$table`")[0]->{'Create Table'};

            // Replace DB name if any
            $createTableSQL = preg_replace('/CREATE TABLE `[^`]+`\.`/', 'CREATE TABLE `', $createTableSQL);

            DB::connection('db_target')->unprepared($createTableSQL);
        }

        // 2. Add missing columns
        $sharedTables = array_intersect($dbFromTableNames, $dbTargetTableNames);

        foreach ($sharedTables as $table) {
            $this->info("Checking columns in shared table: $table");

            $dbFromColumns = $dbFrom->select("SHOW COLUMNS FROM `$table`");
            $dbTargetColumns = $dbTarget->select("SHOW COLUMNS FROM `$table`");

            $dbTargetColumnNames = array_column($dbTargetColumns, 'Field');
            $dbFromColumnNames = array_column($dbFromColumns, 'Field');

            $missingColumns = array_diff($dbFromColumnNames, $dbTargetColumnNames);

            foreach ($missingColumns as $columnName) {
                // Get column definition
                foreach ($dbFromColumns as $col) {
                    if ($col->Field === $columnName) {
                        $type = strtolower($col->Type);
                        $isTextOrDateType = preg_match('/date|timestamp|text/', $type);

                        $definition = "`$col->Field` $col->Type";

                        if ($isTextOrDateType) {
                            $definition .= " NULL";
                        } else {
                            $definition .= $col->Null === 'NO' ? " NOT NULL" : " NULL";
                        }

                        if (!$isTextOrDateType && $col->Default !== null) {
                            $definition .= " DEFAULT " . DB::connection('teamplus')->getPdo()->quote($col->Default);
                        }

                        if ($col->Extra) {
                            $definition .= " $col->Extra";
                        }

                        $sql = "ALTER TABLE `$table` ADD $definition";
                        DB::connection('db_target')->unprepared($sql);

                        $this->info("➤ Added column `$columnName` to table `$table`");
                        break;
                    }
                }
            }
        }

        $this->info("✅ Schema merge complete.");
    }
}
