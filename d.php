<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loan Application on Sepolia</title>
    <script src="https://cdn.jsdelivr.net/npm/web3@1.8.1/dist/web3.min.js"></script>
</head>
<body>
    <h1>Loan Application on Sepolia Testnet</h1>
    <form id="loanForm">
        <label for="loan_amount">Loan Amount (ETH):</label>
        <input type="number" id="loan_amount" name="loan_amount" required><br>

        <label for="loan_term">Loan Term (Months):</label>
        <input type="number" id="loan_term" name="loan_term" required><br>

        <label for="loan_purpose">Loan Purpose:</label>
        <input type="text" id="loan_purpose" name="loan_purpose" required><br>

        <label for="account">Account Number:</label>
        <input type="text" id="account" name="account" required><br>

        <button type="submit">Apply</button>
    </form>

    <script>
        async function handleTransaction() {
            try {
                const loanAmount = document.getElementById("loan_amount").value;
                const loanTerm = document.getElementById("loan_term").value;
                const loanPurpose = document.getElementById("loan_purpose").value;

                if (typeof window.ethereum === "undefined") {
                    alert("MetaMask is not installed. Please install it and try again.");
                    return;
                }

                const web3 = new Web3(window.ethereum);
                await window.ethereum.request({ method: 'eth_requestAccounts' });

                const accounts = await web3.eth.getAccounts();
                if (accounts.length === 0) {
                    alert("No accounts found. Please connect to MetaMask.");
                    return;
                }

                const userAccount = accounts[0];

                const networkId = await web3.eth.net.getId();
                if (networkId !== 11155111) { // Sepolia Testnet ID
                    alert("Please switch to the Sepolia Testnet.");
                    return;
                }

                const loanId = "LOAN" + Math.floor(1000 + Math.random() * 9000);

                const contractAddress = "0xb8F630E007F1867Ed52e87Fb4fFD4968E0a151E1"; // Replace with your contract address
                const contractABI = [
                    {
                        "inputs": [
                            { "internalType": "string", "name": "loanId", "type": "string" },
                            { "internalType": "uint256", "name": "amount", "type": "uint256" },
                            { "internalType": "uint256", "name": "term", "type": "uint256" },
                            { "internalType": "string", "name": "purpose", "type": "string" }
                        ],
                        "name": "applyForLoan",
                        "outputs": [],
                        "stateMutability": "nonpayable",
                        "type": "function"
                    }
                ];

                const contract = new web3.eth.Contract(contractABI, contractAddress);

                const confirmation = window.confirm(`You are about to apply for a loan of ${loanAmount} ETH for ${loanTerm} months with purpose: ${loanPurpose}. Proceed?`);
                if (!confirmation) return;

                const transaction = await contract.methods
                    .applyForLoan(loanId, web3.utils.toWei(loanAmount, "ether"), loanTerm, loanPurpose)
                    .send({ from: userAccount });

                alert("Loan Application Successful! Transaction Hash: " + transaction.transactionHash);
            } catch (error) {
                if (error.message.includes("insufficient funds")) {
                    alert("Insufficient funds to cover network fees.");
                } else {
                    alert("An error occurred: " + error.message);
                }
            }
        }

        document.getElementById("loanForm").addEventListener("submit", async (e) => {
            e.preventDefault();
            await handleTransaction();
        });
    </script>
</body>
</html>
