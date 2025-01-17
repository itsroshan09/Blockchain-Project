<?php
require_once('vendor/autoload.php');

use GuzzleHttp\Client;

$client = new Client();

try {
    // Sending POST request to deploy contract
    $response = $client->request('POST', 'https://api.verbwire.com/v1/nft/deploy/deployContract', [
        'multipart' => [
            [
                'name' => 'chain',
                'contents' => 'mumbai' // Specify the testnet chain (e.g., Polygon Mumbai)
            ],
            [
                'name' => 'contractType',
                'contents' => 'nft721' // Contract type (e.g., ERC-721 NFT)
            ],
            [
                'name' => 'contractCategory',
                'contents' => 'simple' // Category of the contract
            ],
            [
                'name' => 'isCollectionContract',
                'contents' => 'true' // Whether this is a collection contract
            ],
            [
                'name' => 'contractName',
                'contents' => 'blockchain1' // The name of the contract
            ],
            [
                'name' => 'contractSymbol',
                'contents' => 'blockchain1' // The symbol for the contract
            ]
        ],
        'headers' => [
            'accept' => 'application/json', // Accept JSON responses
            'X-API-Key' => 'pk_live_29bd0d18-b620-4a86-8b17-14ecaa6c445c' // Replace with your Verbwire API key
        ],
    ]);

    // Parse the response
    $responseBody = json_decode($response->getBody(), true);

    // Check if the deployment was successful
    if (isset($responseBody['status']) && $responseBody['status'] === 'success') {
        // Capture the smart contract address and ABI
        $smart_contract_address = $responseBody['data']['contractAddress'] ?? 'N/A';
        $contract_abi = $responseBody['data']['contractAbi'] ?? 'N/A';

        echo "Contract deployed successfully! \n";
        echo "Smart Contract Address: $smart_contract_address \n";
        echo "Contract ABI: \n";
        print_r($contract_abi);
    } elseif (isset($responseBody['transaction_details'])) {
        // If deployment failed, display transaction details
        echo "Failed to deploy contract. Response: \n";
        print_r($responseBody);

        $transactionID = $responseBody['transaction_details']['transactionID'] ?? 'N/A';
        $blockExplorer = $responseBody['transaction_details']['blockExplorer'] ?? 'N/A';

        echo "\nTransaction Details:\n";
        echo "Transaction ID: $transactionID\n";
        echo "Block Explorer: $blockExplorer\n";

        // If the blockExplorer is still undefined, it might mean the transaction is still processing
        if ($blockExplorer === 'N/A' || strpos($blockExplorer, 'undefined') !== false) {
            echo "The block explorer URL seems to be incomplete or undefined. Please check the status of your transaction later.\n";
        } else {
            echo "Check the transaction status here: $blockExplorer\n";
        }
    } else {
        echo "Unexpected response format. Please verify the API response structure.\n";
    }
} catch (\GuzzleHttp\Exception\ClientException $e) {
    // Handle client exceptions (4xx errors)
    echo "Client Exception: " . $e->getResponse()->getBody()->getContents();
} catch (\GuzzleHttp\Exception\ServerException $e) {
    // Handle server exceptions (5xx errors)
    echo "Server Exception: " . $e->getResponse()->getBody()->getContents();
} catch (\Exception $e) {
    // Handle other exceptions
    echo "Unexpected Error: " . $e->getMessage();
}
?>
