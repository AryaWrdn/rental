export default function HeaderBanner({ logo, promoTexts, currentTextIndex, isAnimating }) {
  return (
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
  );
}