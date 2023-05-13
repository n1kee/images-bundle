<?php

namespace ImagesBundle\Api;

use Symfony\Component\HttpClient\HttpClient;

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
		return static::$url;
	}

	protected static function makeRequest(string $path, string $method, $params) {
		$path = $path ?? "";
		$method = $method ?? "GET";
		$fullUrl = static::$url . $path;
        $response = HttpClient::create()
        	->request($method, $fullUrl, $params);

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