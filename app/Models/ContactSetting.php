<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Single-row settings shown on the public Contact page (managed in admin).
 */
class ContactSetting extends Model
{
    protected $fillable = [
        'email',
        'phone',
        'address',
        'hours',
    ];

    public static function current(): self
    {
        $row = self::query()->first();
        if ($row !== null) {
            return $row;
        }

        return self::query()->create([
            'email' => null,
            'phone' => null,
            'address' => null,
            'hours' => 'Monday–Friday, 9:00 a.m.–5:00 p.m. (local time)',
        ]);
    }

    /**
     * @return array{email: string|null, phone: string|null, address: string|null, hours: string|null}
     */
    public function toPublicArray(): array
    {
        return [
            'email' => $this->email !== '' ? $this->email : null,
            'phone' => $this->phone !== '' ? $this->phone : null,
            'address' => $this->address !== '' ? $this->address : null,
            'hours' => $this->hours !== '' ? $this->hours : null,
        ];
    }
}
