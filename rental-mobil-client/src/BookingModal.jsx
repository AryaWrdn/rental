import { useState, useEffect } from 'react';

function BookingModal({ isOpen, onClose, car, user, API_BASE_URL }) {
  const [rentalType, setRentalType] = useState('lepas_kunci');
  const [ktpPhoto, setKtpPhoto] = useState(null);
  const [drivers, setDrivers] = useState([]);
  const [selectedDriverId, setSelectedDriverId] = useState('');
  const [loading, setLoading] = useState(false);

  useEffect(() => {
    if (rentalType === 'dengan_supir') {
      fetch(`${API_BASE_URL}/api/drivers`)
        .then(res => res.json())
        .then(data => {
          if (data.success) setDrivers(data.data);
        })
        .catch(err => console.error("Gagal ambil data supir:", err));
    }
  }, [rentalType, API_BASE_URL]);

  if (!isOpen || !car) return null;

  const handleSubmit = async (e) => {
    e.preventDefault();
    setLoading(true);

    const formData = new FormData();
    formData.append('user_id', user.id);
    formData.append('car_id', car.id);
    formData.append('rental_type', rentalType);
    
    if (rentalType === 'lepas_kunci') {
      if (ktpPhoto) {
        formData.append('ktp_photo', ktpPhoto);
      }
    } else {
      if (selectedDriverId) {
        formData.append('driver_id', selectedDriverId);
      }
    }

    try {
      const response = await fetch(`${API_BASE_URL}/api/bookings`, {
        method: 'POST',
        body: formData, // Jangan set header Content-Type secara manual saat pakai FormData!
      });
      const result = await response.json();

      if (result.success) {
        const chosenDriver = drivers.find(d => d.id == selectedDriverId);
        const driverText = chosenDriver ? `• Supir Pilihan : ${chosenDriver.name} (${chosenDriver.phone})` : '';
        const serviceText = rentalType === 'lepas_kunci' ? '• Layanan : Lepas Kunci (KTP Dilampirkan)' : `• Layanan : Dengan Supir\n${driverText}`;

        const message = `Halo Admin VJ Rental Mobil! 👋\n\nSaya ingin mengonfirmasi pesanan armada:\n\n• Pemesan: ${user.name} (${user.email})\n• Mobil : ${car.name}\n${serviceText}\n• Harga : IDR ${new Intl.NumberFormat('id-ID').format(car.price)}/Hari\n\nMohon dicek di sistem. Terima kasih!`;

        window.open(`https://wa.me/6281262772091?text=${encodeURIComponent(message)}`, '_blank');
        onClose();
      } else {
        alert("Gagal memproses booking: " + (result.message || 'Terjadi kesalahan'));
      }
    } catch (error) {
      console.error("Error:", error);
      alert("Terjadi kesalahan koneksi ke server.");
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 bg-black/80 flex items-center justify-center z-50 p-4">
      <div className="bg-navyDark border border-gray-800 rounded-2xl max-w-lg w-full p-6 text-gray-300 max-h-[90vh] overflow-y-auto">
        <h2 className="text-xl font-bold text-yellowNeon mb-4 uppercase tracking-wide border-b border-gray-800 pb-2">Formulir Pemesanan Unit</h2>
        
        <form onSubmit={handleSubmit} className="space-y-4 text-xs">
          <div className="bg-slate-800/40 p-3 rounded-lg border border-gray-800 space-y-1">
            <p><span className="text-yellowNeon font-bold">Pemesan:</span> {user?.name} ({user?.email})</p>
            <p><span className="text-yellowNeon font-bold">Mobil:</span> {car.name} - IDR {new Intl.NumberFormat('id-ID').format(car.price)}/Hari</p>
          </div>

          <div>
            <label className="block font-bold text-white mb-2">Pilih Tipe Layanan:</label>
            <div className="flex space-x-6">
              <label className="flex items-center space-x-2 cursor-pointer">
                <input 
                  type="radio" 
                  name="rentalType" 
                  value="lepas_kunci" 
                  checked={rentalType === 'lepas_kunci'} 
                  onChange={() => setRentalType('lepas_kunci')} 
                />
                <span className="text-white font-medium">Lepas Kunci</span>
              </label>
              <label className="flex items-center space-x-2 cursor-pointer">
                <input 
                  type="radio" 
                  name="rentalType" 
                  value="dengan_supir" 
                  checked={rentalType === 'dengan_supir'} 
                  onChange={() => setRentalType('dengan_supir')} 
                />
                <span className="text-white font-medium">Dengan Supir</span>
              </label>
            </div>
          </div>

          {rentalType === 'lepas_kunci' && (
            <div className="space-y-1">
              <label className="block text-white font-bold">Upload Foto KTP Asli:</label>
              <input 
                type="file" 
                accept="image/*" 
                required 
                onChange={(e) => setKtpPhoto(e.target.files[0])}
                className="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-yellowNeon file:text-navyDark hover:file:bg-white cursor-pointer"
              />
            </div>
          )}

      {rentalType === 'dengan_supir' && (
            <div className="space-y-2">
              <label className="block text-white font-bold">Pilih Supir yang Tersedia:</label>
              <select 
                required
                value={selectedDriverId}
                onChange={(e) => setSelectedDriverId(e.target.value)}
                className="w-full bg-slate-800 border border-gray-700 rounded-lg p-2.5 text-white focus:outline-none focus:border-yellowNeon"
              >
                <option value="">-- Pilih Supir --</option>
                {drivers
                  .filter(driver => !driver.status || driver.status.toLowerCase() === 'tersedia')
                  .map((driver) => (
                    <option key={driver.id} value={driver.id}>
                      {driver.name} (Pengalaman: {driver.experience} - Telp: {driver.phone})
                    </option>
                  ))
                }
              </select>
            </div>
          )}

          <div className="flex space-x-3 pt-4 border-t border-gray-800">
            <button 
              type="button" 
              onClick={onClose} 
              className="w-1/2 bg-gray-700 hover:bg-gray-600 text-white font-bold py-2.5 rounded-lg transition cursor-pointer"
            >
              Batal
            </button>
            <button 
              type="submit" 
              disabled={loading}
              className="w-1/2 bg-yellowNeon hover:bg-white text-navyDark font-extrabold py-2.5 rounded-lg transition cursor-pointer disabled:opacity-50"
            >
              {loading ? 'Memproses...' : 'Booking & Kirim WA 🚀'}
            </button>
          </div>
        </form>
      </div>
    </div>
  );
}

export default BookingModal;