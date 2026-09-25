<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeItem extends Model
{
    use HasFactory;

    protected $table = 'qr_codes';

    protected $fillable = [
        'code',
        'title',
        'text_content',
        'media_type',
        'media_path',
        'qr_image',
        'views_count',
        'status',
        'created_by',
    ];

    protected $appends = [
        'public_url',
    ];

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getPublicUrlAttribute(): string
    {
        return route('qr.public.show', ['code' => $this->code]);
    }

    public function getMediaUrlAttribute(): ?string
    {
        if (empty($this->media_path)) {
            return null;
        }

        if (filter_var($this->media_path, FILTER_VALIDATE_URL)) {
            return $this->media_path;
        }

        return url($this->media_path);
    }

    public function isImage(): bool
    {
        return $this->media_type === 'image' && !empty($this->media_path);
    }

    public function isVideo(): bool
    {
        return $this->media_type === 'video' && !empty($this->media_path);
    }

    public function hasMedia(): bool
    {
        return in_array($this->media_type, ['image', 'video']) && !empty($this->media_path);
    }

    public function generateQrSvg(int $size = 250): string
    {
        return self::generateSvgWithLogo($this->public_url, $size);
    }

    public static function generateSvgWithLogo(string $url, int $size = 400): string
    {
        $svg = (string) QrCode::size($size)
            ->errorCorrection('H')
            ->margin(1)
            ->color(18, 24, 76)
            ->backgroundColor(255, 255, 255)
            ->generate($url);

        $logoPath = public_path('backend/assets/images/logo-icon.png');
        if (!file_exists($logoPath)) {
            $logoPath = public_path('assets/images/1024.png');
        }
        if (!file_exists($logoPath)) {
            $logoPath = public_path('assets/images/logo_tahadi.png');
        }

        if (file_exists($logoPath)) {
            $logoData = base64_encode(file_get_contents($logoPath));
            $logoMime = mime_content_type($logoPath) ?: 'image/png';
            $logoSrc = "data:{$logoMime};base64,{$logoData}";

            $logoSize = (int) ($size * 0.22);
            $logoPos = (int) (($size - $logoSize) / 2);
            $padding = 4;
            $boxSize = $logoSize + ($padding * 2);
            $boxPos = (int) (($size - $boxSize) / 2);
            $radius = (int) ($boxSize * 0.22);

            $logoSvg = "
  <g class=\"qr-logo-center\">
    <rect x=\"{$boxPos}\" y=\"{$boxPos}\" width=\"{$boxSize}\" height=\"{$boxSize}\" rx=\"{$radius}\" ry=\"{$radius}\" fill=\"#ffffff\" stroke=\"#8b5cf6\" stroke-width=\"2\" />
    <image href=\"{$logoSrc}\" x=\"{$logoPos}\" y=\"{$logoPos}\" width=\"{$logoSize}\" height=\"{$logoSize}\" preserveAspectRatio=\"xMidYMid meet\" />
  </g>
</svg>";

            $svg = str_replace('</svg>', $logoSvg, $svg);
        }

        return $svg;
    }
}
