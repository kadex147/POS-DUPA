{{-- resources/views/components/modal-print.blade.php --}}

<div id="postTransactionDetailModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl p-4 lg:p-6 w-full max-w-md relative max-h-[90vh] overflow-y-auto">
        <button onclick="closePostTransactionModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
        
        <div class="flex justify-center mb-4">
            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center">
                <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
        </div>

        <h3 class="text-lg lg:text-xl font-bold mb-4 text-green-600 text-center">Transaksi Berhasil!</h3> 
        
        <div class="mb-4 space-y-2 text-sm bg-gray-50 p-4 rounded-lg">
            <div class="flex justify-between">
                <span class="font-semibold text-gray-600">Invoice:</span>
                <span id="postDetailModalInvoice" class="font-mono font-semibold"></span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold text-gray-600">Tanggal:</span>
                <span id="postDetailModalTanggal"></span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold text-gray-600">Kasir:</span>
                <span id="postDetailModalKasir"></span>
            </div>
        </div>

        <div class="overflow-x-auto mb-4">
            <table class="w-full">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="text-left py-2 text-xs lg:text-sm px-2">Nama</th>
                        <th class="text-center py-2 text-xs lg:text-sm px-2">Jml</th>
                        <th class="text-right py-2 text-xs lg:text-sm px-2">Total</th>
                    </tr>
                </thead>
                <tbody id="postDetailModalItemsBody" class="text-xs lg:text-sm divide-y">
                </tbody>
            </table>
        </div>
        
        <div class="border-t pt-4 mb-4">
            <div class="flex justify-between font-bold text-base lg:text-lg">
                <span>TOTAL:</span>
                <span id="postDetailModalTotal" class="text-green-600">Rp 0</span>
            </div>
        </div>
        
        <div class="space-y-2">
            <button onclick="printReceiptAndClose()" 
                    class="w-full py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition font-semibold flex items-center justify-center gap-2 text-sm lg:text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span class="button-text">Cetak Struk</span>
            </button>
            
            <button onclick="closePostTransactionModal()" 
                    class="w-full py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold text-sm lg:text-base">
                Tutup
            </button>
        </div>
    </div>
</div>

<div id="transactionPrintModal" class="fixed inset-0 bg-black/50 backdrop-blur-sm hidden items-center justify-center z-50 p-4">
    <div class="bg-white rounded-lg shadow-xl p-4 lg:p-6 w-full max-w-md relative max-h-[90vh] overflow-y-auto">
        <button onclick="closePrintModal()" class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition">
            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"/>
            </svg>
        </button>
        
        <h3 class="text-lg lg:text-xl font-bold mb-4 text-gray-800">Detail Transaksi</h3>
        
        <div class="mb-4 space-y-1 text-sm text-gray-600 bg-gray-50 p-4 rounded-lg">
            <div class="flex justify-between">
                <span class="font-semibold">Invoice:</span>
                <span id="printModalInvoice" class="font-mono"></span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Tanggal:</span>
                <span id="printModalTanggal"></span>
            </div>
            <div class="flex justify-between">
                <span class="font-semibold">Kasir:</span>
                <span id="printModalKasir"></span>
            </div>
        </div>
        
        <div class="overflow-x-auto">
            <table class="w-full mb-4">
                <thead>
                    <tr class="border-b bg-gray-50">
                        <th class="text-left py-2 text-sm px-2">Nama</th>
                        <th class="text-center py-2 text-sm px-2">Jml</th>
                        <th class="text-right py-2 text-sm px-2">Total</th>
                    </tr>
                </thead>
                <tbody id="printModalItemsBody" class="text-sm divide-y"></tbody>
            </table>
        </div>
        
        <div class="border-t pt-4 mb-4">
            <div class="flex justify-between font-bold text-base lg:text-lg">
                <span>Total:</span>
                <span id="printModalTotal" class="text-green-600">Rp 0</span>
            </div>
        </div>
        
        <div class="space-y-2">
            <button onclick="printReceiptAndCloseHistory()" 
                    class="w-full py-3 bg-gray-700 text-white rounded-lg hover:bg-gray-800 transition font-semibold flex items-center justify-center gap-2 text-sm lg:text-base">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                </svg>
                <span class="button-text">Cetak Struk</span>
            </button>
            
            <button onclick="closePrintModal()" 
                    class="w-full py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold text-sm lg:text-base">
                Tutup
            </button>
        </div>
    </div>
</div>

<script>
/**
 * Deteksi apakah device adalah mobile atau tablet
 */
