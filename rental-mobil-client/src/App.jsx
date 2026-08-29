import { useState, useEffect, useRef } from 'react';
import logo from './assets/logo-removebg-preview.png';
import Cars from './Cars';
import Drivers from './Drivers'; // Pastikan diimport jika dibuat terpisah
import AuthModal from './AuthModal'; 
import BookingModal from './BookingModal';
import Navbar from './Navbar';
import HeaderBanner from './HeaderBanner';
import Footer from './Footer';

function App() {
  const [carData, setCarData] = useState([]);
  const [loading, setLoading] = useState(true);
  const [currentTextIndex, setCurrentTextIndex] = useState(0);
  const [isAnimating, setIsAnimating] = useState(false);
  const [activePage, setActivePage] = useState('home');
  const [driverData, setDriverData] = useState([]);
  
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

  useEffect(() => {
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

    fetch(`${API_BASE_URL}/api/drivers`)
      .then((res) => res.json())
      .then((res) => {
        if (res.success) setDriverData(res.data);
      })
      .catch((err) => {
        console.error("Gagal mengambil data supir:", err);
      });
  }, []);

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
        <Navbar 
          activePage={activePage} 
          setActivePage={setActivePage} 
          scrollToSection={scrollToSection} 
          locationsRef={locationsRef} 
          contactRef={contactRef} 
          user={user} 
          handleLogout={handleLogout} 
          setIsAuthModalOpen={setIsAuthModalOpen} 
        />

        <HeaderBanner 
          logo={logo} 
          promoTexts={promoTexts} 
          currentTextIndex={currentTextIndex} 
          isAnimating={isAnimating} 
        />

        {activePage === 'our-cars' ? (
          <Cars carData={carData} loading={loading} onBookNow={handleBookNow} />
        ) : activePage === 'drivers' ? (
          <Drivers driverData={driverData} />
        ) : (
          <div ref={homeRef}>
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

            <main className="max-w-7xl mx-auto px-6 py-20">
              <h2 className="text-2xl font-extrabold text-center text-yellowNeon mb-10 uppercase tracking-widest border-b-2 border-yellowNeon/20 pb-3 max-w-sm mx-auto">Pilihan Armada Terbaik</h2>

              {loading ? (
                <div className="text-center text-gray-400 font-semibold py-12">Memuat data armada...</div>
              ) : carData.length === 0 ? (
                <div className="text-center text-gray-500 py-12">Belum ada data mobil.</div>
              ) : (
                <>
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

                  {/* Tombol Lihat Lebih Banyak */}
                  <div className="text-center mt-12">
                    <button
                      onClick={() => {
                        setActivePage('our-cars');
                        window.scrollTo({ top: 0, behavior: 'smooth' });
                      }}
                      className="inline-block bg-yellowNeon text-navyDark font-extrabold px-8 py-3 rounded-xl hover:bg-white transition duration-300 text-xs tracking-widest uppercase shadow-lg cursor-pointer"
                    >
                      Lihat Lebih Banyak &gt;&gt;
                    </button>
                  </div>
                </>
              )}
            </main>

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

      <Footer setActivePage={setActivePage} />

      <AuthModal isOpen={isAuthModalOpen} onClose={() => setIsAuthModalOpen(false)} onLoginSuccess={handleLoginSuccess} />
      
      {isBookingModalOpen && (
        <BookingModal
          isOpen={isBookingModalOpen}
          onClose={() => {
            setIsBookingModalOpen(false); // Menutup modal
            setActivePage('home');        // Mengembalikan halaman utama ke home
          }}
          car={selectedCarToBook}
          user={user}
          API_BASE_URL={API_BASE_URL}
          setActivePage={setActivePage}
        />
      )}
    </div>
  );
}

export default App;