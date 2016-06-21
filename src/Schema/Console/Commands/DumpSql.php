<?php

namespace Autn\Schema\Console\Commands;

use Ifsnop\Mysqldump\Mysqldump as IMysqldump;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;
use PDO;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;

class DumpSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:schema {--path= : Path to save file} {--dbconnect= : Name of database} {--force= : Force (true/false) } {--method= : Name of method (mysqldump/php) }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Dump base database to sql file';

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
        $pathparam = $this->option('path');
        $dbconnect = $this->option('dbconnect');
        $force = $this->option('force');
        $method = $this->option('method');

        if (!$dbconnect) {
            $dbconnect = 'mysql';
        }

        $username = Config::get('database.connections.' . $dbconnect . '.username');
        $password = Config::get('database.connections.' . $dbconnect . '.password');
        $host = Config::get('database.connections.' . $dbconnect . '.host');
        $database = Config::get('database.connections.' . $dbconnect . '.database');
        $filename = 'schema.sql';

        if (!$pathparam) {
            $path = database_path();
            $pathparam = 'database';
        } else {
            $path = base_path() . '/' . $pathparam;
            if (!is_dir($path)) {
                $this->info('The path does not exist');

                return;
            }
        }

        try {
            $dbh = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        } catch (Exception $e) {
            $this->info($e->getMessage()); //@codeCoverageIgnore

            return; //@codeCoverageIgnore
        }

        if ($force || $this->confirm('Your database will refresh! Do you wish to continue? [yes|no]'))
        {
            Artisan::call('vendor:publish');
            Artisan::call('clear-compiled');
            Artisan::call('optimize');
            Artisan::call('migrate:refresh', [ '--database' => $dbconnect, '--force' => true ]);

            if (!$method || $method == 'mysqldump') {
                try {
                    exec("mysqldump --user=$username --password=$password --host=$host $database > " . $path . '/' . $filename);

                    $this->info('Generate successed, the file saved to: ' . $path . '/' . $filename);
                } catch (Exception $e) {
                    $this->info($e->getMessage()); //@codeCoverageIgnore
                }
            } elseif ($method == 'php') {
                try {
                    $dump = new IMysqldump("mysql:host=$host;dbname=$database", $username, $password);
                    $dump->start($path . '/' . $filename);
                    $this->info('Generate successed, the file saved to: ' . $path . '/' . $filename);
                } catch (\Exception $e) {
                    $this->info('Mysqldump-php error: ' . $e->getMessage()); //@codeCoverageIgnore
                }
            } else {
                $this->info('The method you selected does not support. You can select below methods: `mysqldump` or `php`');
            }
        }
    }
}
