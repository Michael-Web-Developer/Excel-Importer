<?php

use App\Models\User;
use Illuminate\Http\UploadedFile;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

beforeEach(function () {
    Storage::fake('local');
});

test('successfully uploads a valid xlsx file', function () {
    $user = User::factory()->create();

    $spreadsheet = new Spreadsheet();
    $sheet = $spreadsheet->getActiveSheet();

    $sheet->fromArray([
        ['id', 'name', 'date']
    ], NULL, 'A1');

    for ($i = 1; $i <= 10; $i++) {
        $sheet->fromArray([
            [$i, 'User ' . $i, '12.05.2025']
        ], NULL, 'A' . ($i + 1));
    }

    $filename = 'test_upload.xlsx';
    $path = storage_path('app/testing/' . $filename);

    if (!file_exists(storage_path('app/testing'))) {
        mkdir(storage_path('app/testing'), 0777, true);
    }

    (new Xlsx($spreadsheet))->save($path);

    $file = new UploadedFile(
        $path,
        $filename,
        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        null,
        true
    );

    $response = $this
        ->actingAs($user)
        ->post('/api/upload-file', [
        'feed' => $file,
    ]);

    $response->assertStatus(200);
});

test('rejects upload of a file larger than 50MB or wrong format', function () {
    $user = User::factory()->create();

    // Создаём просто текстовый файл >50МБ
    $largeContent = str_repeat('A', 1024 * 1024 * 51); // 51MB
    $filename = 'huge_file.txt';
    $path = storage_path('app/testing/' . $filename);

    if (!file_exists(storage_path('app/testing'))) {
        mkdir(storage_path('app/testing'), 0777, true);
    }

    file_put_contents($path, $largeContent);

    $file = new UploadedFile(
        $path,
        $filename,
        'text/plain',
        null,
        true
    );

    $response = $this
        ->actingAs($user)
        ->post('/api/upload-file', [
            'feed' => $file,
        ]);

    $response->assertStatus(422);
    $response->assertJsonValidationErrors(['feed']);
});

test('rejects upload for guest user', function () {
    $response = $this->post('/api/upload-file');

    $response->assertStatus(401);
    $response->assertJson([
        'message' => 'Unauthorized',
    ]);
});
