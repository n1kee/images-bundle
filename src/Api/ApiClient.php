<?php

namespace ImagesBundle\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use App\Storage\RequestHeadersStorage;
use ImagesBundle\Api\ApiResponse;

class ApiClient {

    function __construct(
    	private HttpClientInterface $client,
        private RequestHeadersStorage $reqHeadersStorage,
    ) {
    }

	protected string $url = "";

    function getRequestParams(array $params = []) {
        $storedHeaders = $this->reqHeadersStorage
            ->getRandomFile()
            ->readJson();

        $storedHeaders["host"] = parse_url($this->url)["host"];

        return array_merge([
        	"headers" => $storedHeaders,
        ], $params);
    }

	function get(string $path, $params) {
		return $this->makeRequest($path, "GET", $params);
	}

	function post(string $path, $params) {
		return $this->makeRequest($path, "POST", $params);
	}

	function update(string $path, $params) {
		return $this->makeRequest($path, "UPDATE", $params);
	}

	function delete(string $path, $params) {
		return $this->makeRequest($path, "DELETE", $params);
	}

	function getUrl(): string {
		return $this->url;
	}

	protected function makeRequest(string $path, string $method, $params) {
		$path = $path ?? "";
		$method = $method ?? "GET";
		$params = $this->getRequestParams($params);
		$fullUrl = $this->url . $path;

        try {
            try {

                $response = $this->client
                    ->request($method, $fullUrl, $params);

            } catch (Exception $ex) {
                return new ApiResponse(null, false, $ex);
            }
        } catch (Error $ex) {
            return new ApiResponse(null, false, $ex);
        }

        $statusCode = $response->getStatusCode();

        $contentType = $response->getHeaders()['content-type'][0];

        if (floor($statusCode / 100) == 2) {
        	if ($contentType === "application/json") {
        		return new ApiResponse($response->toArray());
        	}
            $parsedResponse = json_decode($response->getContent(), true);
        	return new ApiResponse($parsedResponse);
        }

        return new ApiResponse(null, false);
	}
}