<?php
 
namespace App\Http\Controllers;
 
use Illuminate\Http\Request;
use Chapa\Chapa\Facades\Chapa as Chapa;

use App\Models\Payment;
use Illuminate\Support\Facades\Validator;


 
class PaymentController extends Controller
{
    /**
     * Initialize Rave payment process
     * @return void
     */
    protected $reference;
 
    public function __construct(){
        $this->reference = Chapa::generateReference();
 
    }
    public function store(Request $request)
    {
        //This generates a payment reference
        $reference = $this->reference;
        
 
         $validator = Validator::make($request->all(), [
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'email' => 'required|email',
            'amount' => 'required|numeric|min:1|max:10000' // Updated validation rule
        ]);

        if($validator->fails())
        {
            return redirect('/pay')->withErrors($validator)->withInput();   //tobe chanched later
        }
        // Enter the details of the payment
        $data = [
            
            'amount' => $request->amount,
            'email' => $request->email,
            'tx_ref' => $reference,
            'currency' => "ETB",
            'callback_url' => route('callback',[$reference]),
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            "customization" => [
                "title" => 'Test',
                "description" => "testing"
            ]
        ];

        $payment = Chapa::initializePayment($data);

$pay_ment = Payment::create([
    'first_name' => $request->first_name,
    'last_name' => $request->last_name,
    'email' => $request->email,
    'tx_ref' => $reference,
    'amount' => $request->amount,
    'status' => ($payment['status'] === 'success') ? 'success' : 'fail'
]);

// Check payment status
if ($payment['status'] !== 'success') {
    \Log::error('Payment Status:', ['status' => $payment['status']]);
    return redirect('/')->with('error', 'Sorry, payment was not successful');
}

return redirect($payment['data']['checkout_url']);
    }
 
    /**
     * Obtain Rave callback information
     * @return void
     */
    public function callback($reference)
{
    $data = Chapa::verifyTransaction($reference);

    // If payment is successful
    if ($data['status'] == 'success') {
        $payment = Payment::where('tx_ref', $reference)->first();

        if ($payment) {
            $payment->update(['status' => 'success']); // Update the status to 'success'
        } else {
            \Log::error('Payment record not found for this reference ' . $reference);
        }
        
        $message = "Payment is Sucessful";
        return redirect('/')->with('success', $message);
    } else {
        $message = "Payment is not Sucessful";
        return redirect('/')->with('error', $message);
    }
}
    public function getReference()
{
    return $this->reference;
}
}