<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Beneficiary;
use App\Models\BeneficiaryChangeRequest;
use App\Models\AuditLog;
use Illuminate\Support\Facades\DB;

class BeneficiaryService
{
    public function requestChange(Member $member, array $beneficiaries): BeneficiaryChangeRequest
    {
        $this->validatePercentages($beneficiaries);

        return BeneficiaryChangeRequest::create([
            'member_id' => $member->id,
            'payload' => $beneficiaries,
            'status' => 'pending',
        ]);
    }

    public function approveChange(BeneficiaryChangeRequest $request, int $actorId): void
    {
        if ($request->status !== 'pending') {
            throw new \Exception('Request already processed.');
        }

        DB::transaction(function () use ($request, $actorId) {
            Beneficiary::where('member_id', $request->member_id)->delete();

            foreach ($request->payload as $data) {
                Beneficiary::create([
                    'member_id' => $request->member_id,
                    ...$data,
                ]);
            }

            $request->update(['status' => 'approved']);

            AuditLog::create([
                'user_id' => $actorId,
                'action' => 'beneficiaries_updated',
                'model' => Beneficiary::class,
                'model_id' => $request->member_id,
                'new_values' => $request->payload,
            ]);
        });
    }

    protected function validatePercentages(array $beneficiaries): void
    {
        $total = collect($beneficiaries)->sum('percentage');

        if ($total !== 100) {
            throw new \Exception('Beneficiary percentages must total 100.');
        }
    }
}
