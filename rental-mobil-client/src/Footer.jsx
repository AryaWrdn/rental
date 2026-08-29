export default function Footer({ setActivePage }) {
  return (
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
  );
}