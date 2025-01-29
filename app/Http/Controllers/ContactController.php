<?php 

namespace App\Http\Controllers;

use App\Models\Contacts;
use Illuminate\Http\Request;
use Propaganistas\LaravelPhone\PhoneNumber;
use App\Http\Requests\Contact\UpsertRequest;

class ContactController extends Controller
{
    protected $ID_OFFSET = 1000000000;
    /**
     * Store a newly created resource in storage.
     */
    public function upsert(UpsertRequest $request)
    {
        $data = $request->validated();
        $contactData = $this->formatContactData($data);

        $upserted = Contacts::upsert($contactData, 'odoo_id');

        if ($upserted) {
            return response()->json($this->findContactByVat($data['vat']));
        }

        return $this->upsertErrorResponse();
    }

    /**
     * Format the contact data for upsert.
     */
    private function formatContactData(array $data): array
    {
        $phone = $this->sanitizePhoneNumber($data['mobile']);

        return [
            'odoo_id' => $data['origin'] == 'gwmve' ? $data['id'] + $this->ID_OFFSET : $data['id'],
            'name' => $data['display_name'],
            'email' => $data['email'] ?? null,
            'vat' => $data['vat'],
            'country_code' => "+{$phone->getCountryCode()}",
            'phone' => $phone->getNationalNumber(),
        ];
    }

    /**
     * Sanitize the phone number by removing unwanted characters and converting it.
     */
    private function sanitizePhoneNumber(string $mobile)
    {
        $cleanedPhone = preg_replace('/[ \-\(\)]/', '', $mobile);
        return  (new PhoneNumber($cleanedPhone))->toLibPhoneObject();
    }

    /**
     * Find a contact by its VAT number.
     */
    private function findContactByVat(string $vat)
    {
        return Contacts::where('vat', $vat)->first();
    }

    /**
     * Return an error response for the upsert operation.
     */
    private function upsertErrorResponse()
    {
        return response()->json([
            'error' => 'Whoops, a problem occurred while upserting this contact'
        ], 400);
    }
}