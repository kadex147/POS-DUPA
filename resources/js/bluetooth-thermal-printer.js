/**
 * ========================================
 * BLUETOOTH THERMAL PRINTER SOLUTION
 * ========================================
 * 
 * Fitur:
 * - Print langsung ke printer thermal Bluetooth dari HP
 * - Support ESC/POS commands
 * - Auto-detect browser support
 * - Fallback ke print biasa jika tidak support
 * 
 * Requirements:
 * - Chrome Android 56+
 * - Website harus HTTPS
 * - Printer thermal Bluetooth
 */

class BluetoothThermalPrinter {
    constructor() {
        this.device = null;
        this.characteristic = null;
        this.encoder = new TextEncoder();
    }

    /**
     * Check apakah browser support Web Bluetooth API
     */
    isSupported() {
        if (!navigator.bluetooth) {
            console.warn('Web Bluetooth API tidak support di browser ini');
            return false;
        }
        
        // Check HTTPS
        if (location.protocol !== 'https:' && location.hostname !== 'localhost') {
            console.warn('Web Bluetooth hanya bisa jalan di HTTPS');
            return false;
        }
        
        return true;
    }

    /**
     * Connect ke printer Bluetooth
     */
    async connect() {
        try {
            console.log('Mencari printer Bluetooth...');
            
            // Request Bluetooth device
            this.device = await navigator.bluetooth.requestDevice({
                filters: [
                    { services: ['000018f0-0000-1000-8000-00805f9b34fb'] }, // Printer service
                ],
                optionalServices: ['000018f0-0000-1000-8000-00805f9b34fb']
            });

            console.log('Printer ditemukan:', this.device.name);

            // Connect ke GATT server
            const server = await this.device.gatt.connect();
            console.log('Terhubung ke GATT server');

            // Get service
            const service = await server.getPrimaryService('000018f0-0000-1000-8000-00805f9b34fb');
            
            // Get characteristic
            this.characteristic = await service.getCharacteristic('00002af1-0000-1000-8000-00805f9b34fb');
            
            console.log('Siap untuk print!');
            return true;

        } catch (error) {
            console.error('Gagal connect ke printer:', error);
            
            if (error.name === 'NotFoundError') {
                alert('Printer tidak ditemukan. Pastikan printer Bluetooth sudah menyala dan dalam mode pairing.');
            } else {
                alert('Gagal terhubung ke printer: ' + error.message);
            }
            
            return false;
        }
    }

    /**
     * Disconnect dari printer
     */
    disconnect() {
        if (this.device && this.device.gatt.connected) {
            this.device.gatt.disconnect();
            console.log('Disconnected dari printer');
        }
    }

    /**
     * ESC/POS Commands
     */
    getESCPOS() {
        return {
            INIT: [0x1B, 0x40], // Initialize printer
            ALIGN_CENTER: [0x1B, 0x61, 0x01],
            ALIGN_LEFT: [0x1B, 0x61, 0x00],
            ALIGN_RIGHT: [0x1B, 0x61, 0x02],
            BOLD_ON: [0x1B, 0x45, 0x01],
            BOLD_OFF: [0x1B, 0x45, 0x00],
            FEED: [0x0A], // Line feed
            CUT: [0x1D, 0x56, 0x00], // Cut paper
            TEXT_NORMAL: [0x1B, 0x21, 0x00],
            TEXT_2X: [0x1B, 0x21, 0x30],
            TEXT_SMALL: [0x1B, 0x21, 0x01],
        };
    }

    /**
     * Send data ke printer
     */
    async send(data) {
        if (!this.characteristic) {
            throw new Error('Printer belum terkoneksi');
        }

        try {
            // Convert ke Uint8Array jika string
            if (typeof data === 'string') {
                data = this.encoder.encode(data);
            }

            // Send data dalam chunks (max 512 bytes per chunk)
            const chunkSize = 512;
            for (let i = 0; i < data.length; i += chunkSize) {
                const chunk = data.slice(i, i + chunkSize);
                await this.characteristic.writeValue(chunk);
                // Small delay untuk stabilitas
                await new Promise(resolve => setTimeout(resolve, 50));
            }
        } catch (error) {
            console.error('Gagal send data ke printer:', error);
            throw error;
        }
    }

