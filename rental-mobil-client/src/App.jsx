import { useState, useEffect, useRef } from 'react';
import logo from './assets/logo-removebg-preview.png';
import Cars from './Cars';
import AuthModal from './AuthModal'; 
import BookingModal from './BookingModal';

function App() {
  const [carData, setCarData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [currentTextIndex, setCurrentTextIndex] = useState(0);
  const [isAnimating, setIsAnimating] = useState(false);
  const [activePage, setActivePage] = useState('home');
  const [driverData, setDriverData] = useState([]);
  
  // State Modal Booking
  const [isBookingModalOpen, setIsBookingModalOpen] = useState(false);
  const [selectedCarToBook, setSelectedCarToBook] = useState(null);

  const API_BASE_URL = "http://127.0.0.1:8000";

  const homeRef = useRef(null);
  const locationsRef = useRef(null);
  const contactRef = useRef(null);
  
  const [user, setUser] = useState(() => {
    const savedUser = localStorage.getItem('user');
    return savedUser ? JSON.parse(savedUser) : null;
  });

  const [isAuthModalOpen, setIsAuthModalOpen] = useState(false);

  // Handler Tombol Book Now
  const handleBookNow = (car) => {
    if (!user) {
      setSelectedCarToBook(car);
      setIsAuthModalOpen(true);
    } else {
      setSelectedCarToBook(car);
      setIsBookingModalOpen(true);
    }
  };

  const handleLoginSuccess = (userData) => {
    setUser(userData);
    localStorage.setItem('user', JSON.stringify(userData));
    if (selectedCarToBook) {
      setIsBookingModalOpen(true);
    }
  };

  const handleLogout = () => {
    setUser(null);
    localStorage.removeItem('user');
  };

  const scrollToSection = (elementRef) => {
    setActivePage('home');
    setTimeout(() => elementRef.current?.scrollIntoView({ behavior: 'smooth' }), 100);
  };

  const promoTexts = [
    { title: "", desc: '"Jelajahi keindahan kota tanpa batas! Pesan mobil pilihanmu sekarang."', note: "*Siap liburan dengan nyaman? Booking mobilmu hari ini!" },
    { title: "", desc: '"Sewa hemat, keliling kota makin lincah dan sat-set. Mulai 350rb/hari!"', note: "*Lepas kunci gampang, syarat gak ribet!" },
    { title: "", desc: '"Armada keluarga luas dan nyaman. Perjalanan jauh gak berasa lelah."', note: "*Tersedia pilihan sewa harian, bulanan + Driver profesional!" }
  ];

  // Fetch Data Mobil & Supir
  useEffect(() => {
    // Fetch data mobil
    fetch(`${API_BASE_URL}/api/cars`)
      .then((res) => res.json())
      .then((res) => {
        if (res.success) setCarData(res.data);
        setLoading(false);
      })
      .catch((err) => {
        console.error("Gagal mengambil data mobil:", err);
        setLoading(false);
      });

    // Fetch data supir
    fetch(`${API_BASE_URL}/api/drivers`)
      .then((res) => res.json())
      .then((res) => {
        if (res.success) setDriverData(res.data);
      })
      .catch((err) => {
        console.error("Gagal mengambil data supir:", err);
      });
  }, []);

  // Animasi Banner Teks
  useEffect(() => {
    const interval = setInterval(() => {
      setIsAnimating(true);
      setTimeout(() => {
        setCurrentTextIndex((prev) => (prev + 1) % promoTexts.length);
        setIsAnimating(false);
      }, 400);
    }, 4500);
    return () => clearInterval(interval);
  }, []);

  return (
    <div className="bg-[#07111e] font-sans min-h-screen flex flex-col justify-between text-gray-300">
      <div>
        {/* NAVBAR */}
        <nav className="bg-navyDark text-white px-6 py-4 flex justify-between items-center text-sm font-semibold tracking-wider border-b border-gray-800 sticky top-0 z-50 shadow-md">
          <div className="flex space-x-6 items-center">
            <button onClick={() => setActivePage('home')} className={`transition cursor-pointer ${activePage === 'home' ? 'text-yellowNeon font-bold' : 'hover:text-yellowNeon'}`}>HOME</button>
            <button onClick={() => setActivePage('our-cars')} className={`transition cursor-pointer ${activePage === 'our-cars' ? 'text-yellowNeon font-bold' : 'hover:text-yellowNeon'}`}>OUR CARS</button>
            <button onClick={() => setActivePage('drivers')} className={`transition cursor-pointer ${activePage === 'drivers' ? 'text-yellowNeon font-bold' : 'hover:text-yellowNeon'}`}>OUR DRIVERS</button>
            <button onClick={() => scrollToSection(locationsRef)} className="hover:text-yellowNeon transition cursor-pointer">OUR LOCATIONS</button>
            <button onClick={() => scrollToSection(contactRef)} className="hover:text-yellowNeon transition cursor-pointer">CONTACT US</button>
            </div>

          <div className="flex items-center space-x-4">
            
            {user ? (
              <div className="relative group">
                <button className="flex items-center space-x-3 p-1.5 rounded-full hover:bg-gray-800 transition cursor-pointer">
                  <div className="w-8 h-8 rounded-full bg-yellowNeon text-navyDark flex items-center justify-center font-bold text-xs uppercase shadow">
                    {user.name.substring(0, 2)}
                  </div>
                  <span className="text-xs text-gray-200 group-hover:text-yellowNeon transition font-medium hidden sm:inline-block">{user.name}</span>
                </button>
                <div className="absolute right-0 mt-2 w-48 bg-navyDark border border-gray-800 rounded-lg shadow-xl opacity-0 group-hover:opacity-100 transition-all duration-200 pointer-events-none group-hover:pointer-events-auto py-2 z-50">
                  <div className="px-4 py-2 border-b border-gray-800">
                    <p className="text-xs font-bold text-white">{user.name}</p>
                    <p className="text-[10px] text-gray-400 truncate">{user.email}</p>
                  </div>
                  <button onClick={handleLogout} className="w-full text-left px-4 py-2 text-xs text-red-400 hover:bg-red-500/10 transition cursor-pointer">Logout</button>
                </div>
              </div>
            ) : (
              <button onClick={() => setIsAuthModalOpen(true)} className="bg-yellowNeon text-navyDark text-xs font-extrabold px-4 py-2 rounded-lg hover:bg-white transition cursor-pointer">LOGIN / REGISTER</button>
            )}
          </div>
        </nav>

        {/* HEADER & HERO BANNER */}
        <header className="bg-[#07111e] shadow-sm">
          <div className="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <div className="bg-[#07111e] px-4 py-2 rounded">
              <img src={logo} alt="Logo Rental" className="h-18 w-auto" />
            </div>
            <div className="flex space-x-6 text-sm text-gray-300">
              <div className="flex items-center space-x-2">
                <span className="text-2xl">📞</span>
                <div><p className="text-xs text-gray-400">Hotline</p><p className="font-bold text-white">+6281262772091</p></div>
              </div>
              <div className="flex items-center space-x-2">
                <span className="text-2xl text-green-500">💬</span>
                <div><p className="text-xs text-gray-400">WhatsApp</p><p className="font-bold text-green-600">+6281262772091</p></div>
              </div>
            </div>
          </div>

          <div className="bg-navyDark text-white text-center py-14 px-4 relative overflow-hidden border-y border-gray-800 min-h-[200px] flex flex-col justify-center">
            <div className={`transition-all duration-500 ease-in-out transform ${isAnimating ? 'opacity-0 -translate-y-4 scale-95' : 'opacity-100 translate-y-0 scale-100'}`}>
              <h1 className="text-3xl md:text-4xl font-black mb-2 tracking-wide uppercase">{promoTexts[currentTextIndex].title}</h1>
              <p className="text-yellowNeon text-lg font-bold italic tracking-wide max-w-3xl mx-auto">{promoTexts[currentTextIndex].desc}</p>
              <p className="text-xs text-gray-400 mt-3 font-medium">{promoTexts[currentTextIndex].note}</p>
            </div>
            <div className="flex justify-center space-x-2 mt-6">
              {promoTexts.map((_, idx) => (
                <div key={idx} className={`h-1.5 rounded-full transition-all duration-300 ${idx === currentTextIndex ? 'w-6 bg-yellowNeon' : 'w-2 bg-gray-600'}`} />
              ))}
            </div>
          </div>
        </header>

        {/* KONTEN HALAMAN */}
        {activePage === 'our-cars' ? (
          <Cars carData={carData} loading={loading} onBookNow={handleBookNow} />
        ) : activePage === 'drivers' ? (
          <main className="max-w-7xl mx-auto px-6 py-12">
            <h2 className="text-2xl font-extrabold text-center text-yellowNeon mb-10 uppercase tracking-widest border-b-2 border-yellowNeon/20 pb-3 max-w-sm mx-auto">
              Daftar Mitra Driver VJ
            </h2>

            {driverData.length === 0 ? (
              <div className="text-center text-gray-500 py-12">
                Belum ada data supir yang tersedia di database.
              </div>
            ) : (
              <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                {driverData.map((driver) => {
                  // Cek apakah supir sedang bertugas (case-insensitive)
                  const isBusy = driver.status && driver.status.toLowerCase() === 'bertugas';
                  
                  return (
                    <div key={driver.id} className="border-2 border-navyDark rounded-xl overflow-hidden flex flex-col shadow-lg hover:border-gray-700 transition duration-300">
                      
                      <div className="bg-navyDark text-white p-6 text-center grow flex flex-col justify-between">
                        <div>
                          <h3 className="font-bold text-xl tracking-wide uppercase mb-2">{driver.name}</h3>
                          <div className="h-28 flex flex-col items-center justify-center my-2 p-4 bg-slate-800/30 rounded-lg border border-gray-800/50 space-y-2">
                            <span className="text-3xl">👨‍✈️</span>
                            <span className="text-xs text-yellowNeon font-semibold">Driver Profesional</span>
                          </div>
                        </div>
                        
                        <div className="mt-4">
                          <p className="text-xs uppercase text-gray-400">Kontak WhatsApp</p>
                          <p className="text-sm font-bold text-white mt-0.5">{driver.phone}</p>
                          <a 
                            href={`https://wa.me/${driver.phone.replace(/[^0-9]/g, '')}?text=${encodeURIComponent(`Halo ${driver.name}, saya ingin memesan layanan supir Anda di VJ Rental Mobil.`)}`}
                            target="_blank" 
                            rel="noreferrer"
                            className="mt-3 w-full bg-green-600 hover:bg-green-700 text-white font-extrabold py-2 px-4 rounded-lg transition duration-300 text-xs tracking-wider shadow text-center block cursor-pointer"
                          >
                            CHAT DRIVER 💬
                          </a>
                        </div>
                      </div>

                      {/* Bagian Status Dinamis (Merah jika Bertugas, Hijau jika Tersedia) */}
                      <div className="bg-yellowNeon text-navyDark p-5 text-sm space-y-2 font-semibold border-t border-navyDark">
                        <div className="flex justify-between items-center border-b border-navyDark/20 pb-1">
                          <span className="text-xs uppercase font-black">STATUS :</span>
                          <span className={`text-[10px] px-2 py-0.5 rounded font-extrabold text-white ${isBusy ? 'bg-red-600' : 'bg-green-600'}`}>
                            {driver.status ? driver.status.toUpperCase() : 'TERSEDIA'}
                          </span>
                        </div>
                        <p>• Pengalaman: {driver.experience}</p>
                        <p className="font-black pt-1 text-xs text-red-700 border-t border-navyDark/10">
                          Mitra Resmi VJ Rental Mobil
                        </p>
                      </div>

                    </div>
                  );
                })}
              </div>
            )}
          </main>
        ) : (
          <div ref={homeRef}>
            {/* KEUNGGULAN */}
            <section className="max-w-7xl mx-auto px-6 pt-16">
              <div className="grid grid-cols-1 md:grid-cols-3 gap-8">
                {[
                  { icon: '⏰', title: 'Layanan 24/7 Nonstop', desc: 'Butuh sewa mendadak tengah malam? Tim CS kami selalu siap siaga.' },
                  { icon: '✨', title: 'Armada Bersih & Prima', desc: 'Seluruh unit mobil dijamin dalam kondisi wangi, kinclong, dan rutin servis berkala.' },
                  { icon: '🤝', title: 'Driver Profesional', desc: 'Driver kami ramah, berpengalaman, hafal rute jalanan, dan selalu utamakan keselamatan.' }
                ].map((item, idx) => (
                  <div key={idx} className="bg-navyDark/40 border border-gray-800 p-6 rounded-2xl flex items-start space-x-4 hover:border-yellowNeon/35 transition">
                    <div className="text-3xl p-3 bg-yellowNeon/10 text-yellowNeon rounded-xl font-bold">{item.icon}</div>
                    <div><h3 className="text-white font-bold text-lg mb-1">{item.title}</h3><p className="text-gray-400 text-xs">{item.desc}</p></div>
                  </div>
                ))}
              </div>
            </section>

            {/* MAIN CARDS */}
            <main className="max-w-7xl mx-auto px-6 py-20">
              <h2 className="text-2xl font-extrabold text-center text-yellowNeon mb-10 uppercase tracking-widest border-b-2 border-yellowNeon/20 pb-3 max-w-sm mx-auto">Pilihan Armada Terbaik</h2>

              {loading ? (
                <div className="text-center text-gray-400 font-semibold py-12">Memuat data armada...</div>
              ) : carData.length === 0 ? (
                <div className="text-center text-gray-500 py-12">Belum ada data mobil.</div>
              ) : (
                <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                  {carData.slice(0, 4).map((car) => (
                    <div key={car.id} className="border-2 border-navyDark rounded-xl overflow-hidden flex flex-col shadow-lg hover:border-gray-700 transition">
                      <div className="bg-navyDark text-white p-6 text-center grow flex flex-col justify-between">
                        <div>
                          <h3 className="font-bold text-xl tracking-wide uppercase mb-4">{car.name}</h3>
                          <div className="h-28 flex items-center justify-center my-2 p-2 bg-slate-800/30 rounded-lg border border-gray-800/50">
                            {car.icon ? <img src={`${API_BASE_URL}/storage/cars/${car.icon}`} alt={car.name} className="max-h-full max-w-full object-contain" /> : <span className="text-5xl">🚗</span>}
                          </div>
                        </div>
                        <div>
                          <p className="text-xs uppercase text-gray-400 mt-2">Mulai Dari IDR</p>
                          <p className="text-2xl font-black text-yellowNeon">{new Intl.NumberFormat('id-ID').format(car.price)} <span className="text-xs text-white">/Hari</span></p>
                          {car.status === 'disewa' ? (
                            <button disabled className="mt-4 w-full bg-red-600/20 border border-red-500/40 text-red-400 font-extrabold py-2.5 rounded-lg text-xs tracking-wider cursor-not-allowed">SEDANG DIPAKAI ⛔</button>
                          ) : (
                            <button onClick={() => handleBookNow(car)} className="mt-4 w-full bg-yellowNeon text-navyDark font-extrabold py-2.5 rounded-lg hover:bg-white transition text-xs tracking-wider cursor-pointer">BOOK NOW &gt;&gt;</button>
                          )}
                        </div>
                      </div>
                      <div className="bg-yellowNeon text-navyDark p-5 text-sm space-y-2 font-semibold border-t border-navyDark">
                        <p className="font-black border-b border-navyDark/20 pb-1 text-xs">TIPE : {car.type}</p>
                        <p>• {car.capacity}</p>
                        <p>• {car.transmission}</p>
                        <p>• BULANAN : {car.monthly_price}</p>
                        <p className="font-black pt-1 text-xs text-red-700 border-t border-navyDark/10">+ Driver: IDR {new Intl.NumberFormat('id-ID').format(car.driver_price)}/Hari</p>
                      </div>
                    </div>
                  ))}
                </div>
              )}
            </main>

            {/* LOCATIONS */}
            <section ref={locationsRef} className="max-w-7xl mx-auto px-6 py-20 border-t border-gray-900 scroll-mt-16">
              <h2 className="text-2xl font-extrabold text-center text-yellowNeon mb-10 uppercase tracking-widest border-b-2 border-yellowNeon/20 pb-3 max-w-sm mx-auto">Our Locations</h2>
              <div className="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                <div className="space-y-4">
                  <h3 className="text-xl font-bold text-white">📍 Kantor Pusat Operasional</h3>
                  <p className="text-gray-400 text-sm leading-relaxed">Melayani area strategis perkotaan dan bandara. Kunjungi pool utama untuk serah terima unit cepat.</p>
                  <div className="p-4 bg-navyDark/60 border border-gray-800 rounded-xl space-y-2 text-xs">
                    <p><span className="text-yellowNeon font-bold">Pool Utama:</span> Jl. Jamin Ginting No.131, Kwala Bekala, Kec. Medan Johor, Kota Medan, Sumatera Utara 20155</p>
                    <p><span className="text-yellowNeon font-bold">Layanan Antar-Jemput:</span> Gratis untuk area Bandara & Hotel bintang terdekat!</p>
                  </div>
                </div>
                
                <div className="w-full h-64 bg-slate-800/40 border-2 border-gray-800 rounded-2xl relative overflow-hidden group hover:border-yellowNeon/20 transition">
                  <iframe
                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3982.0463428935414!2d98.6300977!3d3.5186548!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x303130198031d451%3A0x6b7724219cc9d5e!2sJl.%20Jamin%20Ginting%20No.131%2C%20Kwala%20Bekala%2C%20Kec.%20Medan%20Johor%2C%20Kota%20Medan%2C%20Sumatera%20Utara%2020155!5e0!3m2!1sid!2sid!4v1710000000000!5m2!1sid!2sid"
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

            {/* CONTACT */}
            <section ref={contactRef} className="max-w-4xl mx-auto px-6 py-20 border-t border-gray-900 text-center scroll-mt-16 mb-12">
              <h2 className="text-2xl font-extrabold text-center text-yellowNeon mb-6 uppercase tracking-widest border-b-2 border-yellowNeon/20 pb-3 max-w-xs mx-auto">Contact Us</h2>
              <p className="text-gray-400 text-sm mb-8">Punya pertanyaan seputar harga bulanan atau syarat lepas kunci? Hubungi tim kami.</p>
              <a href={`https://wa.me/6281262772091?text=${encodeURIComponent("Halo Admin Rental Mobil! 👋\n\nI am interested in renting a car. Please let me know the availability.")}`} target="_blank" rel="noreferrer" className="inline-flex bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl items-center space-x-2 transition shadow-lg text-sm cursor-pointer">
                <span>💬 Hubungi Via WhatsApp Fast Respon</span>
              </a>
            </section>
          </div>
        )}
      </div>

      {/* FOOTER */}
      <footer className="bg-navyDark text-gray-400 text-xs py-8 px-6 border-t border-gray-800 w-full">
        <div className="max-w-7xl mx-auto flex flex-col md:flex-row justify-between items-center space-y-4 md:space-y-0">
          <div><p className="font-bold text-sm text-white mb-1">VJ RENTAL MOBIL</p><p>© 2026 Seluruh Hak Cipta Dilindungi.</p></div>
          <div className="flex space-x-6">
            <button onClick={() => setActivePage('home')} className="hover:text-yellowNeon transition cursor-pointer">Syarat & Ketentuan</button>
            <button onClick={() => setActivePage('home')} className="hover:text-yellowNeon transition cursor-pointer">Kebijakan Privasi</button>
          </div>
          <div><p className="text-right text-gray-500">Powered by <span className="text-yellowNeon font-bold">Hero</span> <span className="text-cyan-400 font-bold">Agripa</span></p></div>
        </div>
      </footer>

      <AuthModal isOpen={isAuthModalOpen} onClose={() => setIsAuthModalOpen(false)} onLoginSuccess={handleLoginSuccess} />
      
      <BookingModal
        isOpen={isBookingModalOpen}
        onClose={() => setIsBookingModalOpen(false)}
        car={selectedCarToBook}
        user={user}
        API_BASE_URL={API_BASE_URL}
      />
    </div>
  );
}

export default App;