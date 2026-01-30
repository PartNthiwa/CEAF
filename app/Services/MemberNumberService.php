<?php

use Illuminate\Support\Facades\DB;
use App\Models\Member;

class MemberNumberService
{
    public static function generate(): string
    {
        return DB::transaction(function () {
            $last = Member::whereNotNull('member_number')
                ->orderBy('id', 'desc')
                ->lockForUpdate()
                ->first();

            $next = 1;

            if ($last && preg_match('/CEAF-(\d+)/', $last->member_number, $m)) {
                $next = ((int)$m[1]) + 1;
            }

            return 'CEAF-' . str_pad((string)$next, 7, '0', STR_PAD_LEFT);
        });
    }
}
