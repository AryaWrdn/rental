import { useState, useEffect, useRef } from 'react';
import logo from './assets/logo-removebg-preview.png';
import Cars from './Cars';
import AuthModal from './AuthModal'; 

function App() {
  const [carData, setCarData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [currentTextIndex, setCurrentTextIndex] = useState(0);
  const [isAnimating, setIsAnimating] = useState(false);

  // State baru untuk menentukan halaman aktif ('home' atau 'our-cars')
  const [activePage, setActivePage] = useState('home');

  const API_BASE_URL = "http://127.0.0.1:8000";

  const homeRef = useRef(null);
  const locationsRef = useRef(null);
  const contactRef = useRef(null);
  const [user, setUser] = useState(() => {
    const savedUser = localStorage.getItem('user');
    return savedUser ? JSON.parse(savedUser) : null;
  });

  const [isAuthModalOpen, setIsAuthModalOpen] = useState(false);
  const [selectedCarToBook, setSelectedCarToBook] = useState(null);

  const handleLoginSuccess = (userData) => {
    setUser(userData);
    localStorage.setItem('user', JSON.stringify(userData));
    
    // Jika ada mobil yang ditahan saat pencet book now, langsung teruskan booking ke WhatsApp
    if (selectedCarToBook) {
      processBookingWA(selectedCarToBook, userData);
      setSelectedCarToBook(null);
    }
  };

  const handleLogout = () => {
    setUser(null);
    localStorage.removeItem('user');
  };

  // Fungsi mengarahkan ke WhatsApp
  const processBookingWA = (car, currentUser) => {
    const message = `Halo Admin VJ Rental Mobil! 👋\n\nSaya ingin memesan unit armada berikut:\n\n• pemesan: ${currentUser.name} (${currentUser.email})\n• Nama Mobil : ${car.name}\n• Tipe/Kapasitas : ${car.type} / ${car.capacity}\n• Transmisi : ${car.transmission}\n• Harga Sewa : IDR ${new Intl.NumberFormat('id-ID').format(car.price)}/Hari\n\nApakah unit ini tersedia untuk dijadwalkan? Terima kasih!`;
    window.open(`https://wa.me/6281262772091?text=${encodeURIComponent(message)}`, '_blank');
  };

  // Handler Tombol Book Now
  const handleBookNow = (car) => {
    if (!user) {
      setSelectedCarToBook(car); // Simpan unit pilihan
      setIsAuthModalOpen(true);  // Munculkan Modal Login
    } else {
      processBookingWA(car, user);
    }
  };

  const scrollToSection = (elementRef) => {
    // Kembalikan ke halaman home dulu jika user sedang berada di halaman 'our-cars'
    setActivePage('home');
    setTimeout(() => {
      elementRef.current?.scrollIntoView({ behavior: 'smooth' });
    }, 100);
  };

  const promoTexts = [
    {
      title: "PAJERO SPORT & 4x4 TRITON",
      desc: '"Jelajahi keindahan kota tanpa batas! Pesan mobil pilihanmu sekarang."',
      note: "*Siap liburan dengan nyaman? Booking mobilmu hari ini!"
    },
    {
      title: "HONDA BRIO & TOYOTA RAIZE",
      desc: '"Sewa hemat, keliling kota makin lincah dan sat-set. Mulai 350rb/hari!"',
      note: "*Lepas kunci gampang, syarat gak ribet!"
    },
    {
      title: "AVANZA CVT & INNOVA REBORN",
      desc: '"Armada keluarga luas dan nyaman. Perjalanan jauh gak berasa lelah."',
      note: "*Tersedia pilihan sewa harian, bulanan + Driver profesional!"
    }
  ];

  useEffect(() => {
    fetch(`${API_BASE_URL}/api/cars`)
      .then((response) => response.json())
      .then((res) => {
        if (res.success) {
          setCarData(res.data);
        }
        setLoading(false);
      })
      .catch((error) => {
        console.error("Gagal mengambil data dari API:", error);
        setLoading(false);
      });
  }, []);

  useEffect(() => {
    const interval = setInterval(() => {
      setIsAnimating(true);
      setTimeout(() => {
        setCurrentTextIndex((prevIndex) => (prevIndex + 1) % promoTexts.length);
        setIsAnimating(false);
      }, 400);
    }, 4500);

    return () => clearInterval(interval);
  }, []);

  return (
    <div className="bg-[#07111e] font-sans min-h-screen flex flex-col justify-between text-gray-300">

      <div>
        {/* ================= NAVBAR ================= */}
    <nav className="bg-navyDark text-white px-6 py-4 flex justify-between items-center text-sm font-semibold tracking-wider border-b border-gray-800 sticky top-0 z-50 shadow-md">
  {/* Left: Navigation Links */}
  <div className="flex space-x-6 items-center">
    <button 
      onClick={() => setActivePage('home')} 
      className={`transition cursor-pointer ${activePage === 'home' ? 'text-yellowNeon font-bold' : 'hover:text-yellowNeon'}`}
    >
      HOME
    </button>
    <button 
      onClick={() => setActivePage('our-cars')} 
      className={`transition cursor-pointer ${activePage === 'our-cars' ? 'text-yellowNeon font-bold' : 'hover:text-yellowNeon'}`}
    >
      OUR CARS
    </button>
    <button 
      onClick={() => scrollToSection(locationsRef)} 
      className="hover:text-yellowNeon transition cursor-pointer"
    >
      OUR LOCATIONS
    </button>
    <button 
      onClick={() => scrollToSection(contactRef)} 
      className="hover:text-yellowNeon transition cursor-pointer"
    >
      CONTACT US
    </button>
  </div>

  {/* Right: User Profile Menu */}
<div className="flex items-center space-x-4">
  {user ? (
    <div className="relative group">
      <button className="flex items-center space-x-3 p-1.5 rounded-full hover:bg-gray-800 transition cursor-pointer">
        <div className="w-8 h-8 rounded-full bg-yellowNeon text-navyDark flex items-center justify-center font-bold text-xs uppercase shadow">
          {user.name.substring(0, 2)}
        </div>
        <span className="text-xs text-gray-200 group-hover:text-yellowNeon transition font-medium hidden sm:inline-block">
          {user.name}
        </span>
      </button>

      {/* Dropdown Menu */}
      <div className="absolute right-0 mt-2 w-48 bg-navyDark border border-gray-800 rounded-lg shadow-xl opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none group-hover:pointer-events-auto py-2 z-50">
        <div className="px-4 py-2 border-b border-gray-800">
          <p className="text-xs font-bold text-white">{user.name}</p>
          <p className="text-[10px] text-gray-400 truncate">{user.email}</p>
        </div>
        <button 
          onClick={handleLogout} 
          className="w-full text-left px-4 py-2 text-xs text-red-400 hover:bg-red-500/10 transition flex items-center space-x-2"
        >
          <span>Logout</span>
        </button>
      </div>
    </div>
  ) : (
    <button
      onClick={() => setIsAuthModalOpen(true)}
      className="bg-yellowNeon text-navyDark text-xs font-extrabold px-4 py-2 rounded-lg hover:bg-white transition"
    >
      LOGIN / REGISTER
    </button>
  )}
</div>
</nav>

        {/* HEADER LOGO DAN HERO BANNER (Tetap muncul di atas semua halaman agar estetik) */}
        <header className="bg-[#07111e] shadow-sm">
          <div className="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div className="bg-[#07111e] px-4 py-2 rounded">
              <img src={logo} alt="Logo Rental" className="h-18 w-auto" />
            </div>
            <div className="flex space-x-6 text-sm text-gray-300">
              <div className="flex items-center space-x-2">
                <span className="text-2xl">📞</span>
                <div>
                  <p className="text-xs text-gray-400">Hotline</p>
                  <p className="font-bold text-white">+6281262772091</p>
                </div>
              </div>
              <div className="flex items-center space-x-2">
                <span className="text-2xl text-green-500">💬</span>
                <div>
                  <p className="text-xs text-gray-400">WhatsApp</p>
                  <p className="font-bold text-green-600">+6281262772091</p>
                </div>
              </div>
            </div>
          </div>

          {/* AUTO TEXT SLIDER */}
          <div className="bg-navyDark text-white text-center py-14 px-4 relative overflow-hidden border-y border-gray-800 min-h-[200px] flex flex-col justify-center">
            <div className={`transition-all duration-500 ease-in-out transform ${isAnimating ? 'opacity-0 -translate-y-4 scale-95' : 'opacity-100 translate-y-0 scale-100'}`}>
              <h1 className="text-3xl md:text-4xl font-black mb-2 tracking-wide uppercase">{promoTexts[currentTextIndex].title}</h1>
              <p className="text-yellowNeon text-lg font-bold italic tracking-wide max-w-3xl mx-auto drop-shadow-sm">{promoTexts[currentTextIndex].desc}</p>
              <p className="text-xs text-gray-400 mt-3 font-medium tracking-wider">{promoTexts[currentTextIndex].note}</p>
            </div>
            <div className="flex justify-center space-x-2 mt-6">
              {promoTexts.map((_, idx) => (
                <div key={idx} className={`h-1.5 rounded-full transition-all duration-300 ${idx === currentTextIndex ? 'w-6 bg-yellowNeon' : 'w-2 bg-gray-600'}`} />
              ))}
            </div>
          </div>
        </header>

        {/* ================= KONTEN DINAMIS BERDASARKAN HALAMAN YANG AKTIF ================= */}
        {activePage === 'our-cars' ? (

          /* JIKA HALAMAN 'OUR CARS' AKTIF -> Tampilkan file komponen Cars.jsx */
          <Cars carData={carData} loading={loading} />

        ) : ( 

          /* JIKA HALAMAN 'HOME' AKTIF -> Tampilkan Layout Utama + Dibatasi 4 Mobil */
          <div ref={homeRef}>
            {/* SECTION KEUNGGULAN */}
            <section className="max-w-7xl mx-auto px-6 pt-16">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div className="bg-navyDark/40 border border-gray-800 p-6 rounded-2xl flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-4 hover:border-yellowNeon/30 transition duration-300">
                  <div className="text-3xl p-3 bg-yellowNeon/10 text-yellowNeon rounded-xl font-bold">⏰</div>
                  <div>
                    <h3 className="text-white font-bold text-lg mb-1 tracking-wide">Layanan 24/7 Nonstop</h3>
                    <p className="text-gray-400 text-xs leading-relaxed">Butuh sewa mendadak tengah malam? Tim CS kami selalu siap siaga.</p>
                  </div>
                </div>
                <div className="bg-navyDark/40 border border-gray-800 p-6 rounded-2xl flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-4 hover:border-yellowNeon/30 transition duration-300">
                  <div className="text-3xl p-3 bg-yellowNeon/10 text-yellowNeon rounded-xl font-bold">✨</div>
                  <div>
                    <h3 className="text-white font-bold text-lg mb-1 tracking-wide">Armada Bersih & Prima</h3>
                    <p className="text-gray-400 text-xs leading-relaxed">Seluruh unit mobil dijamin dalam kondisi wangi, kinclong, dan rutin servis berkala.</p>
                  </div>
                </div>
                <div className="bg-navyDark/40 border border-gray-800 p-6 rounded-2xl flex flex-col md:flex-row items-center md:items-start space-y-4 md:space-y-0 md:space-x-4 hover:border-yellowNeon/30 transition duration-300">
                  <div className="text-3xl p-3 bg-yellowNeon/10 text-yellowNeon rounded-xl font-bold">🤝</div>
                  <div>
                    <h3 className="text-white font-bold text-lg mb-1 tracking-wide">Driver Profesional</h3>
                    <p className="text-gray-400 text-xs leading-relaxed">Driver kami ramah, berpengalaman, hafal rute jalanan, dan selalu utamakan keselamatan.</p>
                  </div>
                </div>
              </div>
            </section>

            {/* MAIN CARDS DI HOME (DILIMIT HANYA 4 UNIT TERATAS) */}
            <main className="max-w-7xl mx-auto px-6 py-20">
              <h2 className="text-2xl font-extrabold text-center text-yellowNeon mb-10 uppercase tracking-widest border-b-2 border-yellowNeon/20 pb-3 max-w-sm mx-auto">
                Pilihan Armada Terbaik
              </h2>

              {loading ? (
                <div className="text-center text-gray-400 font-semibold py-12">Sedang memuat data armada mobil...</div>
              ) : carData.length === 0 ? (
                <div className="text-center text-gray-500 py-12">Belum ada data mobil yang tersedia di database admin.</div>
              ) : (
                /* .slice(0, 4) digunakan untuk membatasi tampilan hanya 4 data teratas di Home */
                <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                  {carData.slice(0, 4).map((car) => (
                    <div key={car.id} className="border-2 border-navyDark rounded-xl overflow-hidden flex flex-col shadow-lg hover:border-gray-700 transition duration-300">
                      <div className="bg-navyDark text-white p-6 text-center grow flex flex-col justify-between">
                        <div>
                          <h3 className="font-bold text-xl tracking-wide uppercase mb-4">{car.name}</h3>
                          <div className="h-28 flex items-center justify-center my-2 p-2 bg-slate-800/30 rounded-lg border border-gray-800/50">
                            {car.icon ? (
                              <img src={`${API_BASE_URL}/storage/cars/${car.icon}`} alt={car.name} className="max-h-full max-w-full object-contain drop-shadow-md" />
                            ) : (
                              <span className="text-5xl">🚗</span>
                            )}
                          </div>
                        </div>
                        <div>
                          <p className="text-xs uppercase tracking-widest text-gray-400 mt-2">Mulai Dari IDR</p>
                          <p className="text-2xl font-black text-yellowNeon">
                            {new Intl.NumberFormat('id-ID').format(car.price)} <span className="text-xs text-white">/Hari</span>
                          </p>
                          <button
  onClick={() => handleBookNow(car)}
  className="mt-4 w-full bg-yellowNeon text-navyDark font-extrabold py-2.5 px-4 rounded-lg hover:bg-white transition duration-300 text-xs tracking-wider shadow text-center block cursor-pointer"
>
  BOOK NOW &gt;&gt;
</button>
                        </div>
                      </div>
                      <div className="bg-yellowNeon text-navyDark p-5 text-sm space-y-2 font-semibold border-t border-navyDark">
                        <p className="font-black border-b border-navyDark/20 pb-1 text-xs">TIPE MOBIL : {car.type}</p>
                        <p>• {car.capacity}</p>
                        <p>• {car.transmission}</p>
                        <p>• SEWA BULANAN : {car.monthly_price}</p>
                        <p className="font-black pt-1 text-xs text-red-700 border-t border-navyDark/10">
                          Mobil + Driver : IDR {new Intl.NumberFormat('id-ID').format(car.driver_price)}/Hari
                        </p>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </main>

            {/* SECTION LOCATIONS */}
            <section ref={locationsRef} className="max-w-7xl mx-auto px-6 py-20 border-t border-gray-900 scroll-mt-16">
              <h2 className="text-2xl font-extrabold text-center text-yellowNeon mb-10 uppercase tracking-widest border-b-2 border-yellowNeon/20 pb-3 max-w-sm mx-auto">
                Our Locations
              </h2>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div className="space-y-4">
                  <h3 className="text-xl font-bold text-white tracking-wide">📍 Kantor Pusat Operasional</h3>
                  <p className="text-gray-400 text-sm leading-relaxed">
                    Kami melayani area strategis perkotaan dan bandara. Kamu bisa langsung datang ke pool utama kami untuk serah terima unit secara cepat.
                  </p>
                  <div className="p-4 bg-navyDark/60 border border-gray-800 rounded-xl space-y-2 text-xs">
                    <p><span className="text-yellowNeon font-bold">Pool Utama:</span> Jl. Sultan Serdang No. 88, Area Bandara Internasional</p>
                    <p><span className="text-yellowNeon font-bold">Cabang Kota:</span> Jl. Diponegoro No. 12 (Samping Pusat Perbelanjaan)</p>
                    <p><span className="text-yellowNeon font-bold">Layanan Antar-Jemput:</span> Gratis untuk area Bandara & Hotel bintang terdekat!</p>
                  </div>
                </div>
                {/* Box Google Maps Asli */}
                <div className="w-full h-64 bg-slate-800/40 border-2 border-gray-800 rounded-2xl relative overflow-hidden group hover:border-yellowNeon/20 transition">
                  <iframe
                    src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d9622.7834187318!2d98.64349057068182!3d3.528088953380661!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sid!2sid!4v1780996635637!5m2!1sid!2sid"
                    width="100%"
                    height="100%"
                    style={{ border: 0 }}
                    allowFullScreen=""
                    loading="lazy"
                    referrerPolicy="no-referrer-when-downgrade"
                    title="Lokasi Rental Mobil"
                    className="opacity-80 group-hover:opacity-100 transition duration-300"
                  />
                </div>
              </div>
            </section>

            {/* SECTION CONTACT */}
            <section ref={contactRef} className="max-w-4xl mx-auto px-6 py-20 border-t border-gray-900 text-center scroll-mt-16 mb-12">
              <h2 className="text-2xl font-extrabold text-center text-yellowNeon mb-6 uppercase tracking-widest border-b-2 border-yellowNeon/20 pb-3 max-w-xs mx-auto">
                Contact Us
              </h2>
              <p className="text-gray-400 text-sm max-w-2xl mx-auto mb-8">
                Punya pertanyaan mengenai harga sewa bulanan atau syarat lepas kunci? Jangan ragu hubungi tim sales kami!
              </p>
              <div className="inline-flex flex-col sm:flex-row space-y-4 sm:space-y-0 sm:space-x-4">
                <a
                  href={`https://wa.me/6281262772091?text=${encodeURIComponent(
                    "Halo Admin Rental Mobil! 👋\n\nSaya tertarik untuk menyewa mobil. Boleh minta informasi ketersediaan unit dan persyaratannya?\n\nTerima kasih!"
                  )}`}
                  target="_blank"
                  rel="noreferrer"
                  className="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl flex items-center justify-center space-x-2 transition shadow-lg text-sm tracking-wide"
                >
                  <span>💬 Hubungi Via WhatsApp Fast Respon</span>
                </a>
              </div>
            </section>
          </div>
        )}
      </div>

      {/* ================= FOOTER SECTION ================= */}
      <footer className="bg-navyDark text-gray-400 text-xs py-8 px-6 border-t border-gray-800 w-full">
        <div className="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
          <div>
            <p className="font-bold text-sm text-white mb-1"> VJ RENTAL MOBIL </p>
            <p>© 2026 Seluruh Hak Cipta Dilindungi.</p>
          </div>
          <div className="flex space-x-6 font-medium">
            <button onClick={() => setActivePage('home')} className="hover:text-yellowNeon transition cursor-pointer">Syarat & Ketentuan</button>
            <button onClick={() => setActivePage('home')} className="hover:text-yellowNeon transition cursor-pointer">Kebijakan Privasi</button>
            <button onClick={() => { setActivePage('home'); setTimeout(() => contactRef.current?.scrollIntoView({ behavior: 'smooth' }), 100); }} className="hover:text-yellowNeon transition cursor-pointer">Bantuan</button>
          </div>
          <div>
            <p className="text-right text-gray-500">Powered by <span className="text-yellowNeon font-bold">Hero</span> <span className="text-cyan-400 font-bold">Agripa</span></p>
          </div>
        </div>
      </footer>
      {/* Modal Auth */}
      <AuthModal
        isOpen={isAuthModalOpen}
        onClose={() => setIsAuthModalOpen(false)}
        onLoginSuccess={handleLoginSuccess}
      />

    </div>
    
  );
}

export default App;