<?php

$HeaderMasterMhsw = array ( // MSMHS
  array("KDPTIMSMHS", "C", 6),  // Kode Perguruan Tinggi
	array("KDPSTMSMHS", "C", 5),  // Kode Program Studi
	array("KDJENMSMHS", "C", 1),  // Kode Jenjang Studi
	array("NIMHSMSMHS", "C", 15), // NIM
	array("NMMHSMSMHS", "C", 30), // Nama
	array("TPLHRMSMHS", "C", 20), // Tempat Lahir
	array("TGLHRMSMHS", "D"),     // Tanggal Lahir
	array("KDJEKMSMHS", "C", 1),  //  Jenis Kelamin
	array("TAHUNMSMHS", "C", 4),	// Tahun masuk
	array("SMAWLMSMHS", "C", 5),	// Semester awal terdaftar
	array("BTSTUMSMHS", "C", 5),	// Batas Studi
	array("ASSMAMSMHS", "C", 2),	// Kode Propinsi Pendidikan terakhir
	array("TGMSKMSMHS", "D"),			// Tanggal Masuk
	array("TGLLSMSMHS", "D"),			// Tanggal Lulus
	array("STMHSMSMHS", "C", 1),	// Kode Status Mhsw
	array("STPIDMSMHS", "C", 1),	// Kode Status Awal (Baru/Pindahan) 
	array("SKSDIMSMHS", "N", 3, 0),	// Jumlah SKS Pindahan
	array("ASNIMMSMHS", "C", 15),	// NIM asal perguruan tinggi (Pindahan)
	array("ASPTIMSMHS", "C", 6),  // Kode Perguruan tinggi sebelumnya (Pindahan)
	array("ASJENMSMHS", "C", 1),	// Kode Jenjang studi sebelumnya (Pindahan)
	array("ASPSTMSMHS", "C", 5), 	// Kode Program Studi sebelumnya (Pindahan)
	array("BISTUMSMHS", "C", 1), 	// Kode Biaya studi (S3)
	array("PEKSBMSMHS", "C", 1), 	// Kode Pekerjaan (S3)
	array("NMPEKMSMHS", "C", 40),	// Nama Tenpat bekerja jika bukan dosen (S3)
	array("PTPEKMSMHS", "C", 6),	// Kode PT tempat bekerja bila dosen (S3)
	array("PSPEKMSMHS", "C", 5),	// Kode PS tempat bekerja bila dosen (S3)
	array("NMPRMMSMHS", "C", 10),	// NIDN Promotor #
	array("NOKP1MSMHS", "C", 10),	// NIDN Promotor 1
	array("NOKP2MSMHS", "C", 10),	// NIDN Promotor 2
	array("NOKP3MSMHS", "C", 10),	// NIDN Promotor 3
	array("NOKP4MSMHS", "C", 10));	// NIDN Promotor 4
					
$HeaderMasterDosen = array ( // MSDOS
	array("KDPTIMSDOS", "C", 6),	// Kode Perguruan Tinggi
	array("KDPSTMSDOS", "C", 5),	// Kode Program Studi
	array("KDJENMSDOS", "C", 1),	// Kode Jenjang Studi
	array("NOKTPMSDOS", "C", 25),	// No KTP dosen
	array("NODOSMSDOS", "C", 10),	// Nomor dosen (NIDN)
	array("NMDOSMSDOS", "C", 30),	// Nama dosen
	array("GELARMSDOS", "C", 10),	// Gelas Akademik
	array("TPLHRMSDOS", "C", 20),	// Tempat Lahir
	array("TGLHRMSDOS", "D"),			// Tanggal Lahir
	array("KDJEKMSDOS", "C", 1),	// Kode Jenis Kelamin
	array("KDJANMSDOS", "C", 1),	// Kode Jabatan akademik
	array("KDPDAMSDOS", "C", 1),	// Kode Pendidikan Tertinggi
	array("KDSTAMSDOS", "C", 1),	// Kode Ikatan status kerja
	array("STDOSMSDOS", "C", 1),	// Kode Aktifitas Dosen
	array("MLSEMMSDOS", "C", 1),	// Semester Dosen Mulai
	array("NIPNSMSDOS", "C", 9),	// NIP PNS
	array("PTINDMSDOS", "N", 6, 0));	// Homebase

