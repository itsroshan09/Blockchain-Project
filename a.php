<?php
$contractAddress = 'your_smart_contract_address_here'; // After deployment

$client = new \GuzzleHttp\Client();
$apiKey = 'your_api_key_here'; // Replace with your Verbwire API key

$endpoint = "https://api.verbwire.com/v1/nft/contracts/$contractAddress"; // Hypothetical endpoint

try {
    $response = $client->request('GET', $endpoint, [
        'headers' => [
            'accept' => 'application/json',
            'X-API-Key' => $apiKey
        ]
    ]);

    // Parse the response body
    $responseBody = json_decode($response->getBody(), true);

    if (isset($responseBody['data'])) {
        $smart_contract_address = $responseBody['data']['contractAddress'] ?? 'N/A';
        $contract_abi = $responseBody['data']['contractAbi'] ?? 'N/A';

        echo "Smart Contract Address: $smart_contract_address \n";
        echo "Contract ABI: \n";
        print_r($contract_abi);
    } else {
        echo "No contract details found.\n";
    }
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}

?>
