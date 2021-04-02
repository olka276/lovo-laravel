<?php declare(strict_types=1);

namespace App\Http\Controllers;

use App\Services\GusService;
use GusApi\Exception\InvalidUserKeyException;
use GusApi\Exception\NotFoundException;
use GusApi\GusApi;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpFoundation\Response;

/**
 * Class GusController
 * @package App\Http\Controllers
 */
class GusController extends Controller
{
    /**
     * @var int
     */
    private $apiKey;

    /**
     * @var GusService
     */
    private $gusService;

    /**
     * GusController constructor.
     * @param GusService $gusService
     */
    public function __construct(GusService $gusService) {
        $this->apiKey = config('gus.api_key');
        $this->gusService = $gusService;
    }

    /**
     * Gets Company data by NIP code in Request
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getDataByNipCode(Request $request): JsonResponse
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
                'error' => 'Nieprawidłowy klucz API'
            ]);

        } catch (NotFoundException $e) {
            $error = $this
                ->gusService
                ->getErrorMessage($gus->getResultSearchMessage());

            return response()->json([
                'nip' => $nip,
                'error' => $this
                    ->gusService
                    ->handleErrorCodes($error)
            ]);
        }
    }
}