$HeaderAktivitasMhsw = array ( // TRAKM
	array("THSMSTRAKM", "C", 5),	// Tahun Semester Pelaporan data
	array("KDPTITRAKM", "C", 6),	// Kode Perguruan Tinggi
	array("KDPSTTRAKM", "C", 5),	// Kode Program Studi
	array("KDJENTRAKM", "C", 1),	// Kode Jenjang Studi
	array("NIMHSTRAKM", "C", 15),	// Nomor Induk Mhsw
	array("MAHASISWA", "C", 50),    // *** Tambahan untuk STKIP PGRI Pontianak
	array("NLIPSTRAKM", "N", 4, 2),	// Nilai IPS
	array("SKSEMTRAKM", "N", 3, 0),	// SKS yang diambil
	array("NLIPKTRAKM", "N", 4, 2),	// Nilai IPK
	array("SKSTTTRAKM", "N", 3, 0)	// SKS Total diperoleh
	);
					
$HeaderAktivitasDosen = array ( // TRAKD
	array("THSMSTRAKD", "C", 5),	// Tahun Semester Pelaporan data
	array("KDPTITRAKD", "C", 6),	// Kode Perguruan Tinggi
	array("KDPSTTRAKD", "C", 5),	// Kode Program Studi
	array("KDJENTRAKD", "C", 1),	// Kode Jenjang Studi
	array("NODOSTRAKD", "C", 10),	// NIDN Dosen
	array("DOSEN", "C", 50),      // *** Tambahan untuk STKIP PGRI Pontianak
	array("KDKMKTRAKD", "C", 10),	// Kode Mata Kuliah
	array("MATAKULIAH", "C", 50), // *** Tambahan untuk STKIP PGRI Pontianak
	array("KELASTRAKD", "C", 2),	// Kode Kelas Pararel
	array("TMRENTRAKD", "N", 2, 0),	// Tatap Muka yang direncanakan
	array("TMRELTRAKD", "N", 2, 0)	// Tatap Muka Realisasi
	);
					
$HeaderNilaiMhsw = array ( // TRNLM
  array("THSMSTRNLM", "C", 5),	// Tahun Semester Pelaporan data
  array("KDPTITRNLM", "C", 6),	// Kode Perguruan Tinggi
  array("KDPSTTRNLM", "C", 5),	// Kode Program Studi
  array("KDJENTRNLM", "C", 1),	// Kode Jenjang Studi
  array("NIMHSTRNLM", "C", 10),	// NIM Mahasiswa
  array("MAHASISWA", "C", 50),  // *** Tambahan untuk STKIP PGRI Pontianak
  array("KDKMKTRNLM", "C", 10),	// Kode Mata Kuliah
  array("MATAKULIAH", "C", 50), // *** Tambahan untuk STKIP PGRI Pontianak
  array("DOSEN", "C", 50),      // *** Tambahan untuk STKIP PGRI Pontianak
  array("NLAKHTRNLM", "C", 2),	// Nilai Berupa A B C D E
  array("BOBOTTRNLM", "N", 4, 2)	// Bobot Nilai
);

$HeaderKelulusanMhsw = array(      // *** TRLSM
  array('THSMSTRLSM', 'C', 5),     // Tahun semester
  array('KDPTITRLSM', 'C', 6),     // Kode PT
  array('KDJENTRLSM', 'C', 1),     // Kode jenjang
  array('KDPSTTRLSM', 'C', 5),     // Kode program studi
  array('NIMHSTRLSM', 'C', 15),    // NIM
  array('MAHASISWA', 'C', 50),     // *** Tambahan untuk STKIP PGRI Pontianak
  array('STMHSTRLSM', 'C', 1),     // Kode status mshw
  array('TGLLSTRLSM', 'D'),        // Tgl Lulus
  array('SKSTTTRLSM', 'N', 19, 5), // Total SKS
  array('NLIPKTRLSM', 'N', 19, 5), // IPK
  array('NOSKRTRLSM', 'C', 30),    // SK Rektor
  array('TGLRETRLSM', 'D'),        // Tgl SK
  array('NOIJATRLSM', 'C', 40),    // No ijazah
  array('STLLSTRLSM', 'C', 1),     // Status lulus
  array('JNLLSTRLSM', 'C', 1),
  array('BLAWLTRLSM', 'C', 6),
  array('BLAKHTRLSM', 'C', 6),
  array('NODS1TRLSM', 'C', 10),
  array('NODS2TRLSM', 'C', 10),
  array('NODS3TRLSM', 'C', 10),
  array('NODS4TRLSM', 'C', 10),
  array('NODS5TRLSM', 'C', 10)
);
					
