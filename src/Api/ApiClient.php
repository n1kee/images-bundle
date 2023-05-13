<?php

namespace ImagesBundle\Api;

use Symfony\Component\HttpClient\HttpClient;

class ApiClient {

	protected string $url = "";

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
		$fullUrl = $this->url . $path;
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