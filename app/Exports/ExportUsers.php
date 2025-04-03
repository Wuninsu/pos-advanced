<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ExportUsers implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return User::all();
    }

    public function map($user): array
    {
        return [
            $user->id,
            $user->username,
            $user->name,
            $user->email,
            $user->phone,
            $user->role,
            $user->created_at
        ];
    }

    public function headings(): array
    {
        return [
            "#",
            "Username",
            "Name",
            "Email",
            "Phone",
            "Role",
            "Created At"
        ];
    }
}
