<?php

class SchemaTest extends SchemaTestCase
{
    public function testNoOptions()
    {
        $dbFile = fopen(base_path() . '/data/schema.sql', 'r');
        $fileSize = filesize(base_path() . '/data/schema.sql');

        Artisan::call('db:schema', ['--force' => true]);

        $this->assertTrue(file_exists(base_path() . '/database/schema.sql'));

        $schemaDbFile = fopen(base_path() . '/database/schema.sql', 'r');
        $schemaFileSize = filesize(base_path() . '/database/schema.sql');

        while (($line = fgets($dbFile)) !== false) {
            $schemaLine = fgets($schemaDbFile);
            if (substr($schemaLine, 0, 2) !== '--' && substr($schemaLine, 0, 2) !== '/*') {
                $this->assertEquals($line, $schemaLine);
            }
        }

        fclose($dbFile);
        fclose($schemaDbFile);
        unlink(base_path() . '/database/schema.sql');
    }

    public function testWithPathOption()
    {
        $dbFile = fopen(base_path() . '/data/schema.sql', 'r');
        $fileSize = filesize(base_path() . '/data/schema.sql');

        Artisan::call('db:schema', ['--force' => true, '--path' => 'database/factories']);

        $this->assertTrue(file_exists(base_path() . '/database/factories/schema.sql'));

        $schemaDbFile = fopen(base_path() . '/database/factories/schema.sql', 'r');
        $schemaFileSize = filesize(base_path() . '/database/factories/schema.sql');

        while (($line = fgets($dbFile)) !== false) {
            $schemaLine = fgets($schemaDbFile);
            if (substr($schemaLine, 0, 2) !== '--' && substr($schemaLine, 0, 2) !== '/*') {
                $this->assertEquals($line, $schemaLine);
            }
        }

        fclose($dbFile);
        fclose($schemaDbFile);
        unlink(base_path() . '/database/factories/schema.sql');
    }

    public function testNoPath()
    {
        $info = Artisan::call('db:schema', ['--force' => true, '--path' => 'databasexyz']);

        $this->assertFalse(file_exists(base_path() . '/databasexyz/schema.sql'));
    }

    public function testWithDbconnectOption()
    {
        $dbFile = fopen(base_path() . '/data/schema.sql', 'r');
        $fileSize = filesize(base_path() . '/data/schema.sql');

        Artisan::call('db:schema', ['--force' => true, '--dbconnect' => 'mysql2']);

        $this->assertTrue(file_exists(base_path() . '/database/schema.sql'));

        $schemaDbFile = fopen(base_path() . '/database/schema.sql', 'r');
        $schemaFileSize = filesize(base_path() . '/database/schema.sql');

        while (($line = fgets($dbFile)) !== false) {
            $schemaLine = fgets($schemaDbFile);
            if (substr($schemaLine, 0, 2) !== '--' && substr($schemaLine, 0, 2) !== '/*') {
                $this->assertEquals($line, $schemaLine);
            }
        }

        fclose($dbFile);
        fclose($schemaDbFile);
        unlink(base_path() . '/database/schema.sql');
    }

    public function testWithPathAndDbconnectOptions()
    {
        $dbFile = fopen(base_path() . '/data/schema.sql', 'r');
        $fileSize = filesize(base_path() . '/data/schema.sql');

        Artisan::call('db:schema', ['--force' => true, '--path' => 'database/factories', '--dbconnect' => 'mysql2']);

        $this->assertTrue(file_exists(base_path() . '/database/factories/schema.sql'));

        $schemaDbFile = fopen(base_path() . '/database/factories/schema.sql', 'r');
        $schemaFileSize = filesize(base_path() . '/database/factories/schema.sql');

        while (($line = fgets($dbFile)) !== false) {
            $schemaLine = fgets($schemaDbFile);
            if (substr($schemaLine, 0, 2) !== '--' && substr($schemaLine, 0, 2) !== '/*') {
                $this->assertEquals($line, $schemaLine);
            }
        }

        fclose($dbFile);
        fclose($schemaDbFile);
        unlink(base_path() . '/database/factories/schema.sql');
    }

    public function testWithMethodOption()
    {
        $dbFile = fopen(base_path() . '/data/schema-php.sql', 'r');
        $fileSize = filesize(base_path() . '/data/schema-php.sql');

        Artisan::call('db:schema', ['--force' => true, '--method' => 'php']);

        $this->assertTrue(file_exists(base_path() . '/database/schema.sql'));

        $schemaDbFile = fopen(base_path() . '/database/schema.sql', 'r');
        $schemaFileSize = filesize(base_path() . '/database/schema.sql');

        while (($line = fgets($dbFile)) !== false) {
            $schemaLine = fgets($schemaDbFile);
            if (substr($schemaLine, 0, 2) !== '--' && substr($schemaLine, 0, 2) !== '/*') {
                $this->assertEquals($line, $schemaLine);
            }
        }

        fclose($dbFile);
        fclose($schemaDbFile);
        unlink(base_path() . '/database/schema.sql');
    }

