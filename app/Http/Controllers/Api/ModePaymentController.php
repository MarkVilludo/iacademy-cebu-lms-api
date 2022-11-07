<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PaymentMode;
use App\Http\Resources\ModePaymentResource;
use Illuminate\Http\Request;

class ModePaymentController extends Controller
{
    public function __construct(PaymentMode $paymentMode)
    {
        $this->paymentMode = $paymentMode;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        //
        $searchField = $request->search_field;
        $searchData = $request->search_data;
        $orderBy = $request->order_by;

        $paginateCount = 10;
        if ($request->count_content) {
            $paginateCount = $request->count_content;
        }

        $applications = $this->paymentMode->filterByField($searchField, $searchData)
                                ->orderByField($searchField, $orderBy)
                                ->where('is_active', 1)
                                ->paginate($paginateCount);

        if ($applications) {
            return ModePaymentResource::collection($applications);
            $data['message'] = 'Shows mode of payment available.';
        } else {
            $data['message'] = 'No mode of payment available';
        }

        return response()->json($data, 200);
    }
}
