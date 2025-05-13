<?php

declare(strict_types=1);

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UploadFileRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'feed' => ['required','file', 'mimes:xlsx,xls', 'max:51200'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }

    public function messages(): array
    {
        return [
            'feed.required' => 'Пожалуйста, выберите файл для загрузки.',
            'feed.file' => 'Загружаемый объект должен быть файлом.',
            'feed.mimes' => 'Файл должен быть в формате .xlsx или .xls.',
            'feed.max' => 'Размер файла не должен превышать 50 мегабайт.',
        ];
    }
}