    public function testWithRefreshOption()
    {
        $dbFile = fopen(base_path() . '/data/schema-php.sql', 'r');
        $fileSize = filesize(base_path() . '/data/schema-php.sql');

        Artisan::call('db:schema', ['--force' => true]);
        Artisan::call('db:schema', ['--force' => true, '--refresh' => 'no', '--method' => 'php']);

        $this->assertTrue(file_exists(base_path() . '/database/schema.sql'));

        $schemaDbFile = fopen(base_path() . '/database/schema.sql', 'r');
        $schemaFileSize = filesize(base_path() . '/database/schema.sql');

        while (($line = fgets($dbFile)) !== false) {
            $schemaLine = fgets($schemaDbFile);
            if (substr($schemaLine, 0, 2) !== '--' && substr($schemaLine, 0, 2) !== '/*') {
                $this->assertEquals($line, $schemaLine);
            }
        }

        fclose($dbFile);
        fclose($schemaDbFile);
        unlink(base_path() . '/database/schema.sql');
    }

    public function testWithTypeOption()
    {
        Artisan::call('db:schema', ['--force' => true, '--type' => 'Gzip']);
        $this->assertTrue(file_exists(base_path() . '/database/schema.sql.gz'));
        unlink(base_path() . '/database/schema.sql.gz');

        Artisan::call('db:schema', ['--force' => true, '--type' => 'bzip2']);
        $this->assertTrue(file_exists(base_path() . '/database/schema.sql.bz2'));
        unlink(base_path() . '/database/schema.sql.bz2');

        Artisan::call('db:schema', ['--force' => true, '--type' => 'sql']);
        $this->assertTrue(file_exists(base_path() . '/database/schema.sql'));
        unlink(base_path() . '/database/schema.sql');

        Artisan::call('db:schema', ['--force' => true, '--type' => 'gzip', '--method' => 'mysqldump']);
        $this->assertTrue(file_exists(base_path() . '/database/schema.sql.gz'));
        unlink(base_path() . '/database/schema.sql.gz');

        Artisan::call('db:schema', ['--force' => true, '--type' => 'BZIP2', '--method' => 'mysqldump']);
        $this->assertTrue(file_exists(base_path() . '/database/schema.sql.bz2'));
        unlink(base_path() . '/database/schema.sql.bz2');

        Artisan::call('db:schema', ['--force' => true, '--type' => 'sql', '--method' => 'mysqldump']);
        $this->assertTrue(file_exists(base_path() . '/database/schema.sql'));
        unlink(base_path() . '/database/schema.sql');

        Artisan::call('db:schema', ['--force' => true, '--type' => 'gzip', '--method' => 'php']);
        $this->assertTrue(file_exists(base_path() . '/database/schema.sql.gz'));
        unlink(base_path() . '/database/schema.sql.gz');

        Artisan::call('db:schema', ['--force' => true, '--type' => 'bzip2', '--method' => 'php']);
        $this->assertTrue(file_exists(base_path() . '/database/schema.sql.bz2'));
        unlink(base_path() . '/database/schema.sql.bz2');

        Artisan::call('db:schema', ['--force' => true, '--type' => 'sql', '--method' => 'php']);
        $this->assertTrue(file_exists(base_path() . '/database/schema.sql'));
        unlink(base_path() . '/database/schema.sql');
    }

    public function testFailure() {
        // Run refresh migrations
        Artisan::call('db:schema', ['--force' => true]);
        unlink(base_path() . '/database/schema.sql');
        Artisan::call('db:schema', ['--refresh' => 'no', '--method' => 'php2']);

        $this->assertFalse(file_exists(base_path() . '/database/schema.sql'));
        $this->assertFalse(file_exists(base_path() . '/database/schema.sql.gz'));
        $this->assertFalse(file_exists(base_path() . '/database/schema.sql.bz2'));

        Artisan::call('db:schema', ['--refresh' => 'no', '--type' => 'type']);

        $this->assertFalse(file_exists(base_path() . '/database/schema.sql'));
        $this->assertFalse(file_exists(base_path() . '/database/schema.sql.gz'));
        $this->assertFalse(file_exists(base_path() . '/database/schema.sql.bz2'));
    }
}
