<?php

namespace App\Http\Controllers\Central;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class InvoicesController extends Controller
{
    /**
     * Liste des factures
     */
    public function index(): View
    {
        $invoices = Invoice::with(['tenant', 'payments'])
            ->latest()
            ->paginate(15);

        return view('central.invoices.index', compact('invoices'));
    }

    /**
     * Formulaire de création de facture
     */
    public function create(): View
    {
        $tenants = Tenant::orderBy('name')->get();
        return view('central.invoices.create', compact('tenants'));
    }

    /**
     * Enregistrement d'une nouvelle facture
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'amount_ht' => 'required|numeric|min:0',
            'tax_rate'  => 'required|numeric|min:0|max:100', // Taux en % (ex: 18)
            'currency'  => 'required|string|max:10',
            'due_date'  => 'required|date',
        ]);

        $amountHt       = (float) $validated['amount_ht'];
        $taxRatePercent = (float) $validated['tax_rate']; // 18.00

        // CALCULS
        $taxAmount = $amountHt * ($taxRatePercent / 100); // 500 000 * 0.18 = 90 000 XOF
        $amountTtc = $amountHt + $taxAmount;              // 500 000 + 90 000 = 590 000 XOF

        $invoice = Invoice::create([
            'number'     => 'INV-' . date('Ymd') . '-' . rand(100, 999),
            'tenant_id'  => $validated['tenant_id'],
            'amount_ht'  => $amountHt,
            'tax_rate'   => $taxRatePercent, // Enregistre 18.00
            'tax_amount' => $taxAmount,      // Enregistre 90 000.00
            'amount_ttc' => $amountTtc,      // Enregistre 590 000.00
            'currency'   => $validated['currency'],
            'status'     => 'pending',
            'due_date'   => $validated['due_date'],
        ]);

        return redirect()->route('invoices.index')
            ->with('success', 'La facture a été créée avec succès.');
    }

    /**
     * Affichage détaillé de la facture
     */
    public function show(Invoice $invoice): View
    {
        $invoice->load(['tenant', 'payments']);
        return view('central.invoices.show', compact('invoice'));
    }

    /**
     * Annuler / Marquer une facture comme impayée
     */
    public function destroy(Invoice $invoice): RedirectResponse
    {
        $invoice->update(['status' => 'canceled']);

        return redirect()->route('invoices.index')
            ->with('success', 'La facture a été annulée.');
    }
}