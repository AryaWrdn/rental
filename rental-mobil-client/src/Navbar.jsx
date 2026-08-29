import { useState, useEffect } from 'react';

export default function Navbar({ activePage, setActivePage, scrollToSection, locationsRef, contactRef, user, handleLogout, setIsAuthModalOpen }) {
  const [isDropdownOpen, setIsDropdownOpen] = useState(false);

  // Efek untuk mendeteksi klik di luar komponen dropdown
  useEffect(() => {
    const handleClickOutside = (event) => {
      // Jika dropdown terbuka dan yang diklik berada di luar elemen dropdown, maka tutup
      if (!event.target.closest('#profile-dropdown-container')) {
        setIsDropdownOpen(false);
      }
    };

    document.addEventListener('click', handleClickOutside);
    return () => {
      document.removeEventListener('click', handleClickOutside);
    };
  }, []);

  return (
    <nav className="bg-navyDark text-white px-6 py-4 flex justify-between items-center text-sm font-semibold tracking-wider border-b border-gray-800 sticky top-0 z-50 shadow-md">
      <div className="flex space-x-6 items-center">
        <button onClick={() => setActivePage('home')} className={`transition cursor-pointer ${activePage === 'home' ? 'text-yellowNeon font-bold' : 'hover:text-yellowNeon'}`}>HOME</button>
        <button onClick={() => setActivePage('our-cars')} className={`transition cursor-pointer ${activePage === 'our-cars' ? 'text-yellowNeon font-bold' : 'hover:text-yellowNeon'}`}>OUR CARS</button>
        <button onClick={() => setActivePage('drivers')} className={`transition cursor-pointer ${activePage === 'drivers' ? 'text-drivers' : 'hover:text-yellowNeon'}`}>OUR DRIVERS</button>
        <button onClick={() => scrollToSection(locationsRef)} className="hover:text-yellowNeon transition cursor-pointer">OUR LOCATIONS</button>
        <button onClick={() => scrollToSection(contactRef)} className="hover:text-yellowNeon transition cursor-pointer">CONTACT US</button>
      </div>

      <div className="flex items-center space-x-4">
        {user ? (
          /* Bungkus dengan ID agar terdeteksi oleh event click outside */
          <div id="profile-dropdown-container" className="relative">
            <button 
              onClick={() => setIsDropdownOpen(!isDropdownOpen)} 
              className="flex items-center space-x-3 p-1.5 rounded-full hover:bg-gray-800 transition cursor-pointer focus:outline-none"
            >
              <div className="w-8 h-8 rounded-full bg-yellowNeon text-navyDark flex items-center justify-center font-bold text-xs uppercase shadow">
                {user.name.substring(0, 2)}
              </div>
              <span className="text-xs text-gray-200 hover:text-yellowNeon transition font-medium hidden sm:inline-block">{user.name}</span>
            </button>

            {isDropdownOpen && (
              <div className="absolute right-0 mt-2 w-48 bg-navyDark border border-gray-800 rounded-lg shadow-xl py-2 z-50">
                <div className="px-4 py-2 border-b border-gray-800">
                  <p className="text-xs font-bold text-white">{user.name}</p>
                  <p className="text-[10px] text-gray-400 truncate">{user.username || user.email}</p>
                </div>
                <button 
                  onClick={() => { setIsDropdownOpen(false); handleLogout(); }} 
                  className="w-full text-left px-4 py-2 text-xs text-red-400 hover:bg-red-500/10 transition cursor-pointer"
                >
                  Logout
                </button>
              </div>
            )}
          </div>
        ) : (
          <button onClick={() => setIsAuthModalOpen(true)} className="bg-yellowNeon text-navyDark text-xs font-extrabold px-4 py-2 rounded-lg hover:bg-white transition cursor-pointer">LOGIN / REGISTER</button>
        )}
      </div>
    </nav>
  );
}