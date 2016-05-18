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
}
