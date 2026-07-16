<?php

namespace App\Traits;

use App\Constants\Status;

trait BuyerNotify
{
    public static function notifyToBuyer()
    {
        return [
            'allBuyers'               => 'All Buyers',
            'selectedBuyers'          => 'Selected Buyers',
            'kycUnverified'            => 'Kyc Unverified Buyers',
            'kycVerified'              => 'Kyc Verified Buyers',
            'kycPending'               => 'Kyc Pending Buyers',
            'withBalance'              => 'With Balance Buyers',
            'emptyBalanceBuyers'      => 'Empty Balance Buyers',
            'twoFaDisableBuyers'      => '2FA Disable Buyer',
            'twoFaEnableBuyers'       => '2FA Enable Buyer',
            'hasDepositedBuyers'      => 'Deposited Buyers',
            'notDepositedBuyers'      => 'Not Deposited Buyers',
            'pendingDepositedBuyers'  => 'Pending Deposited Buyers',
            'rejectedDepositedBuyers' => 'Rejected Deposited Buyers',
            'topDepositedBuyers'      => 'Top Deposited Buyers',
            'hasWithdrawBuyers'       => 'Withdraw Buyers',
            'pendingWithdrawBuyers'   => 'Pending Withdraw Buyers',
            'rejectedWithdrawBuyers'  => 'Rejected Withdraw Buyers',
            'pendingTicketBuyer'      => 'Pending Ticket Buyers',
            'answerTicketBuyer'       => 'Answer Ticket Buyers',
            'closedTicketBuyer'       => 'Closed Ticket Buyers',
            'notLoginBuyers'          => 'Last Few Days Not Login Buyers',

            'jobPublishedBuyers'      => 'Having Published Jobs Buyers',
            'jobDraftedBuyers'        => 'Having Drafted Jobs Buyers',
            'notJobBuyers'            => 'Not Jobs Buyers',
            'runningProjectBuyers'    => 'Running Projects Buyers',
            'reportedProjectBuyers'   => 'Reported Projects Buyers',
            'reviewingProjectBuyers'  => 'Reviewing Projects Buyers',
            'completedProjectBuyers'  => 'Completed Projects Buyers',
            'rejectedProjectBuyers'   => 'Rejected Projects Buyers',
        ];
    }

    public function scopeSelectedBuyers($query)
    {
        return $query->whereIn('id', request()->buyer ?? []);
    }

    public function scopeAllBuyers($query)
    {
        return $query;
    }

    public function scopeEmptyBalanceBuyers($query)
    {
        return $query->where('balance', '<=', 0);
    }

    public function scopeTwoFaDisableBuyers($query)
    {
        return $query->where('ts', Status::DISABLE);
    }

    public function scopeTwoFaEnableBuyers($query)
    {
        return $query->where('ts', Status::ENABLE);
    }

    public function scopeHasDepositedBuyers($query)
    {
        return $query->whereHas('deposits', function ($deposit) {
            $deposit->successful();
        });
    }

    public function scopeNotDepositedBuyers($query)
    {
        return $query->whereDoesntHave('deposits', function ($q) {
            $q->successful();
        });
    }

    public function scopePendingDepositedBuyers($query)
    {
        return $query->whereHas('deposits', function ($deposit) {
            $deposit->pending();
        });
    }

    public function scopeRejectedDepositedBuyers($query)
    {
        return $query->whereHas('deposits', function ($deposit) {
            $deposit->rejected();
        });
    }

    public function scopeTopDepositedBuyers($query)
    {
        return $query->whereHas('deposits', function ($deposit) {
            $deposit->successful();
        })->withSum(['deposits' => function ($q) {
            $q->successful();
        }], 'amount')->orderBy('deposits_sum_amount', 'desc')->take(request()->number_of_top_deposited_user ?? 10);
    }

    public function scopeHasWithdrawBuyers($query)
    {
        return $query->whereHas('withdrawals', function ($q) {
            $q->approved();
        });
    }

    public function scopePendingWithdrawBuyers($query)
    {
        return $query->whereHas('withdrawals', function ($q) {
            $q->pending();
        });
    }

    public function scopeRejectedWithdrawBuyers($query)
    {
        return $query->whereHas('withdrawals', function ($q) {
            $q->rejected();
        });
    }

    public function scopePendingTicketBuyer($query)
    {
        return $query->whereHas('tickets', function ($q) {
            $q->whereIn('status', [Status::TICKET_OPEN, Status::TICKET_REPLY]);
        });
    }

    public function scopeClosedTicketBuyer($query)
    {
        return $query->whereHas('tickets', function ($q) {
            $q->where('status', Status::TICKET_CLOSE);
        });
    }

    public function scopeAnswerTicketBuyer($query)
    {
        return $query->whereHas('tickets', function ($q) {

            $q->where('status', Status::TICKET_ANSWER);
        });
    }

    public function scopeNotLoginBuyers($query)
    {
        return $query->whereDoesntHave('loginLogs', function ($q) {
            $q->whereDate('created_at', '>=', now()->subDays(request()->number_of_days ?? 10));
        });
    }

    public function scopeKycVerified($query)
    {
        return $query->where('kv', Status::KYC_VERIFIED);
    }

    public function scopeJobApprovedBuyers($query)
    {
        return $query->whereHas('jobs', function ($q) {
            $q->approved();
        });
    }

    public function scopeJobPublishedBuyers($query)
    {
        return $query->whereHas('jobs', function ($q) {
            $q->published();
        });
    }
    public function scopeJobDraftedBuyers($query)
    {
        return $query->whereHas('jobs', function ($q) {
            $q->drafted();
        });
    }

    public function scopeNotJobBuyers($query)
    {
        return $query->whereDoesntHave('jobs');
    }
    public function scopeRunningProjectBuyers($query)
    {
        return $query->whereHas('projects', function ($q) {
            $q->running();
        });
    }
    public function scopeReportedProjectBuyers($query)
    {
        return $query->whereHas('projects', function ($q) {
            $q->reported();
        });
    }
    public function scopeCompletedProjectBuyers($query)
    {
        return $query->whereHas('projects', function ($q) {
            $q->completed();
        });
    }
    public function scopeReviewingProjectBuyers($query)
    {
        return $query->whereHas('projects', function ($q) {
            $q->reviewing();
        });
    }
    public function scopeRejectedProjectBuyers($query)
    {
        return $query->whereHas('projects', function ($q) {
            $q->rejected();
        });
    }
}