    /**
     * Print text
     */
    async printText(text) {
        const encoded = this.encoder.encode(text);
        await this.send(encoded);
    }

    /**
     * Print line dengan padding
     */
    async printLine(left, right, width = 32) {
        const leftStr = String(left);
        const rightStr = String(right);
        const spaces = width - leftStr.length - rightStr.length;
        const line = leftStr + ' '.repeat(Math.max(0, spaces)) + rightStr + '\n';
        await this.printText(line);
    }

    /**
     * Print separator line
     */
    async printSeparator(char = '-', width = 32) {
        await this.printText(char.repeat(width) + '\n');
    }

    /**
     * Print receipt/struk
     */
    async printReceipt(transaction) {
        const ESC = this.getESCPOS();

        try {
            // Initialize printer
            await this.send(new Uint8Array(ESC.INIT));

            // Header - Center & Bold
            await this.send(new Uint8Array(ESC.ALIGN_CENTER));
            await this.send(new Uint8Array(ESC.TEXT_2X));
            await this.send(new Uint8Array(ESC.BOLD_ON));
            await this.printText('Dupa Radha Kresna\n');
            await this.send(new Uint8Array(ESC.BOLD_OFF));
            await this.send(new Uint8Array(ESC.TEXT_NORMAL));
            
            await this.printText('Jl. Manggis II,\n');
            await this.printText('Bjr Candi Baru Gianyar, Bali\n');
            await this.printText('Telp: 0821 4510 7268\n');
            
            await this.send(new Uint8Array(ESC.FEED));
            await this.printSeparator('=');

            // Transaction Info - Left Align
            await this.send(new Uint8Array(ESC.ALIGN_LEFT));
            
            // Format tanggal
            const date = new Date(transaction.created_at);
            const formattedDate = `${String(date.getDate()).padStart(2, '0')}/${String(date.getMonth() + 1).padStart(2, '0')}/${date.getFullYear()}, ${String(date.getHours()).padStart(2, '0')}:${String(date.getMinutes()).padStart(2, '0')}`;
            
            await this.printLine('Invoice:', transaction.invoice_number);
            await this.printLine('Tanggal:', formattedDate);
            await this.printLine('Kasir:', transaction.user.username);
            
            await this.printSeparator('=');

            // Items
            for (const item of transaction.items) {
                const productName = item.product.name;
                const qty = item.quantity;
                const price = parseFloat(item.price);
                const subtotal = parseFloat(item.subtotal);

                // Product name
                await this.printText(productName + '\n');
                
                // Qty x Price = Subtotal
                await this.printLine(
                    `  ${qty} x ${price.toLocaleString('id-ID')}`,
                    subtotal.toLocaleString('id-ID')
                );
            }

            await this.printSeparator('=');

            // Total
            await this.send(new Uint8Array(ESC.TEXT_2X));
            await this.send(new Uint8Array(ESC.BOLD_ON));
            await this.printLine('TOTAL:', 'Rp ' + parseFloat(transaction.total).toLocaleString('id-ID'));
            await this.send(new Uint8Array(ESC.BOLD_OFF));
            await this.send(new Uint8Array(ESC.TEXT_NORMAL));
            
            await this.printSeparator('=');

            // Footer - Center
            await this.send(new Uint8Array(ESC.ALIGN_CENTER));
            await this.send(new Uint8Array(ESC.FEED));
            await this.printText('Terima kasih atas\n');
            await this.printText('kunjungan Anda!\n');
            await this.send(new Uint8Array(ESC.FEED));
            await this.printText('--- Barang yang ---\n');
            await this.printText('sudah dibeli\n');
            await this.printText('--- tidak dapat ---\n');
            await this.printText('dikembalikan\n');

            // Feed & Cut
            await this.send(new Uint8Array(ESC.FEED));
            await this.send(new Uint8Array(ESC.FEED));
            await this.send(new Uint8Array(ESC.FEED));
            await this.send(new Uint8Array(ESC.CUT));

            console.log('Print selesai!');
            return true;

        } catch (error) {
            console.error('Error saat print:', error);
            alert('Gagal print: ' + error.message);
            return false;
        }
    }
}