function isMobileDevice() {
    const userAgent = navigator.userAgent || navigator.vendor || window.opera;
    
    // Debug logging
    console.log('=== MOBILE DETECTION ===');
    console.log('User Agent:', userAgent);
    console.log('Screen Width:', window.innerWidth);
    console.log('maxTouchPoints:', navigator.maxTouchPoints);
    
    // 1. Deteksi Android
    if (/Android/i.test(userAgent)) {
        console.log('✅ Detected: Android device');
        return true;
    }
    
    // 2. Deteksi iOS
    if (/iPhone|iPad|iPod/i.test(userAgent)) {
        console.log('✅ Detected: iOS device');
        return true;
    }
    
    // 3. Deteksi iPad modern
    if (userAgent.match(/Mac/) && navigator.maxTouchPoints && navigator.maxTouchPoints > 2) {
        console.log('✅ Detected: iPad (modern)');
        return true;
    }
    
    // 4. Deteksi generic mobile
    if (/Mobile|Tablet|webOS|BlackBerry|IEMobile|Opera Mini/i.test(userAgent)) {
        console.log('✅ Detected: Generic mobile');
        return true;
    }
    
    // 5. Deteksi touch device
    if (navigator.maxTouchPoints > 0 && window.innerWidth <= 1280) {
        console.log('✅ Detected: Touch device');
        return true;
    }
    
    console.log('❌ Not detected as mobile/tablet');
    return false;
}

/**
 * Update text tombol cetak
 */
function updatePrintButtonText() {
    const isMobile = isMobileDevice();
    const buttonText = isMobile ? 'Simpan sebagai PDF' : 'Cetak Struk';
    
    console.log('Button text:', buttonText);
    
    document.querySelectorAll('.button-text').forEach((span, index) => {
        span.textContent = buttonText;
        console.log(`Button ${index + 1} updated to:`, span.textContent);
    });
}

/**
 * Menampilkan modal transaksi berhasil
 */
window.showPostTransactionModal = function(transactionData) {
    const modal = document.getElementById('postTransactionDetailModal');
    modal.setAttribute('data-transaction', JSON.stringify(transactionData));
    
    const formattedDate = new Date(transactionData.created_at).toLocaleString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
    });
    const formattedTotal = 'Rp ' + parseFloat(transactionData.total).toLocaleString('id-ID');

    document.getElementById('postDetailModalInvoice').textContent = transactionData.invoice_number;
    document.getElementById('postDetailModalTanggal').textContent = formattedDate;
    document.getElementById('postDetailModalKasir').textContent = transactionData.user.username;
    document.getElementById('postDetailModalTotal').textContent = formattedTotal;

    const itemsBody = document.getElementById('postDetailModalItemsBody');
    itemsBody.innerHTML = '';

    transactionData.items.forEach(item => {
        const itemTotal = 'Rp ' + parseFloat(item.subtotal).toLocaleString('id-ID');
        const row = `
            <tr class="border-gray-100">
                <td class="py-2 px-2">${item.product.name}</td>
                <td class="text-center py-2 px-2">${item.quantity}</td>
                <td class="text-right py-2 px-2">${itemTotal}</td>
            </tr>
        `;
        itemsBody.innerHTML += row;
    });

    updatePrintButtonText();
    modal.classList.remove('hidden');
    modal.classList.add('flex');
};

window.closePostTransactionModal = function() {
    const modal = document.getElementById('postTransactionDetailModal');
    modal.removeAttribute('data-transaction');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    window.location.reload();
};

window.openPrintModal = function(transactionData) {
    const modal = document.getElementById('transactionPrintModal');
    modal.setAttribute('data-transaction', JSON.stringify(transactionData));
    
    const formattedDate = new Date(transactionData.created_at).toLocaleString('id-ID', {
        day: 'numeric', month: 'long', year: 'numeric', hour: '2-digit', minute: '2-digit'
    });
    const formattedTotal = 'Rp ' + parseFloat(transactionData.total).toLocaleString('id-ID');

    document.getElementById('printModalInvoice').textContent = transactionData.invoice_number;
    document.getElementById('printModalTanggal').textContent = formattedDate;
    document.getElementById('printModalKasir').textContent = transactionData.user.username;
    document.getElementById('printModalTotal').textContent = formattedTotal;

    const itemsBody = document.getElementById('printModalItemsBody');
    itemsBody.innerHTML = '';

    transactionData.items.forEach(item => {
        const itemTotal = 'Rp ' + parseFloat(item.subtotal).toLocaleString('id-ID');
        const row = `
            <tr class="border-gray-100">
                <td class="py-2 px-2">${item.product.name}</td>
                <td class="text-center py-2 px-2">${item.quantity}</td>
                <td class="text-right py-2 px-2">${itemTotal}</td>
            </tr>
        `;
        itemsBody.innerHTML += row;
    });

    updatePrintButtonText();
    modal.classList.remove('hidden');
    modal.classList.add('flex');
};

window.closePrintModal = function() {
    const modal = document.getElementById('transactionPrintModal');
    modal.removeAttribute('data-transaction');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
};

window.printReceiptAndClose = function() {
    printReceipt();
    setTimeout(() => {
        closePostTransactionModal();
    }, 500);
};

window.printReceiptAndCloseHistory = function() {
    printReceipt();
    setTimeout(() => {
        closePrintModal();
    }, 500);
};

