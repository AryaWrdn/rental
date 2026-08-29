import React, { useState, useEffect } from 'react';

export default function BookingModal({ car, user, onClose, setActivePage }) {
  const [durationType, setDurationType] = useState('daily');
  const [daysCount, setDaysCount] = useState(1);
  const [serviceType, setServiceType] = useState('lepas_kunci');
  const [selectedBank, setSelectedBank] = useState('BCA');
  const [ktpFile, setKtpFile] = useState(null);
  
  // State untuk alur step pembayaran
  const [step, setStep] = useState(1); // 1: Form Booking, 2: Upload Bukti Pembayaran
  const [paymentProof, setPaymentProof] = useState(null);
  
  // State untuk data supir dari API
  const [drivers, setDrivers] = useState([]);
  const [selectedDriverId, setSelectedDriverId] = useState('');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (serviceType === 'dengan_supir') {
      fetch('http://localhost:8000/api/drivers')
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            setDrivers(data.data);
          }
        })
        .catch((err) => console.error('Gagal mengambil data supir:', err));
    }
  }, [serviceType]);

  if (!car) return null;

  const pricePerDay = car.price || 300000; 
  const driverPricePerDay = car.driver_price || 150000;

  const bankAccounts = [
    { name: 'BCA', number: '1234567890', holder: 'PT VJ Rental Mobil' },
    { name: 'BNI', number: '0987654321', holder: 'PT VJ Rental Mobil' },
    { name: 'Mandiri', number: '1357924680', holder: 'PT VJ Rental Mobil' },
    { name: 'Bank Jago', number: '5552223331', holder: 'PT VJ Rental Mobil' },
    { name: 'SeaBank', number: '9998887776', holder: 'PT VJ Rental Mobil' },
    { name: 'BRI', number: '4443332221', holder: 'PT VJ Rental Mobil' },
  ];

  let totalDays = 1;
  if (durationType === 'daily') {
    totalDays = Math.max(1, parseInt(daysCount) || 1);
  } else if (durationType === 'weekly') {
    totalDays = 7;
  } else if (durationType === 'monthly') {
    totalDays = 30;
  }

  let carTotal = pricePerDay * totalDays;
  let driverTotal = serviceType === 'dengan_supir' ? driverPricePerDay * totalDays : 0;
  let grandTotal = carTotal + driverTotal;

  const formatRupiah = (number) => {
    return new Intl.NumberFormat('id-ID', { style: 'currency', currency: 'IDR', maximumFractionDigits: 0 }).format(number);
  };

  // Handler saat klik tombol Lanjut ke Pembayaran (Step 1 -> Step 2)
  const handleProceedToPayment = (e) => {
    e.preventDefault();
    if (!ktpFile) {
      alert('Silakan upload foto KTP terlebih dahulu!');
      return;
    }
    setStep(2); // Pindah ke modal upload bukti pembayaran
  };

  // Handler final: Kirim ke Database & Buka WhatsApp (Step 2)
  const handleFinalSubmit = async () => {
    if (!paymentProof) {
      alert('Silakan upload bukti pembayaran terlebih dahulu!');
      return;
    }

    setLoading(true);

    const formData = new FormData();
    formData.append('user_id', user?.id);
    formData.append('car_id', car.id);
    formData.append('rental_type', serviceType);
    formData.append('duration_type', durationType);
    formData.append('days_count', daysCount);
    formData.append('payment_method', selectedBank);
    
    if (serviceType === 'dengan_supir' && selectedDriverId) {
      formData.append('driver_id', selectedDriverId);
    }
    
    if (ktpFile) {
      formData.append('ktp_photo', ktpFile);
    }
    
    // Jika backend Anda juga ingin menyimpan bukti pembayaran, tambahkan kolomnya. 
    // Jika belum ada kolomnya di database, data ini tetap aman dikirim atau diabaikan backend.
    formData.append('payment_proof', paymentProof);

    try {
      const response = await fetch('http://localhost:8000/api/bookings', {
        method: 'POST',
        body: formData,
      });

      const result = await response.json();
      if (!response.ok) {
        alert('Gagal menyimpan booking: ' + (result.message || JSON.stringify(result.errors)));
        setLoading(false);
        return;
      }

      // Berhasil masuk database, lanjut buka WhatsApp
      const message = `Halo Admin VJ Rental Mobil, saya ingin melakukan pemesanan unit:\n\n` +
        `👤 *Nama Pemesan*: ${user?.name}\n` +
        `🚗 *Mobil*: ${car.name}\n` +
        `⏱️ *Durasi*: ${durationType === 'daily' ? `${totalDays} Hari` : durationType === 'weekly' ? '1 Minggu (7 Hari)' : '1 Bulan (30 Hari)'}\n` +
        `🛡️ *Layanan*: ${serviceType === 'dengan_supir' ? 'Dengan Supir' : 'Lepas Kunci'}\n` +
        `💳 *Metode Pembayaran*: ${selectedBank}\n` +
        `💰 *Total Pembayaran*: ${formatRupiah(grandTotal)}\n\n` +
        `Berikut saya lampirkan formulir pemesanan dan bukti pembayarannya. Mohon konfirmasinya, terima kasih!`;

      window.open(`https://wa.me/62895429286627?text=${encodeURIComponent(message)}`, '_blank');
      
      if (onClose) onClose();
      if (setActivePage) setActivePage('home');

    } catch (error) {
      console.error('Error:', error);
      alert('Terjadi kesalahan koneksi ke server.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 bg-black/70 flex items-center justify-center z-50 p-4">
      <div className="bg-navyDark text-white border border-gray-800 rounded-2xl w-full max-w-lg p-6 shadow-2xl relative max-h-[90vh] overflow-y-auto">
        
        {/* STEP 1: FORMULIR PEMESANAN */}
        {step === 1 && (
          <form onSubmit={handleProceedToPayment} className="space-y-4 text-xs">
            <h2 className="text-xl font-extrabold text-yellowNeon mb-1">FORMULIR PEMESANAN UNIT</h2>
            <p className="text-xs text-gray-400 mb-4">Daftar untuk melanjutkan pemesanan armada.</p>

            <div className="bg-gray-900/60 p-3 rounded-lg border border-gray-800 mb-4 text-xs">
              <p><span className="text-gray-400">Pemesan:</span> <span className="font-bold text-white">{user?.name}</span></p>
              <p><span className="text-gray-400">Mobil:</span> <span className="font-bold text-yellowNeon">{car.name} - {formatRupiah(pricePerDay)}/Hari</span></p>
            </div>

            {/* Pilihan Durasi Sewa */}
            <div>
              <label className="block text-gray-300 font-bold mb-2">Pilih Durasi Sewa:</label>
              <div className="grid grid-cols-3 gap-2">
                <label className={`flex items-center justify-center p-2 rounded-lg border cursor-pointer transition ${durationType === 'daily' ? 'border-yellowNeon bg-yellowNeon/10 text-yellowNeon font-bold' : 'border-gray-700 text-gray-300'}`}>
                  <input type="radio" name="duration" value="daily" checked={durationType === 'daily'} onChange={() => setDurationType('daily')} className="hidden" />
                  Per Hari
                </label>
                <label className={`flex items-center justify-center p-2 rounded-lg border cursor-pointer transition ${durationType === 'weekly' ? 'border-yellowNeon bg-yellowNeon/10 text-yellowNeon font-bold' : 'border-gray-700 text-gray-300'}`}>
                  <input type="radio" name="duration" value="weekly" checked={durationType === 'weekly'} onChange={() => setDurationType('weekly')} className="hidden" />
                  1 Minggu
                </label>
                <label className={`flex items-center justify-center p-2 rounded-lg border cursor-pointer transition ${durationType === 'monthly' ? 'border-yellowNeon bg-yellowNeon/10 text-yellowNeon font-bold' : 'border-gray-700 text-gray-300'}`}>
                  <input type="radio" name="duration" value="monthly" checked={durationType === 'monthly'} onChange={() => setDurationType('monthly')} className="hidden" />
                  1 Bulan
                </label>
              </div>
            </div>

            {durationType === 'daily' && (
              <div>
                <label className="block text-gray-300 mb-1">Jumlah Hari Rental:</label>
                <input 
                  type="number" 
                  min="1" 
                  value={daysCount} 
                  onChange={(e) => setDaysCount(e.target.value)} 
                  className="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white focus:border-yellowNeon outline-none"
                />
              </div>
            )}

            {/* Pilihan Tipe Layanan */}
            <div>
              <label className="block text-gray-300 font-bold mb-2">Pilih Tipe Layanan:</label>
              <div className="flex space-x-6">
                <label className="flex items-center space-x-2 cursor-pointer">
                  <input type="radio" name="service" value="lepas_kunci" checked={serviceType === 'lepas_kunci'} onChange={() => setServiceType('lepas_kunci')} className="accent-yellowNeon" />
                  <span>Lepas Kunci</span>
                </label>
                <label className="flex items-center space-x-2 cursor-pointer">
                  <input type="radio" name="service" value="dengan_supir" checked={serviceType === 'dengan_supir'} onChange={() => setServiceType('dengan_supir')} className="accent-yellowNeon" />
                  <span>Dengan Supir (+{formatRupiah(driverPricePerDay)}/hari)</span>
                </label>
              </div>
            </div>

           {/* Dropdown Pilih Supir */}
{serviceType === 'dengan_supir' && (() => {
  const availableDrivers = drivers.filter((driver) => driver.status === 'tersedia');

  return (
    <div>
      <label className="block text-gray-300 mb-1 font-bold">Pilih Supir:</label>
      
      {availableDrivers.length > 0 ? (
        <select 
          value={selectedDriverId} 
          onChange={(e) => setSelectedDriverId(e.target.value)}
          required={serviceType === 'dengan_supir'}
          className="w-full bg-gray-900 border border-gray-700 rounded-lg p-2.5 text-white focus:border-yellowNeon outline-none"
        >
          <option value="">-- Pilih Supir Tersedia --</option>
          {availableDrivers.map((driver) => (
            <option key={driver.id} value={driver.id}>
              {driver.name} ({driver.phone})
            </option>
          ))}
        </select>
      ) : (
        <div className="w-full bg-red-500/10 border border-red-500/30 text-red-400 p-3 rounded-lg text-xs font-semibold flex items-center space-x-2">
          <span>⚠️</span>
          <span>Supir sedang tidak tersedia (Semua sedang bertugas)</span>
        </div>
      )}
    </div>
  );
})()}

            {/* Upload KTP */}
            <div>
              <label className="block text-gray-300 mb-1 font-bold">Upload Foto KTP Asli:</label>
              <input 
                type="file" 
                required
                onChange={(e) => setKtpFile(e.target.files[0])} 
                className="w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-yellowNeon file:text-navyDark hover:file:bg-white cursor-pointer"
              />
            </div>

            {/* Rincian Total Harga */}
            <div className="bg-gray-900 p-4 rounded-xl border border-gray-800 space-y-1.5">
              <div className="flex justify-between text-gray-300">
                <span>Sewa Mobil ({totalDays} hari):</span>
                <span>{formatRupiah(carTotal)}</span>
              </div>
              {serviceType === 'dengan_supir' && (
                <div className="flex justify-between text-gray-300">
                  <span>Biaya Supir ({totalDays} hari):</span>
                  <span>{formatRupiah(driverTotal)}</span>
                </div>
              )}
              <div className="border-t border-gray-800 pt-2 flex justify-between text-sm font-bold text-yellowNeon">
                <span>TOTAL PEMBAYARAN:</span>
                <span>{formatRupiah(grandTotal)}</span>
              </div>
            </div>

            {/* Metode Pembayaran */}
            <div>
              <label className="block text-gray-300 font-bold mb-2">Pilih Metode Pembayaran:</label>
              <div className="grid grid-cols-2 gap-2">
                {bankAccounts.map((bank) => (
                  <label 
                    key={bank.name} 
                    className={`flex flex-col p-2.5 rounded-lg border cursor-pointer transition ${selectedBank === bank.name ? 'border-yellowNeon bg-yellowNeon/10 text-white' : 'border-gray-700 text-gray-300 bg-gray-900/40'}`}
                  >
                    <div className="flex items-center justify-between mb-1">
                      <span className="font-bold text-yellowNeon">{bank.name}</span>
                      <input 
                        type="radio" 
                        name="paymentBank" 
                        value={bank.name} 
                        checked={selectedBank === bank.name} 
                        onChange={() => setSelectedBank(bank.name)} 
                        className="accent-yellowNeon" 
                      />
                    </div>
                    <span className="text-[11px] text-gray-300 font-mono">No. Rek: {bank.number}</span>
                    <span className="text-[10px] text-gray-400">{bank.holder}</span>
                  </label>
                ))}
              </div>
            </div>

            {/* Tombol Aksi Step 1 */}
            <div className="flex space-x-3 pt-2">
              <button 
                type="button" 
                onClick={onClose}  
                className="w-1/2 bg-gray-800 hover:bg-gray-700 text-white font-bold py-2.5 rounded-lg transition cursor-pointer"
              >
                Batal
              </button>
              <button 
                type="submit"
                className="w-1/2 bg-yellowNeon hover:bg-white text-navyDark font-extrabold py-2.5 rounded-lg transition cursor-pointer shadow-lg"
              >
                Lanjut ke Pembayaran ➡️
              </button>
            </div>
          </form>
        )}

        {/* STEP 2: UPLOAD BUKTI PEMBAYARAN */}
        {step === 2 && (
          <div className="space-y-4 text-xs">
            <h2 className="text-xl font-extrabold text-yellowNeon mb-1">KONFIRMASI PEMBAYARAN</h2>
            <p className="text-xs text-gray-400 mb-4">Silakan transfer ke rekening yang dipilih lalu upload bukti pembayaran.</p>

            <div className="bg-gray-900 p-4 rounded-xl border border-gray-800 space-y-2">
              <p className="text-gray-400">Metode Pilihan: <span className="font-bold text-yellowNeon">{selectedBank}</span></p>
              <p className="text-gray-400">Total Tagihan: <span className="font-bold text-green-400 text-sm">{formatRupiah(grandTotal)}</span></p>
            </div>

            <div>
              <label className="block text-gray-300 font-bold mb-2">Upload Bukti Transfer / Pembayaran:</label>
              <input 
                type="file" 
                required
                onChange={(e) => setPaymentProof(e.target.files[0])} 
                className="w-full text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-yellowNeon file:text-navyDark hover:file:bg-white cursor-pointer"
              />
            </div>

            <div className="bg-yellowNeon/10 border border-yellowNeon/30 p-3 rounded-lg text-center text-yellowNeon font-medium">
              💡 Setelah menekan tombol di bawah, data akan disimpan dan Anda akan diarahkan otomatis ke WhatsApp Admin bersama bukti transfer.
            </div>

            {/* Tombol Aksi Step 2 */}
            <div className="flex space-x-3 pt-2">
              <button 
                type="button" 
                onClick={() => setStep(1)}  
                className="w-1/2 bg-gray-800 hover:bg-gray-700 text-white font-bold py-2.5 rounded-lg transition cursor-pointer"
              >
                ⬅️ Kembali
              </button>
              <button 
                type="button"
                disabled={loading}
                onClick={handleFinalSubmit}
                className="w-1/2 bg-yellowNeon hover:bg-white text-navyDark font-extrabold py-2.5 rounded-lg transition cursor-pointer shadow-lg disabled:opacity-50"
              >
                {loading ? 'Menyimpan...' : 'Kirim Bukti ke WA 🚀'}
              </button>
            </div>
          </div>
        )}

      </div>
    </div>
  );
}