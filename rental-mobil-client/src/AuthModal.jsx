import { useState } from 'react';

function AuthModal({ isOpen, onClose, onLoginSuccess }) {
  const [isRegister, setIsRegister] = useState(false);
  const [formData, setFormData] = useState({ name: '', email: '', password: '' });
  const [errorMsg, setErrorMsg] = useState('');
  const [loading, setLoading] = useState(false);

  if (!isOpen) return null;

  const handleSubmit = async (e) => {
    e.preventDefault();
    setErrorMsg('');
    setLoading(true);

    const endpoint = isRegister ? 'http://127.0.0.1:8000/api/register' : 'http://127.0.0.1:8000/api/login';

    try {
      const response = await fetch(endpoint, {
  method: 'POST',
  headers: { 
    'Content-Type': 'application/json',
    'Accept': 'application/json' // <-- Tambahkan baris ini
  },
  body: JSON.stringify(formData),
});

      const data = await response.json();

      if (data.success) {
        onLoginSuccess(data.user);
        onClose();
      } else {
        setErrorMsg(data.message || 'Terjadi kesalahan, coba lagi.');
      }
    } catch (err) {
      setErrorMsg('Gagal terhubung ke server backend.');
    } finally {
      setLoading(false);
    }
  };

  return (
    <div className="fixed inset-0 z-50 flex items-center justify-center bg-black/70 backdrop-blur-sm p-4">
      <div className="bg-navyDark border border-gray-800 w-full max-w-md rounded-2xl p-6 shadow-2xl relative text-white">
        
        {/* Tombol Close */}
        <button onClick={onClose} className="absolute top-4 right-4 text-gray-400 hover:text-white font-bold">✕</button>

        <h2 className="text-xl font-black text-yellowNeon mb-2 uppercase text-center">
          {isRegister ? 'Buat Akun Baru' : 'Login Terlebih Dahulu'}
        </h2>
        <p className="text-xs text-gray-400 text-center mb-6">
          {isRegister ? 'Daftar untuk melanjutkan pemesanan armada.' : 'Silakan login untuk memproses booking mobil.'}
        </p>

        {errorMsg && (
          <div className="bg-red-500/10 border border-red-500 text-red-400 text-xs p-3 rounded-lg mb-4 text-center">
            {errorMsg}
          </div>
        )}

        <form onSubmit={handleSubmit} className="space-y-4">
          {isRegister && (
            <div>
              <label className="block text-xs text-gray-300 font-bold mb-1">Nama Lengkap</label>
              <input
                type="text"
                required
                className="w-full bg-slate-900 border border-gray-700 rounded-lg p-2.5 text-xs text-white focus:outline-none focus:border-yellowNeon"
                placeholder="John Doe"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              />
            </div>
          )}

          <div>
            <label className="block text-xs text-gray-300 font-bold mb-1">Email Address</label>
            <input
              type="email"
              required
              className="w-full bg-slate-900 border border-gray-700 rounded-lg p-2.5 text-xs text-white focus:outline-none focus:border-yellowNeon"
              placeholder="nama@email.com"
              value={formData.email}
              onChange={(e) => setFormData({ ...formData, email: e.target.value })}
            />
          </div>

          <div>
            <label className="block text-xs text-gray-300 font-bold mb-1">Password</label>
            <input
              type="password"
              required
              className="w-full bg-slate-900 border border-gray-700 rounded-lg p-2.5 text-xs text-white focus:outline-none focus:border-yellowNeon"
              placeholder="••••••••"
              value={formData.password}
              onChange={(e) => setFormData({ ...formData, password: e.target.value })}
            />
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full bg-yellowNeon text-navyDark font-extrabold py-3 rounded-lg hover:bg-white transition duration-300 text-xs tracking-wider shadow"
          >
            {loading ? 'MENGHUBUNGKAN...' : isRegister ? 'DAFTAR & BOOKING' : 'LOGIN & CONTINUE'}
          </button>
        </form>

        <div className="mt-6 text-center text-xs text-gray-400 border-t border-gray-800 pt-4">
          {isRegister ? 'Sudah punya akun?' : 'Belum punya akun?'}
          <button
            onClick={() => { setIsRegister(!isRegister); setErrorMsg(''); }}
            className="text-yellowNeon font-bold ml-1 hover:underline focus:outline-none"
          >
            {isRegister ? 'Login di sini' : 'Daftar sekarang'}
          </button>
        </div>

      </div>
    </div>
  );
}

export default AuthModal;