<?php

namespace ImagesBundle\Api;

use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\HttpClient;

class ApiClient {

	protected static string $url = "";

	static function get(string $path, $params) {
		return self::makeRequest($path, "GET", $params);
	}

	static function post(string $path, $params) {
		return self::makeRequest($path, "POST", $params);
	}

	static function update(string $path, $params) {
		return self::makeRequest($path, "UPDATE", $params);
	}

	static function delete(string $path, $params) {
		return self::makeRequest($path, "DELETE", $params);
	}

	static function getUrl(): string {
		return self::$url;
	}

	protected static function makeRequest(string $path, string $method, $params) {
		$path = $path ?? "";
		$method = $method ?? "GET";
		$fullUrl = self::$url . $path . substr($colorHex, 1);
        $response = (new HttpClient)->request($method, $fullUrl);

        $statusCode = $response->getStatusCode();

        $contentType = $response->getHeaders()['content-type'][0];

        if (floor($statusCode / 200) === 2) {
        	if ($contentType === "application/json") {
        		return $response->toArray();
        	}
        	return $response->toText();
        }

        return null;
	}
}