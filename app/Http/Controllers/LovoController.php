<?php

namespace App\Http\Controllers;

use App\Lovo\HiperusActions;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class LovoController
 * @package App\Http\Controllers
 * @author Aleksandra Kowalewska <kowalewska@trui.pl>
 */
class LovoController extends Controller
{
    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function getClients(Request $request): JsonResponse
    {
        $clientId = $request->client_id;

        $validator = Validator::make($request->all(), [
            'client_id' => 'required'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'client_id' => $clientId,
                'errors' => $validator->getMessageBag()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        try {
            $response = HiperusActions::GetPSTNNumberList(
                $clientId
            );

            return response()->json([
                'client_id' => $clientId,
                'numbers' => array_column($response, 'extension')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'client_id' => $clientId,
                'error' => $e->getMessage()
            ], Response::HTTP_NOT_ACCEPTABLE);
        }
    }
}
