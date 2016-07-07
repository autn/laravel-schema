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
    protected $signature = 'db:schema {--path= : Path to save file} {--dbconnect= : Name of database} {--force : Run without confirmation } {--method= : Name of method (mysqldump/php) } {--refresh= : Public migration files and refresh migrations (yes/no) } {--type= : Type of file (sql/gzip/bzip2) }';

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
        $method = strtolower($this->option('method'));
        $refresh = strtolower($this->option('refresh'));
        $type = strtolower($this->option('type'));

        if ($type && !in_array($type, ['sql', 'gzip', 'bzip2'])) {
            $this->error('The type "' . $type . '" does not support');

            return;
        }

        if (!$dbconnect) {
            $dbconnect = 'mysql';
        }

        $username = Config::get('database.connections.' . $dbconnect . '.username');
        $password = Config::get('database.connections.' . $dbconnect . '.password');
        $host = Config::get('database.connections.' . $dbconnect . '.host');
        $database = Config::get('database.connections.' . $dbconnect . '.database');
        $filename = 'schema';

        switch ($type) {
            case 'gzip':
                $filetype = '.sql.gz';
                break;

            case 'bzip2':
                $filetype = '.sql.bz2';
                break;

            default:
                $filetype = '.sql';
                break;
        }
        $filename .= $filetype;

        if (!$pathparam) {
            $path = database_path();
            $pathparam = 'database';
        } else {
            $path = base_path() . '/' . $pathparam;
            if (!is_dir($path)) {
                $this->error('The path does not exist');

                return;
            }
        }

        try {
            $dbh = new PDO("mysql:host=$host;dbname=$database", $username, $password);
        } catch (Exception $e) {
            $this->error($e->getMessage()); //@codeCoverageIgnore

            return; //@codeCoverageIgnore
        }

        if ($refresh != 'no') {
            if ($force == 'true' || $this->confirm('Your database will refresh! Do you wish to continue? [yes|no]')) {
                Artisan::call('vendor:publish');
                Artisan::call('clear-compiled');
                Artisan::call('optimize');
                Artisan::call('migrate:refresh', [ '--database' => $dbconnect, '--force' => true ]);
            } else {
                return;
            }
        }

        if (!$method || $method == 'mysqldump') {
            try {
                if ($type == 'gzip') {
                    exec("mysqldump --user=$username --password=$password --host=$host $database | gzip > " . $path . '/' . $filename);
                } elseif ($type == 'bzip2') {
                    exec("mysqldump --user=$username --password=$password --host=$host $database | bzip2 > " . $path . '/' . $filename);
                } else {
                    exec("mysqldump --user=$username --password=$password --host=$host $database > " . $path . '/' . $filename);
                }

                $this->info('Generate successed, the file saved to: ' . $path . '/' . $filename);
            } catch (Exception $e) {
                $this->error($e->getMessage()); //@codeCoverageIgnore
                $this->info('You can select `php` method by add `--method=php` to command.');
            }
        } elseif ($method == 'php') {
            try {
                if ($type == 'gzip') {
                    $dumpSettings = ['compress' => IMysqldump::GZIP];
                    $dump = new IMysqldump("mysql:host=$host;dbname=$database", $username, $password, $dumpSettings);
                    $dump->start($path . '/' . $filename);
                } elseif ($type == 'bzip2') {
                    $dumpSettings = ['compress' => IMysqldump::BZIP2];
                    $dump = new IMysqldump("mysql:host=$host;dbname=$database", $username, $password, $dumpSettings);
                    $dump->start($path . '/' . $filename);
                } else {
                    $dump = new IMysqldump("mysql:host=$host;dbname=$database", $username, $password);
                    $dump->start($path . '/' . $filename);
                }

                $this->info('Generate successed, the file saved to: ' . $path . '/' . $filename);
            } catch (\Exception $e) {
                $this->error('Mysqldump-php error: ' . $e->getMessage()); //@codeCoverageIgnore
            }
        } else {
            $this->error('The method you selected does not support. You can select below methods: `mysqldump` or `php`');
        }
    }
}
