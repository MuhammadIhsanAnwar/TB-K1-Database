<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Wallet - Lapak Gaming</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-50">
    <nav class="bg-white shadow-lg sticky top-0 z-50">
        <div class="container mx-auto px-4">
            <div class="flex justify-between items-center py-4">
                <a href="/" class="text-2xl font-bold text-indigo-600">
                    <i class="fas fa-gamepad"></i> Lapak Gaming
                </a>
                <div class="flex items-center space-x-4">
                    <a href="/dashboard" class="text-gray-700 hover:text-indigo-600">Dashboard</a>
                    <a href="/orders" class="text-gray-700 hover:text-indigo-600">Orders</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold mb-8">My Wallet</h1>
        
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-gradient-to-r from-green-600 to-green-700 rounded-lg shadow-lg p-6 text-white">
                <h3 class="text-lg mb-2">Available Balance</h3>
                <p id="balance" class="text-3xl font-bold">Rp 0</p>
            </div>
            
            <div class="bg-gradient-to-r from-orange-600 to-orange-700 rounded-lg shadow-lg p-6 text-white">
                <h3 class="text-lg mb-2">Pending Balance</h3>
                <p id="pendingBalance" class="text-3xl font-bold">Rp 0</p>
            </div>
            
            <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 rounded-lg shadow-lg p-6 text-white">
                <h3 class="text-lg mb-2">Total Earned</h3>
                <p id="totalEarned" class="text-3xl font-bold">Rp 0</p>
            </div>
        </div>
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-4">Deposit</h3>
                <form id="depositForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Amount</label>
                        <input type="number" name="amount" min="10000" required
                               class="w-full px-4 py-2 border rounded-lg" placeholder="10000">
                    </div>
                    <button type="submit" class="w-full bg-green-600 text-white py-2 rounded-lg hover:bg-green-700">
                        Deposit Now
                    </button>
                </form>
            </div>
            
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-xl font-bold mb-4">Withdraw</h3>
                <form id="withdrawForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium mb-2">Amount</label>
                        <input type="number" name="amount" min="10000" required
                               class="w-full px-4 py-2 border rounded-lg" placeholder="10000">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Bank Name</label>
                        <input type="text" name="bank_name" required
                               class="w-full px-4 py-2 border rounded-lg" placeholder="BCA">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-2">Account Number</label>
                        <input type="text" name="account_number" required
                               class="w-full px-4 py-2 border rounded-lg" placeholder="1234567890">
                    </div>
                    <button type="submit" class="w-full bg-red-600 text-white py-2 rounded-lg hover:bg-red-700">
                        Withdraw
                    </button>
                </form>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-xl font-bold mb-4">Transaction History</h3>
            <div id="transactionsList" class="space-y-4">
                <div class="text-center py-12">
                    <i class="fas fa-spinner fa-spin text-4xl text-indigo-600"></i>
                </div>
            </div>
        </div>
    </div>

    <script>
        const API_BASE = window.location.origin + '/api';
        const token = localStorage.getItem('access_token');
        
        if (!token) window.location.href = '/login';

        async function loadWallet() {
            try {
                const res = await fetch(API_BASE + '/wallet', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await res.json();
                const wallet = data.data.wallet;
                
                document.getElementById('balance').textContent = 'Rp ' + parseInt(wallet.balance).toLocaleString('id-ID');
                document.getElementById('pendingBalance').textContent = 'Rp ' + parseInt(wallet.pending_balance).toLocaleString('id-ID');
                document.getElementById('totalEarned').textContent = 'Rp ' + parseInt(wallet.total_earned).toLocaleString('id-ID');
                
                loadTransactions();
            } catch (error) {
                console.error('Failed to load wallet:', error);
            }
        }

        async function loadTransactions() {
            try {
                const res = await fetch(API_BASE + '/wallet/transactions?limit=20', {
                    headers: { 'Authorization': `Bearer ${token}` }
                });
                const data = await res.json();
                const transactions = data.data.transactions;
                
                if (transactions.length === 0) {
                    document.getElementById('transactionsList').innerHTML = '<p class="text-gray-600 text-center">No transactions yet</p>';
                    return;
                }
                
                document.getElementById('transactionsList').innerHTML = transactions.map(tx => `
                    <div class="border rounded-lg p-4 flex justify-between items-center">
                        <div>
                            <p class="font-semibold">${tx.type.toUpperCase()}</p>
                            <p class="text-sm text-gray-600">${tx.description}</p>
                            <p class="text-xs text-gray-500">${new Date(tx.created_at).toLocaleString()}</p>
                        </div>
                        <div class="text-right">
                            <p class="text-lg font-bold ${tx.type === 'deposit' || tx.type === 'earning' ? 'text-green-600' : 'text-red-600'}">
                                ${tx.type === 'deposit' || tx.type === 'earning' ? '+' : '-'}Rp ${parseInt(tx.amount).toLocaleString('id-ID')}
                            </p>
                            <p class="text-xs text-gray-500">Balance: Rp ${parseInt(tx.balance_after).toLocaleString('id-ID')}</p>
                        </div>
                    </div>
                `).join('');
            } catch (error) {
                console.error('Failed to load transactions:', error);
            }
        }

        document.getElementById('depositForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = Object.fromEntries(formData);
            
            try {
                const response = await fetch(API_BASE + '/wallet/deposit', {
                    method: 'POST',
                    headers: { 
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    throw new Error('Deposit failed');
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Deposit successful!',
                    confirmButtonColor: '#4f46e5'
                });
                e.target.reset();
                loadWallet();
            } catch (error) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Deposit failed',
                    confirmButtonColor: '#4f46e5'
                });
            }
        });

        document.getElementById('withdrawForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const formData = new FormData(e.target);
            const data = { ...Object.fromEntries(formData), account_name: 'User' };
            
            try {
                const response = await fetch(API_BASE + '/wallet/withdraw', {
                    method: 'POST',
                    headers: { 
                        'Authorization': `Bearer ${token}`,
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify(data)
                });

                if (!response.ok) {
                    throw new Error('Withdrawal failed');
                }

                await Swal.fire({
                    icon: 'success',
                    title: 'Success',
                    text: 'Withdrawal request submitted!',
                    confirmButtonColor: '#4f46e5'
                });
                e.target.reset();
                loadWallet();
            } catch (error) {
                await Swal.fire({
                    icon: 'error',
                    title: 'Failed',
                    text: 'Withdrawal failed',
                    confirmButtonColor: '#4f46e5'
                });
            }
        });

        loadWallet();
    </script>
</body>
</html>
