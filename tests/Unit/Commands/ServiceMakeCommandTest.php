<?php

use Orchestra\Testbench\Concerns\InteractsWithPublishedFiles;

pest()->use(InteractsWithPublishedFiles::class);

beforeEach(function (): void {
    $this->files = [
        'app/Services/FooService.php',
        'app/Services/Foo/BarService.php',
    ];
});

it('can generate service file', function (): void {
    $this->artisan('make:service', ['name' => 'FooService'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Services;',
        'class FooService',
    ], 'app/Services/FooService.php');

    $this->assertFilenameNotExists('app/Services/Foo/BarService.php');
});

it('can generate service file with namespace', function (): void {
    $this->artisan('make:service', ['name' => 'Foo\\BarService'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Services\Foo;',
        'class BarService',
    ], 'app/Services/Foo/BarService.php');

    $this->assertFilenameNotExists('app/Services/FooService.php');
});

it('can force overwrite existing service', function (): void {
    $this->artisan('make:service', ['name' => 'FooService'])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Services;',
        'class FooService',
    ], 'app/Services/FooService.php');

    // Force overwrite
    $this->artisan('make:service', ['name' => 'FooService', '--force' => true])
        ->assertExitCode(0);

    $this->assertFileContains([
        'namespace App\Services;',
        'class FooService',
    ], 'app/Services/FooService.php');
});
