<?php

declare(strict_types=1);

namespace App\Http\Integrations\Kodik\Requests;

use App\Values\KodikMaterialsData as DTO;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Http\Response;

class GetMaterialsRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/list';
    }

    public function createDtoFromResponse(Response $response): DTO
    {
        return DTO::fromSaloonResponse($response);
    }
}
