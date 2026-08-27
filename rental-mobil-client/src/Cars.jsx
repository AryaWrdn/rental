import React from 'react';

function Cars({ carData, loading, onBookNow }) {
    const API_BASE_URL = "http://127.0.0.1:8000";

    return (
        <main className="max-w-7xl mx-auto px-6 py-12">
            <h2 className="text-2xl font-extrabold text-center text-yellowNeon mb-10 uppercase tracking-widest border-b-2 border-yellowNeon/20 pb-3 max-w-sm mx-auto">
                Semua Koleksi Armada
            </h2>

            {loading ? (
                <div className="text-center text-gray-400 font-semibold py-12">
                    Sedang memuat seluruh armada mobil...
                </div>
            ) : carData.length === 0 ? (
                <div className="text-center text-gray-500 py-12">
                    Belum ada data mobil yang tersedia di database admin.
                </div>
            ) : (
                <div className="grid grid-cols-1 md:grid-cols-4 gap-6">
                    {carData.map((car) => (
                        <div key={car.id} className="border-2 border-navyDark rounded-xl overflow-hidden flex flex-col shadow-lg hover:border-gray-700 transition duration-300">
                            
                            {/* Bagian Atas: Navy Blue */}
                            <div className="bg-navyDark text-white p-6 text-center grow flex flex-col justify-between">
                                <div>
                                    <h3 className="font-bold text-xl tracking-wide uppercase mb-4">{car.name}</h3>
                                    <div className="h-28 flex items-center justify-center my-2 p-2 bg-slate-800/30 rounded-lg border border-gray-800/50">
                                        {car.icon ? (
                                            <img
                                                src={`${API_BASE_URL}/storage/cars/${car.icon}`}
                                                alt={car.name}
                                                className="max-h-full max-w-full object-contain drop-shadow-md"
                                            />
                                        ) : (
                                            <span className="text-5xl">🚗</span>
                                        )}
                                    </div>
                                </div>
                                
                                <div>
                                    <p className="text-xs uppercase text-gray-400 mt-2">Mulai Dari IDR</p>
                                    <p className="text-2xl font-black text-yellowNeon">
                                        {new Intl.NumberFormat('id-ID').format(car.price || car.driver_price || 0)} <span className="text-xs text-white">/Hari</span>
                                    </p>

                                    {/* Tombol Book Now / Status Sedang Dipakai */}
                                    {car.status === 'disewa' ? (
                                        <button
                                            disabled
                                            className="mt-4 w-full bg-red-600/20 border border-red-500/40 text-red-400 font-extrabold py-2.5 px-4 rounded-lg text-xs tracking-wider shadow text-center block cursor-not-allowed opacity-90"
                                        >
                                            SEDANG DIPAKAI ⛔
                                        </button>
                                    ) : (
                                        <button
                                            onClick={() => onBookNow(car)}
                                            className="mt-4 w-full bg-yellowNeon text-navyDark font-extrabold py-2.5 px-4 rounded-lg hover:bg-white transition duration-300 text-xs tracking-wider shadow text-center block cursor-pointer"
                                        >
                                            BOOK NOW &gt;&gt;
                                        </button>
                                    )}
                                </div>
                            </div>

                            {/* Bagian Bawah: Kuning Stabilo */}
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
    );
}

export default Cars;