// ========================================
// GLOBAL FUNCTIONS UNTUK INTEGRASI
// ========================================

// Instance global printer
window.bluetoothPrinter = new BluetoothThermalPrinter();

/**
 * Fungsi utama untuk print (dengan auto-detect support)
 */
window.printReceipt = async function() {
    if (!window.currentTransaction) {
        alert('Data transaksi tidak tersedia');
        return;
    }

    const transaction = window.currentTransaction;

    // Check apakah support Bluetooth
    if (window.bluetoothPrinter.isSupported()) {
        // Tampilkan pilihan: Bluetooth atau Print Biasa
        const useBluetooth = confirm(
            'Pilih metode print:\n\n' +
            'OK = Print via Bluetooth (Printer Thermal)\n' +
            'Cancel = Print Biasa (Browser)'
        );

        if (useBluetooth) {
            await printViaBluetooth(transaction);
        } else {
            printViaWindow(transaction);
        }
    } else {
        // Fallback ke print biasa
        console.log('Bluetooth tidak support, gunakan print biasa');
        printViaWindow(transaction);
    }
};

/**
 * Print via Bluetooth
 */
async function printViaBluetooth(transaction) {
    try {
        // Show loading
        const originalText = event.target ? event.target.textContent : '';
        if (event.target) {
            event.target.textContent = 'Menghubungkan...';
            event.target.disabled = true;
        }

        // Connect ke printer (jika belum)
        if (!window.bluetoothPrinter.device || !window.bluetoothPrinter.device.gatt.connected) {
            const connected = await window.bluetoothPrinter.connect();
            if (!connected) {
                if (event.target) {
                    event.target.textContent = originalText;
                    event.target.disabled = false;
                }
                return;
            }
        }

        // Print receipt
        if (event.target) {
            event.target.textContent = 'Mencetak...';
        }

        const success = await window.bluetoothPrinter.printReceipt(transaction);

        if (success) {
            alert('Print berhasil! ✓');
        }

        // Reset button
        if (event.target) {
            event.target.textContent = originalText;
            event.target.disabled = false;
        }

    } catch (error) {
        console.error('Error print Bluetooth:', error);
        alert('Gagal print via Bluetooth. Coba gunakan print biasa.');
        
        // Reset button
        if (event.target) {
            event.target.textContent = originalText;
            event.target.disabled = false;
        }
    }
}

/**
 * Print via Window (fallback untuk non-Bluetooth)
 */
function printViaWindow(transaction) {
    // Generate HTML untuk print (kode lama Anda)
    const date = new Date(transaction.created_at);
    const day = String(date.getDate()).padStart(2, '0');
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const year = date.getFullYear();
    const hours = String(date.getHours()).padStart(2, '0');
    const minutes = String(date.getMinutes()).padStart(2, '0');
    const formattedDate = `${day}/${month}/${year}, ${hours}:${minutes}`;

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

    const printHTML = `
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
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
        .total-section { border-top: 1px dashed #000; padding-top: 5px; margin-top: 5px; }
        .total-section table { width: 100%; font-size: 13px; }
        .footer { text-align: center; margin-top: 8px; padding-top: 5px; border-top: 1px dashed #000; font-size: 10px; line-height: 1.3; }
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
    <p style="margin-top: 5px;">--- Barang yang ---</p>
    <p> sudah dibeli </p>
    <p>--- tidak dapat ---</p>
    <p>dikembalikan</p>
</div>
</body>
</html>
    `;

    const printWindow = window.open('', '', 'width=200,height=600');
    printWindow.document.write(printHTML);
    printWindow.document.close();
    
    setTimeout(() => {
        printWindow.focus();
        printWindow.print();
        printWindow.close();
    }, 300);
}

console.log('✅ Bluetooth Thermal Printer ready!');