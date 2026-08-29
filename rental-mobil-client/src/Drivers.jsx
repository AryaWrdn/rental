export default function Drivers({ driverData }) {
  return (
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
                  
                  </div>
                </div>

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
  );
}