/**
 * Cetak struk - BEHAVIOR SAMA untuk mobile dan desktop
 * Auto print & auto close
 */
window.printReceipt = function() {
    const postModal = document.getElementById('postTransactionDetailModal');
    const historyModal = document.getElementById('transactionPrintModal');
    let activeModal = null;

    if (!postModal.classList.contains('hidden')) {
        activeModal = postModal;
    } else if (!historyModal.classList.contains('hidden')) {
        activeModal = historyModal;
    }

    if (!activeModal) {
        alert('Modal transaksi tidak ditemukan.');
        return;
    }

    const transactionJson = activeModal.getAttribute('data-transaction');
    if (!transactionJson) {
        alert('Data transaksi tidak tersedia.');
        return;
    }

    const transaction = JSON.parse(transactionJson);
    const date = new Date(transaction.created_at);
    const formattedDate = `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()}, ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;

    let itemsHTML = '';
    transaction.items.forEach(item => {
        const itemName = item.product.name;
        const qty = item.quantity;
        const price = parseFloat(item.price);
        const subtotal = parseFloat(item.subtotal);
        
        itemsHTML += `
<div style="margin-bottom: 3px;">
  <div style="font-size: 13px;">${itemName}</div>
  <table width="100%" style="font-size: 12px;">
    <tr>
      <td width="65%">${qty} x ${price.toLocaleString('id-ID')}</td>
      <td width="5%"> </td>
      <td width="30%" align="right">${subtotal.toLocaleString('id-ID')}</td>
    </tr>
  </table>
</div>
        `;
    });

    const isMobile = isMobileDevice();

    const printHTML = `
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk - ${transaction.invoice_number}</title>
    <style>
        @page { margin: 0; size: 58mm auto; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            font-size: 11px;
            font-weight: bold;
            padding: 2mm;
            width: 48mm;
            background: white;
            color: #000;
            line-height: 1.2;
        }
        .header { text-align: center; margin-bottom: 5px; padding-bottom: 5px; border-bottom: 1px dashed #000; }
        .header h1 { font-size: 14px; font-weight: bold; margin-bottom: 2px; }
        .header p { font-size: 10px; margin: 1px 0; }
        .divider { border-top: 1px dashed #000; margin: 5px 0; }
        .info { font-size: 10px; margin: 5px 0; }
        .info table { width: 100%; border-collapse: collapse; }
        .info td { padding: 1px 0; }
        .info td:first-child { width: 30%; }
        .info td:last-child { width: 70%; text-align: right; }
        .items { margin: 5px 0; }
        .items table { width: 100%; border-collapse: collapse; }
        .items td { padding: 0; }
        .total-section { border-top: 1px dashed #000; padding-top: 5px; margin-top: 5px; }
        .total-section table { width: 100%; font-size: 13px; }
        .total-section td { padding: 2px 0; }
        .footer { text-align: center; margin-top: 8px; padding-top: 5px; border-top: 1px dashed #000; font-size: 10px; line-height: 1.3; }
        @media print { body { margin: 0; padding: 3mm; } }
    </style>
</head>
<body>
<div class="header">
    <h1>Dupa Radha Kresna</h1>
    <p>Jl. Manggis II, Bjr Candi Baru Gianyar, Bali</p>
    <p>Telp: 0821 4510 7268</p>
</div>
<div class="divider"></div>
<div class="info">
    <table>
        <tr><td>Invoice:</td><td>${transaction.invoice_number}</td></tr>
        <tr><td>Tanggal:</td><td>${formattedDate}</td></tr>
        <tr><td>Kasir:</td><td>${transaction.user.username}</td></tr>
    </table>
</div>
<div class="divider"></div>
<div class="items">${itemsHTML}</div>
<div class="total-section">
    <table>
        <tr><td>TOTAL:</td><td align="right">Rp ${parseFloat(transaction.total).toLocaleString('id-ID')}</td></tr>
    </table>
</div>
<div class="footer">
    <p>Terima kasih atas</p>
    <p>kunjungan Anda!</p>
</div>
<script>
    window.addEventListener('load', function() {
        setTimeout(function() {
            window.print();
            window.addEventListener('afterprint', function() {
                setTimeout(function() { window.close(); }, 500);
            });
            setTimeout(function() {
                if (!window.closed) { window.close(); }
            }, 3000);
        }, 500);
    });
<\/script>
</body>
</html>
    `;

    // SAMA untuk mobile dan desktop: Auto print & auto close
    if (isMobile) {
        const printWindow = window.open('', '_blank');
        if (printWindow) {
            printWindow.document.write(printHTML);
            printWindow.document.close();
        } else {
            alert('Mohon izinkan popup untuk mencetak struk');
        }
    } else {
        const printWindow = window.open('', '', 'width=200,height=600');
        printWindow.document.write(printHTML);
        printWindow.document.close();
    }
};

document.addEventListener('DOMContentLoaded', function() {
    updatePrintButtonText();
});

window.addEventListener('resize', function() {
    updatePrintButtonText();
});
</script>