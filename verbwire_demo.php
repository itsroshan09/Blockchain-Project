<?php
require 'vendor/autoload.php'; // Guzzle dependency

use GuzzleHttp\Client;

// Replace with your actual Verbwire API Key
$apiKey = 'pk_live_29bd0d18-b620-4a86-8b17-14ecaa6c445c'; 

// Function to deploy a smart contract (free version using testnet)
function deployContract($client, $apiKey) {
    try {
        $response = $client->post('https://api.verbwire.com/v1/nft/deploy/deployContract', [
            'headers' => [
                'X-API-Key' => $apiKey, // Correct header for Verbwire API
                'Content-Type' => 'application/json',
            ],
            'json' => [
                'chain' => 'mumbai', // Use a testnet (e.g., Polygon Mumbai Testnet)
                'contractType' => 'nft721',
                'contractCategory' => 'simple',
                'isCollectionContract' => "true" // **This is a boolean value, which will trigger the error**
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        return $data;
    } catch (\GuzzleHttp\Exception\ClientException $e) {
        $errorResponse = $e->getResponse()->getBody()->getContents();
        return ['error' => 'Client Exception: ' . $errorResponse];
    } catch (\GuzzleHttp\Exception\ServerException $e) {
        $errorResponse = $e->getResponse()->getBody()->getContents();
        return ['error' => 'Server Exception: ' . $errorResponse];
    } catch (\Exception $e) {
        return ['error' => 'Unexpected Error: ' . $e->getMessage()];
    }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verbwire Smart Contract Deployment (Free)</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="text-center">Verbwire Smart Contract Deployment (Testnet)</h1>
        <hr>
        <div class="text-center">
            <form method="post" action="">
                <button type="submit" name="deploy" class="btn btn-primary">Deploy Smart Contract</button>
            </form>
        </div>
        <div class="mt-5">
            <?php
            if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['deploy'])) {
                $client = new Client();
                $result = deployContract($client, $apiKey);

                if (isset($result['error'])) {
                    echo "<div class='alert alert-danger'>Error: " . htmlspecialchars($result['error']) . "</div>";
                } else {
                    echo "<div class='alert alert-success'>Smart Contract Deployed Successfully!</div>";
                    echo "<h5>Response:</h5><pre>" . htmlspecialchars(print_r($result, true)) . "</pre>";
                }
            }
            ?>
        </div>
    </div>
</body>
</html>
