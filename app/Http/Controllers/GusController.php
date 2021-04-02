<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\GusService;
use GusApi\Exception\InvalidUserKeyException;
use GusApi\Exception\NotFoundException;
use GusApi\GusApi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

class GusController extends Controller
{
    private $apiKey;
    private $gusService;

    public function __construct(GusService $gusService) {
        $this->apiKey = config('gus.api_key');
        $this->gusService = $gusService;
    }

    public function getDataByNipCode(Request $request)
    {
        //for development purposes use second arg
        $gus = new GusApi($this->apiKey, 'dev');
        //$gus = new GusApi($this->apiKey);

        $validator = Validator::make($request->all(), [
            'nip' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->getMessageBag()
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $nip = $request->nip;

        try {
            $gus->login();

            $data = $gus->getByNip($nip)[0];
            return response()->json(
                $this->gusService->getNipDataArray($data)
            );

        } catch (InvalidUserKeyException $e) {
            return response()->json([
                'nip' => $nip,
                'error' => 'Invalid API key.'
            ]);
        } catch (NotFoundException $e) {
            $error = $this
                ->gusService
                ->refactorResponseMessage($gus->getResultSearchMessage());

            return response()->json([
                'nip' => $nip,
                'error' => $error['KomunikatTresc']
            ]);
        }
    }
}
