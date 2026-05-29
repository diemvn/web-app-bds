<?php

namespace App\Filament\Pages;

use App\Models\SystemSetting;
use BackedEnum;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class SystemSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    protected static ?string $navigationLabel = 'Cài đặt';

    protected static ?string $title = 'Cài đặt hệ thống';

    protected static ?int $navigationSort = 99;

    public ?array $data = [];

    public function mount(): void
    {
        $this->data = [
            'app_name' => SystemSetting::get('app_name', 'Quản lý phòng trọ'),
            'primary_color' => SystemSetting::get('primary_color', '#FF5A5F'),
            'tagline' => SystemSetting::get('tagline', ''),
            'logo_path' => SystemSetting::get('logo_path'),
            'meta_description_default' => SystemSetting::get('meta_description_default', ''),
            'google_site_verification' => SystemSetting::get('google_site_verification', ''),
        ];
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Hệ thống';
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Thương hiệu')->description('Logo, tên và màu hiển thị trên admin & portal')->schema([
                    TextInput::make('app_name')->label('Tên hệ thống')->required(),
                    TextInput::make('tagline')->label('Slogan'),
                    ColorPicker::make('primary_color')->label('Màu chủ đạo'),
                    FileUpload::make('logo_path')->label('Logo')->image()->directory('branding')->disk('public'),
                ]),
                Section::make('SEO & Google')->description('Meta mặc định và xác minh Google Search Console')->schema([
                    Textarea::make('meta_description_default')
                        ->label('Meta description mặc định')
                        ->rows(3)
                        ->maxLength(160)
                        ->helperText('Tối đa ~160 ký tự. Dùng cho trang chủ và trang không có mô tả riêng.'),
                    TextInput::make('google_site_verification')
                        ->label('Google site verification')
                        ->helperText('Mã content từ thẻ meta google-site-verification trong Search Console.'),
                ]),
            ]);
    }

    public function save(): void
    {
        $data = $this->data;
        if (array_key_exists('logo_path', $data)) {
            $data['logo_path'] = SystemSetting::normalizeLogoPath($data['logo_path']);
        }

        foreach ($data as $key => $value) {
            SystemSetting::set($key, $value);
        }

        Notification::make()->title('Đã lưu cài đặt')->success()->send();
    }

    protected string $view = 'filament.pages.system-settings';
}