$HeaderAlamat = array (
          array("No", "N", 5, 0),
          array("MhswID", "C", 10),
          array("Nama", "C", 35),
          array("Alamat", "C", 65),
          array("RT/RW", "C", 8),
          array("Kota", "C", 25),
          array("Kode Pos", "C", 8),
          array("Telepon", "C", 15)
          );
$HeaderMatakuliah = array( // *** TBKMK
  array('THSMSTBKMK', 'C', 5),     // Tahun akademik
  array('KDPTITBKMK', 'C', 6),     // Kode PT Institusi
  array('KDJENTBKMK', 'C', 1),     // Kode jenjang prodi
  array('KDPSTTBKMK', 'C', 5),     // Kode Prodi
  array('KDKMKTBKMK', 'C', 10),    // Kode Matakuliah
  array('NAKMKTBKMK', 'C', 40),    // Nama Matakuliah
  array('SKSMKTBKMK', 'N', 19, 5), // SKS matakuliah
  array('SKSTMTBKMK', 'N', 2, 0),  // SKS Tatap muka
  array('SKSPRTBKMK', 'N', 2, 0),  // SKS Praktikum
  array('SKSLPTBKMK', 'N', 2, 0),  // SKS Lapangan
  array('SEMESTBKMK', 'C', 2),     // Semester MK
  array('KDWPLTBKMK', 'C', 1),     // Kode: A-wajib, B-pilihan, C-wajib peminatan, D-pilihan peminatan, S-skripsi
  array('KDKURTBKMK', 'C', 1),     // Kurikulum???
  array('KDKELTBKMK', 'C', 1),     // ???
  array('NODOSTBKMK', 'C', 10),    // NIDN
  array('STKMKTBKMK', 'C', 1),     // Status MK (A-aktif, H-hapus)
  array('SLBUSTBKMK', 'C', 1),     // Ketersediaan Silabus
  array('SAPPPTBKMK', 'C', 1),     // Ketesedian satuan acara pengajaran (Y/T)
  array('BHNAJTBKMK', 'C', 1),     // Ketersediaan bahan ajar/Diktat (Y/T)
  array('KDUTATBKMK', 'C', 1),     // ???
  array('KDKUGTBKMK', 'C', 1),     // ???
  array('KDLAITBKMK', 'C', 1),     // ???
  array('KDMPATBKMK', 'C', 1),     // ???
  array('KDMPBTBKMK', 'C', 1),     // ???
  array('KDMPCTBKMK', 'C', 1),     // ???
  array('KDMPDTBKMK', 'C', 1),     // ???
  array('KDMPETBKMK', 'C', 1),     // ???
  array('KDMPFTBKMK', 'C', 1),     // ???
  array('KDMPGTBKMK', 'C', 1),     // ???
  array('KDMPHTBKMK', 'C', 1),     // ???
  array('KDMPITBKMK', 'C', 1),     // ???
  array('KDMPJTBKMK', 'C', 1),     // ???
  array('CRMKLTBKMK', 'C', 1),     // ????
  array('PRSTDTBKMK', 'C', 1),     // ????
  array('SMGDSTBKMK', 'C', 1),     // ????
  array('RPSIMTBKMK', 'C', 1),     // ????
  array('CSSTUTBKMK', 'C', 1),     // ????
  array('DISLNTBKMK', 'C', 1),     // ????
  array('SDILNTBKMK', 'C', 1),     // ????
  array('CODLNTBKMK', 'C', 1),     // ????
  array('COLLNTBKMK', 'C', 1),     // ????
  array('CTXINTBKMK', 'C', 1),     // ????
  array('PJBLNTBKMK', 'C', 1),     // ????
  array('PBBLNTBKMK', 'C', 1),     // ????
  array('UJTLSTBKMK', 'N', 2, 0),  // ????
  array('TGMKLTBKMK', 'N', 2, 0),  // ????
  array('TGMODTBKMK', 'N', 2, 0),  // ????
  array('PSTSITBKMK', 'N', 2, 0),  // ????
  array('SIMULTBKMK', 'N', 2, 0),  // ????
  array('LAINNTBKMK', 'N', 2, 0),  // ????
  array('UJTL1TBKMK', 'N', 2, 0),  // ????
  array('TGMK1TBKMK', 'N', 2, 0),  // ????
  array('TGMO1TBKMK', 'N', 2, 0),  // ????
  array('PSTS1TBKMK', 'N', 2, 0),  // ????
  array('SIMU1TBKMK', 'N', 2, 0),  // ????
  array('LAIN1TBKMK', 'N', 2, 0)  // ????
);
?>
