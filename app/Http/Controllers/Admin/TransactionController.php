<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class TransactionController extends Controller
{
    public function index()
    {
        $transactions = Transaction::with('user','booking')
            ->latest()
            ->paginate(20);

        return view('admin.transactions.index', compact('transactions'));
    }

    public function show($id)
    {
        $transaction = Transaction::with('user','booking')
            ->findOrFail($id);

        return view('admin.transactions.show', compact('transaction'));
    }

    public function updateStatus(Request $request, $id)
    {
        $transaction = Transaction::findOrFail($id);

        $transaction->status = $request->status;

        if ($request->status == 'success') {
            $transaction->paid_at = now();
        }

        $transaction->save();

        return back()->with('success','Transaction updated');
    }

    public function destroy($id)
    {
        Transaction::findOrFail($id)->delete();

        return back()->with('success','Deleted successfully');
    }

    public function invoice($id)
{
    $transaction = Transaction::with('user','booking')
        ->findOrFail($id);

    $pdf = Pdf::loadView('admin.transactions.invoice', compact('transaction'));

    return $pdf->download('invoice-'.$transaction->transaction_id.'.pdf');
}
}