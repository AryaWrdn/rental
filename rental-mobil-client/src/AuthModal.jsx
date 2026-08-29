import { useState } from 'react';

function AuthModal({ isOpen, onClose, onLoginSuccess }) {
  const [isRegister, setIsRegister] = useState(false);
  const [formData, setFormData] = useState({ name: '', username: '', password: '' });
  const [showPassword, setShowPassword] = useState(false); // State untuk toggle lihat password
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
          'Accept': 'application/json'
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
        <button onClick={onClose} className="absolute top-4 right-4 text-gray-400 hover:text-white font-bold cursor-pointer">✕</button>

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
                placeholder="agri"
                value={formData.name}
                onChange={(e) => setFormData({ ...formData, name: e.target.value })}
              />
            </div>
          )}

          <div>
            <label className="block text-xs text-gray-300 font-bold mb-1">Username</label>
            <input
              type="text"
              required
              className="w-full bg-slate-900 border border-gray-700 rounded-lg p-2.5 text-xs text-white focus:outline-none focus:border-yellowNeon"
              placeholder="agri123"
              value={formData.username}
              onChange={(e) => setFormData({ ...formData, username: e.target.value })}
            />
          </div>

          <div>
            <label className="block text-xs text-gray-300 font-bold mb-1">Password</label>
            <div className="relative">
              <input
                type={showPassword ? 'text' : 'password'}
                required
                className="w-full bg-slate-900 border border-gray-700 rounded-lg p-2.5 pr-10 text-xs text-white focus:outline-none focus:border-yellowNeon"
                placeholder="••••••••"
                value={formData.password}
                onChange={(e) => setFormData({ ...formData, password: e.target.value })}
              />
              <button
                type="button"
                onClick={() => setShowPassword(!showPassword)}
                className="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-white cursor-pointer focus:outline-none flex items-center justify-center"
              >
                {showPassword ? (
                  /* Ikon Mata Terbuka */
                  <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                    <path strokeLinecap="round" strokeLinejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    <path strokeLinecap="round" strokeLinejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                  </svg>
                ) : (
                  /* Ikon Mata Dicoret Garis (Eye Off) */
                  <svg xmlns="http://www.w3.org/2000/svg" className="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" strokeWidth={2}>
                    <path strokeLinecap="round" strokeLinejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.03 10.03 0 012.25-3.66m3.18-2.18A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                    <path strokeLinecap="round" strokeLinejoin="round" d="M3 3l18 18" />
                  </svg>
                )}
              </button>
            </div>
          </div>

          <button
            type="submit"
            disabled={loading}
            className="w-full bg-yellowNeon text-navyDark font-extrabold py-3 rounded-lg hover:bg-white transition duration-300 text-xs tracking-wider shadow cursor-pointer"
          >
            {loading ? 'MENGHUBUNGKAN...' : isRegister ? 'DAFTAR & BOOKING' : 'LOGIN & CONTINUE'}
          </button>
        </form>

        <div className="mt-6 text-center text-xs text-gray-400 border-t border-gray-800 pt-4">
          {isRegister ? 'Sudah punya akun?' : 'Belum punya akun?'}
          <button
            onClick={() => { setIsRegister(!isRegister); setErrorMsg(''); }}
            className="text-yellowNeon font-bold ml-1 hover:underline focus:outline-none cursor-pointer"
          >
            {isRegister ? 'Login di sini' : 'Daftar sekarang'}
          </button>
        </div>

      </div>
    </div>
  );
}

export default AuthModal;