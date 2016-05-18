<?php

namespace Autn\Schema\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputArgument;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Artisan;
use PDO;

class DumpSql extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'db:schema {--path= : Path to save file} {--dbconnect= : Name of database} {--force= : Force }';

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

            try {
                exec("mysqldump --user=$username --password=$password --host=$host $database > " . $path . '/' . $filename);

                $this->info('Generate successed, the file saved to: ' . $path . '/' . $filename);
            } catch (Exception $e) {
                $this->info($e->getMessage()); //@codeCoverageIgnore
            }
        }
    }
}
