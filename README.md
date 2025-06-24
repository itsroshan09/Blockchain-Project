# 🔗 Simple Blockchain in Python

A basic implementation of a blockchain written in Python, built for educational purposes. This project demonstrates how blockchain technology works under the hood—using concepts like cryptographic hashing, proof of work, and chain validation.

---

## 🧠 What is This Project?

This project simulates a **mini blockchain** system where each block:
- Contains data, a timestamp, its hash, and the hash of the previous block
- Is secured using **SHA-256 cryptographic hash**
- Is mined using a simple **Proof-of-Work** mechanism
- Forms a tamper-proof **linked chain**

It’s ideal for understanding **core blockchain principles** without using any third-party libraries or frameworks.

---

## 🧰 Tech Stack

- **Language:** Python 3
- **Concepts:** Object-Oriented Programming, SHA-256 Hashing, Proof of Work, Chain Validation

---

## 📂 Project Structure

Blockchain-Project/

├── block.py # Block structure with hash and nonce

├── blockchain.py # Blockchain logic: mining, validation

├── main.py # Runner script to create & validate blockchain

└── README.md # Documentation


---

## 🚀 How to Run

### Prerequisites
- Python 3.x installed on your system

### Steps to Run
```bash
git clone https://github.com/itsroshan09/Blockchain-Project.git
cd Blockchain-Project
python3 main.py


🛠️ Features
⛓️ Create a chain of blocks with unique hashes

🔐 SHA-256 hashing for secure block linking

🧩 Adjustable Proof-of-Work difficulty level

✅ Blockchain validation to detect tampering

📦 Easily extendable to support transactions or networking

🎯 How It Works
The Genesis Block is created (first block with no previous hash).

New blocks are created and contain:

Custom data

Timestamp

A calculated hash

A pointer (hash) to the previous block

The block is mined using a Proof-of-Work loop until the hash meets a required difficulty (e.g., starts with 0000).

Once mined, the block is added to the chain.

The full blockchain is validated by checking all hashes and links.



📸 Sample Output (Console)
Block 1 mined: 0000a7d0abf4b...
Block 2 mined: 00001cd5a3e1f...
Blockchain is valid: True

📚 Learning Outcomes
Understand how blockchains work internally

Practice hashing, PoW algorithms, and chain validation

Learn how blockchain prevents tampering and double spending

👨‍💻 Author
Roshan Patil
LinkedIn | GitHub




📜 License
This project is licensed under the MIT License.
