<?php

namespace App\Http\Controllers;

use App\Models\Configuration;
use App\Models\Employer;
use App\Models\Payment;
use Carbon\Carbon;
use Illuminate\Support\Str;
use PDF;

class PaymentController extends Controller
{
    public function index ()
    {
        $defaultPayementDateQuery = Configuration::where('type', 'PAYEMENT_DATE')->first();
        $defaultPayementDate = $defaultPayementDateQuery->value;
        $convertedPayementDate = intval($defaultPayementDate);
        $today = intval(date('d'));

        $isPaymentDay = false;

        if ($today === $convertedPayementDate) {
            $isPaymentDay = true;
        }


        $payments = Payment::latest()->orderBy('id', 'desc')->paginate(10);
        return view('paiements.index', [
            'payments' => $payments,
            'isPaymentDay' => $isPaymentDay
        ]);
    }

    public function initPayment ()
    {
        // Verifier que nous somme à la date de paiement avant d'executer le ci-dessous
        $monthMapping = [
            'JANUARY' => 'JANVIER', 'FEBRUARY' => 'FEVRIER', 'MARCH' => 'MARS', 'APRIL' => 'AVRIL', 'MAY' => 'MAI', 'JUNE' => 'JUIN',
            'JULY' => 'JUILLET', 'AUGUST' => 'AOUT', 'SEPTEMBER' => 'SEPTEMBRE', 'OCTOBER' => 'OCTOBRE', 'NOVEMBER' => 'NOVEMBRE', 'DECEMBER' => 'DECEMBRE'
        ];

        $currentMonth = strtoupper(Carbon::now()->formatLocalized('%B'));
        
        // Mois en cours en français
        $currentMonthInFrench = $monthMapping[$currentMonth] ?? '';

        // Année en cours
        $currentYear = Carbon::now()->format('Y');

        // Similation des paiements pour les empoyer dans le mois en cours
        
        // Recuperation des employers qui n'ont pas été payé pour le mois en cours
        $employers = Employer::whereDoesntHave('payments', function ($query) use($currentMonthInFrench, $currentYear) {
            $query->where('month', $currentMonthInFrench)
                ->where('year', $currentYear);
        })->get();

        if ($employers->count() === 0) {
            return to_route('payment.index')->with('error_message', 'Tous vos employers ont été payé pour ce mois de '. $currentMonthInFrench .'.');
        }

        // Faire les paiements pour ces employers
        foreach ($employers as $employer) {
            $aEtePayer = $employer->payments()->where('month', $currentMonthInFrench)
            ->where('year', $currentYear)
            ->exists();

            if (!$aEtePayer) {
                $salaire = $employer->montant_journalier * 31;

                Payment::create([
                    'reference' => strtoupper(Str::random(10)),
                    'employer_id' => $employer->id,
                    'amount' => $salaire,
                    'launch_date' => now(),
                    'done_time' => now(),
                    'status' => 'SUCCESS',
                    'month' => $currentMonthInFrench,
                    'year' => $currentYear
                ]);

            }
        }
        
        return to_route('payment.index')->with('success_message', 'Paiement des employers effectué pour le mois de '. $currentMonthInFrench .'.');            
    }

    public function download (Payment $payment)
    {
        $fullPaymentInfo = Payment::with('employer')->find($payment->id);
        // Génere pdf
        // return view('paiements.facture', [
        //     'fullPaymentInfo' => $fullPaymentInfo,
        // ]);

        $pdf = PDF::loadView('paiements.facture', ['fullPaymentInfo' => $fullPaymentInfo]);
        return $pdf->download('facture_' . $fullPaymentInfo->employer->lastname . '.pdf');
    }
}
