<?php

namespace MatDave\MODXPackage\Traits;

trait Curl
{
    protected function curl($path, $method = 'GET', $params = [], $headers = [])
    {
        if (empty($this->api)) {
            return json_encode(['error' => 'No endpoint is set.']);
        }

        $headers = array_merge($headers, [
            'Content-Type: application/json',
        ]);

        $ch = curl_init($this->api . $path);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, 1);
        }
        if (!empty($params)) {
            switch ($method) {
                case 'POST':
                case 'PUT':
                case 'PATCH':
                case 'DELETE':
                    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
                    break;
                case 'GET':
                    curl_setopt($ch, CURLOPT_URL, $this->api . $path . '?' . http_build_query($params));
                    break;
            }
        }
        $result = curl_exec($ch);
        $statusCode = (int)curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($statusCode < 200 || $statusCode >= 300) {
            return json_encode(['statusCode' => $statusCode, 'error'=>json_decode($result)]);
        }

        if (empty($result)) {
            return '[]';
        }

        return $result;
    }
}