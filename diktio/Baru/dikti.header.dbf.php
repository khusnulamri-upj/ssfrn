<?php


$HeaderMasterDosen = array (			// MSDOS
	array("KDPTIMSDO", "C", 6),			// 1. Kode Perguruan Tinggi => KDPTI MSDO (KDPTI MSDOS)	
	array("NODOSMSDO", "C", 20),		// 2. Nomor dosen (NIDN) => NODOS MSDO, "C", 20 (, "C", 10) (NODOS MSDOS)
	array("NMDOSMSDO", "C", 30),		// 3. Nama dosen => NMDOS MSDO (NMDOS MSDOS)
	array("NIDNNMSDO", "C", 10),		// 4. => BARU
	array("GELARMSDO", "C", 20),		// 5. Gelas Akademik => GELAR MSDO, "C", 20 (, "C", 10) (GELAR MSDOS)
	array("STDOSMSDO", "C", 1),			// 6. Kode Aktifitas Dosen => STDOS MSDO (STDOS MSDOS)
	array("MLSEMMSDO", "C", 10),		// 7. Semester Dosen Mulai atau tanggal mulai bekerja => MLSEM MSDO, "C", 5 (, "C", 1) (MLSEM MSDOS)
	array("SMAWLMSDO", "C", 5),			// 8. => BARU

	array("TPLHRMSDO", "C", 20),		// 9. Tempat Lahir => TPLHR MSDO (TPLHR MSDOS)
	array("TGLHRMSDO", "C", 10),		// 10. Tanggal Lahir => TGLHR MSDO (TGLHR MSDOS) (, "D")
	array("KDJEKMSDO", "C", 1),			// 11. Kode Jenis Kelamin => KDJEK MSDO (KDJEK MSDOS)
	array("KDJANMSDO", "C", 1),			// 12. Kode Jabatan akademik => KDJAN MSDO (KDJAN MSDOS)
	array("KDPDAMSDO", "C", 1),			// 13. Kode Pendidikan Tertinggi => KDPDA MSDO (KDPDA MSDOS)
	array("KDSTAMSDO", "C", 1),			// 14. Kode Ikatan status kerja => KDSTA MSDO (KDSTA MSDOS)		
	array("NIPNSMSDO", "C", 20),		// 15. NIP PNS => NIPNS MSDO, "C", 20 (, "C", 9) (NIPNS MSDOS)
	array("PTINDMSDO", "C", 50),		// 16. PTIND MSDOS => PTIND MSDO, "C", 6 (, "N", 6, 0) (PTIND MSDOS)
	array("KDPSTMSDO", "C", 5),			// 17. Kode Program Studi => KDPST MSDO (KDPST MSDOS)
	array("KDJENMSDO", "C", 1),			// 18. Kode Jenjang Studi => KDJEN MSDO (KDJEN MSDOS)
	array("NOKTPMSDO", "C", 25),		// 19. No KTP dosen => NOKTP MSDO (NOKTP MSDOS)
		
	array("ALDOSMSDO", "C", 50),		// 20. => BARU
	array("TELRMMSDO", "C", 12),		// 21. => BARU
	array("NOHPPMSDO", "C", 12),		// 22. => BARU
	array("EMAILMSDO", "C", 40)			// 23. => BARU	
	
	);

/*
	
	array("STKAT MSDO", "C", 1),		// . => BARU
	array("SRTIJ MSDO", "C", 1),		// . => BARU			
	array("CETAK MSDO", "C", 1),		// . => BARU
	array("KDWIL MSDO", "C", 2),		// . => BARU
	array("AKTAM MSDO", "C", 40),		// . => BARU
	array("FOTOP MSDO", "C", 60),		// . => BARU	

	);	// Homebase
	
*/



$HeaderMasterMhsw = array (				// MSMHS
  array("KDPTIMSMH", "C", 6),			// 1. Kode Perguruan Tinggi => KDPTIMSMH (KDPTIMSMHS)
	array("KDPSTMSMH", "C", 5),			// 3. Kode Program Studi => KDPSTMSMH (KDPSTMSMHS)
	array("KDJENMSMH", "C", 1),			// 2. Kode Jenjang Studi => KDJENMSMH (KDJENMSMHS)		
	array("NIMHSMSMH", "C", 15),		// 4. NIM => NIMHSMSMH (NIMHSMSMHS)
	array("NMMHSMSMH", "C", 30),		// 5. Nama => NMMHSMSMH (NMMHSMSMHS)
	array("SHIFTMSMH", "C", 1),			// 6. SHIFTMSMH (BARU)
	array("TPLHRMSMH", "C", 20),		// 7. Tempat Lahir => TPLHRMSMH (TPLHRMSMHS)
	array("TGLHRMSMH", "C", 10),		// 8. Tanggal Lahir => TGLHRMSMH	(TGLHRMSMHS)
	array("KDJEKMSMH", "C", 1),			// 9. Jenis Kelamin => KDJEKMSMH (KDJEKMSMHS)
	array("TAHUNMSMH", "C", 4),			// 10. Tahun masuk => TAHUNMSMH (TAHUNMSMHS)
	array("SMAWLMSMH", "C", 5),			// 11. Semester awal terdaftar => SMAWLMSMH	(SMAWLMSMHS)
	array("BTSTUMSMH", "C", 5),			// 12. Batas Studi => BTSTUMSMH (BTSTUMSMHS)
	array("ASSMAMSMH", "C", 2),			// 13. Kode Propinsi Pendidikan terakhir => ASSMAMSMH (ASSMAMSMHS)
	array("TGMSKMSMH", "C", 10),		// 14. Tanggal Masuk => TGMSKMSMH	(TGMSKMSMHS)
	array("TGLLSMSMH", "C", 10),		// 15. Tanggal Lulus => TGLLSMSMH (TGLLSMSMHS)
	array("STMHSMSMH", "C", 1),			// 16. Kode Status Mhsw => STMHSMSMH (STMHSMSMHS)
	array("STPIDMSMH", "C", 1),			// 17. Kode Status Awal (Baru/Pindahan) => STPIDMSMH (STPIDMSMHS)
	array("SKSDIMSMH", "N", 4, 0),	// 18. Jumlah SKS Pindahan => SKSDIMSMH, "N", 4, 0 (SKSDIMSMHS) (, "N", 3, 0)
	array("ASNIMMSMH", "C", 15),		// 19. NIM asal perguruan tinggi (Pindahan) => ASNIMMSMH (ASNIMMSMHS)
	array("ASPTIMSMH", "C", 6),			// 20. Kode Perguruan tinggi sebelumnya (Pindahan) => ASPTIMSMH (ASPTIMSMHS)
	array("ASJENMSMH", "C", 1),			// 21. Kode Jenjang studi sebelumnya (Pindahan) => ASJENMSMH (ASJENMSMHS)
	array("ASPSTMSMH", "C", 5), 		// 22. Kode Program Studi sebelumnya (Pindahan) => ASPSTMSMH (ASPSTMSMHS)	
	array("BISTUMSMH", "C", 1), 		// 23. Kode Biaya studi (S3) => BISTUMSMH (BISTUMSMHS)
	array("PEKSBMSMH", "C", 1), 		// 24. Kode Pekerjaan (S3) => PEKSBMSMH (PEKSBMSMHS)
	array("NMPEKMSMH", "C", 40),		// 25. Nama Tenpat bekerja jika bukan dosen (S3) => NMPEKMSMH (NMPEKMSMHS)
	array("PTPEKMSMH", "C", 6),			// 26. Kode PT tempat bekerja bila dosen (S3) => PTPEKMSMH (NMPEKMSMHS)
	array("PSPEKMSMH", "C", 5),			// 27. Kode PS tempat bekerja bila dosen (S3) => PSPEKMSMH (PSPEKMSMHS)		
	array("NOPRMMSMH", "C", 10),		// 28. NIDN Promotor # => NOPRMMSMH (NOPRMMSMHS)
	array("NOKP1MSMH", "C", 10),		// 29. NIDN Promotor 1 => NOKP1MSMH (NOKP1MSMHS)
	array("NOKP2MSMH", "C", 10),		// 30. NIDN Promotor 2 => NOKP2MSMH (NOKP2MSMHS)
	array("NOKP3MSMH", "C", 10),		// 31. NIDN Promotor 3 => NOKP3MSMH (NOKP3MSMHS)
	array("NOKP4MSMH", "C", 10),		// 32. NIDN Promotor 4 => NOKP4MSMH (NOKP4MSMHS)

	array("NIMANMSMH", "C", 26),		// 33. => NIMANMSMH (BARU)
	array("ALMHSMSMH", "C", 50),		// 34. => ALMHSMSMH (BARU)
	array("TELRMMSMH", "C", 15),		// 35. => TELRMMSMH (BARU)
	array("NOHPPMSMH", "C", 12),		// 36. => NOHPPMSMH (BARU)
	array("EMAILMSMH", "C", 40),		// 37. => EMAILMSMH (BARU)
	array("NOIJSMSMH", "C", 50)			// 38. => NOIJSMSMH (BARU)		
	
	);	

	/* 
	array("NMPRM MSMH", "C", 10),		// NIDN Promotor # => NMPRMMSMH (TIDAK ADA) (NMPRMMSMHS)		
	array("MLSEM MSMH", "C", 5),		// => MLSEMMSMH (BARU)
	array("TGMS1 MSMH", "C", 8),		// => TGMS1MSMH (BARU)	
	array("USIAM MSMH", "N", 4, 0), // => USIAMMSMH, "N", 4, 0 (BARU) (, "N", 3, 0)
	array("SMAW1 MSMH", "C", 5),		// => SMAW1MSMH (BARU)	
	array("NILUN MSMH", "C", 21),		// => NILUNMSMH (BARU)
	array("STKRS MSMH", "C", 1),		// => STKRSMSMH (BARU)	
	array("FOTOO MSMH", "C", 50),		// => FOTOOMSMH (BARU)	
	array("PDLLS MSMH", "C", 1)			// => PDLLSMSMH (BARU)
	*/



$HeaderMatakuliah = array(					// *** TBKMK
  array('THSMSTBKM', 'C', 5),				// 1. Tahun akademik => THSMSTBKM (THSMSTBKMK)
  array('KDPTITBKM', 'C', 6),				// 2. Kode PT Institusi => KDPTITBKM (KDPTITBKMK)
  array('KDJENTBKM', 'C', 1),				// 3. Kode jenjang prodi => KDJENTBKM (KDJENTBKMK)
  array('KDPSTTBKM', 'C', 5),				// 4. Kode Prodi => KDPSTTBKM (KDPSTTBKMK)
  array('KDKMKTBKM', 'C', 10),			// 5. Kode Matakuliah => KDKMKTBKM (KDKMKTBKMK)
  array('NAKMKTBKM', 'C', 40),			// 6. Nama Matakuliah => NAKMKTBKM (NAKMKTBKMK)
  array('SKSMKTBKM', 'N', 19, 5),		// 7. SKS matakuliah => SKSMKTBKM (SKSMKTBKMK)
  array('SKSTMTBKM', 'N', 19, 5),		// 8. SKS Tatap muka => SKSTMTBKM, 'N', 4, 0 (SKSTMTBKMK) (, 'N', 2, 0)
  array('SKSPRTBKM', 'N', 19, 5),		// 9. SKS Praktikum => SKSPRTBKM, 'N', 4, 0 (SKSPRTBKMK) (, 'N', 2, 0)
  array('SKSLPTBKM', 'N', 19, 5),		// 10. SKS Lapangan => SKSLPTBKM, 'N', 4, 0 (SKSLPTBKMK) (, 'N', 2, 0)
  array('SEMESTBKM', 'C', 2),				// 11. Semester MK => SEMESTBKM (SEMESTBKMK)
  array('KDWPLTBKM', 'C', 1),				// 12. Kode: A-wajib, B-pilihan, C-wajib peminatan, D-pilihan peminatan, S-skripsi => KDWPLTBKM (KDWPLTBKMK)
  
	array('KDKURTBKM', 'C', 1),				// 13. Kode Kurikulum Inti/Institusi => KDKURTBKM (KDKURTBKMK)
  array('KDKELTBKM', 'C', 1),				// 14. Kode Kelompok Mata Kuliah => KDKELTBKM (KDKELTBKMK)
  array('NODOSTBKM', 'C', 10),			// 15. NIDN => NODOSTBKM (NODOSTBKMK)
	
	array('JENJATBKM', 'C', 1), 			// 16. BARU
	array('PRODITBKM', 'C', 5), 			// 17. BARU
	
	array('STKMKTBKM', 'C', 1),				// 18. Status MK (A-aktif, H-hapus) => STKMKTBKM (STKMKTBKMK)
  array('SLBUSTBKM', 'C', 1),				// 19. Ketersediaan Silabus => SLBUSTBKM (SLBUSTBKMK)
  array('SAPPPTBKM', 'C', 1),				// 20. Ketesedian satuan acara pengajaran (Y/T) => SAPPPTBKM (SAPPPTBKMK)
  array('BHNAJTBKM', 'C', 1),				// 21. Ketersediaan bahan ajar/Diktat (Y/T) => BHNAJTBKM (BHNAJTBKMK)
  array('DIKTTTBKM', 'C', 1) 				// 22. Diktat => DIKTTTBKM (DIKTTTBKMK)

  );



$HeaderAktivitasDosen = array (			// TRAKD
	array("THSMSTRAK", "C", 5),				// 1. Tahun Semester Pelaporan data => THSMSTRAK (THSMSTRAKD)
	array("KDPTITRAK", "C", 6),				// 2. Kode Perguruan Tinggi => KDPTITRAK (KDPTITRAKD)
	array("KDJENTRAK", "C", 1),				// 3. Kode Jenjang Studi => KDJENTRAK (KDJENTRAKD)
	array("KDPSTTRAK", "C", 5),				// 4. Kode Program Studi => KDPSTTRAK (KDPSTTRAKD)
	array("NODOSTRAK", "C", 10),			// 5. NIDN Dosen => NODOSTRAK (NODOSTRAKD)		
	array("KDKMKTRAK", "C", 10),			// 6. Kode Mata Kuliah => KDKMKTRAK (KDKMKTRAKD)	
	array("KELASTRAK", "C", 5),				// 7. Kode Kelas Pararel => KELASTRAK (KELASTRAKD)
	array("TMRENTRAK", "N", 19, 5),		// 8. Tatap Muka yang direncanakan => TMRENTRAK, "N", 4, 0 (TMRENTRAKD) (, "N", 2, 0)
	array("TMRELTRAK", "N", 19, 5),		// 9. Tatap Muka Realisasi => TMRELTRAK, "N", 4, 0 (TMRELTRAKD) (, "N", 2, 0)	
	
	array("NMDOSTRAK", "C", 50),			// 10. => BARU
	array("SKSMKTRAK", "N", 4, 0),		// 11. => BARU
	array("CETAKTRAK", "C", 1),				// 12. => BARU
	
	array("MATAKULIAH", "C", 50)			// 14. *** Tambahan untuk STKIP PGRI Pontianak => MATAKULIAH (TIDAK ADA)

	);

	/* 
	array("DOSEN", "C", 50),					// 13. *** Tambahan untuk STKIP PGRI Pontianak => DOSEN (TIDAK ADA)
	*/



$HeaderAktivitasMhsw = array (		// TRAKM
	array("THSMSTRAK", "C", 5),			// 1. Tahun Semester Pelaporan data => THSMSTRAK (THSMSTRAKM)
	array("KDPTITRAK", "C", 6),			// 2. Kode Perguruan Tinggi => KDPTITRAK (KDPTITRAKM)
	array("KDJENTRAK", "C", 1),			// 3. Kode Jenjang Studi => KDJENTRAK (KDJENTRAKM)
	array("KDPSTTRAK", "C", 5),			// 4. Kode Program Studi => KDPSTTRAK (KDPSTTRAKM)
	array("NIMHSTRAK", "C", 15),		// 5. Nomor Induk Mhsw => NIMHSTRAK (NIMHSTRAKM)
	array("SKSEMTRAK", "N", 19, 5),	// 6. SKS yang diambil => SKSEMTRAK, "N", 4 (SKSEMTRAKM) (, "N", 3, 0)
	array("NLIPSTRAK", "N", 19, 5),	// 7. Nilai IPS => NLIPSTRAK, "N", 8, 2 (NLIPSTRAKM) (, "N", 4, 2)
	array("SKSTTTRAK", "N", 19, 5),	// 8. SKS Total diperoleh => SKSTTTRAK, "N", 4 (SKSTTTRAKM) (, "N", 3, 0)
	array("NLIPKTRAK", "N", 19, 5),	// 9. Nilai IPK => NLIPKTRAK, "N", 8, 2 (NLIPKTRAKM) (, "N", 4, 2)

	array("MAHASISWA", "C", 50),    // 10. *** Tambahan untuk STKIP PGRI Pontianak => MAHASISWA (TIDAK ADA) (MAHASISWA)	
					
	array("SMAWLTRAK", "C", 5),			// 11. => BARU
	array("STMHSTRAK", "C", 1),			// 12. => BARU
	array("MLSEMTRAK", "C", 5)  		// 13. => BARU		

	);



$HeaderKelulusanMhsw = array(      // *** TRLSM
  array('THSMSTRLS', 'C', 5),      // 1. Tahun semester => THSMSTRLS (THSMSTRLSM)
  array('KDPTITRLS', 'C', 6),      // 2. Kode PT => KDPTITRLS (KDPTITRLSM)
  array('KDJENTRLS', 'C', 1),      // 3. Kode jenjang => KDJENTRLS (KDJENTRLSM)
  array('KDPSTTRLS', 'C', 5),      // 4. Kode program studi => KDPSTTRLS (KDPSTTRLSM)
  array('NIMHSTRLS', 'C', 15),     // 5. NIM => NIMHSTRLS (NIMHSTRLSM)  
  array('STMHSTRLS', 'C', 1),      // 6. Kode status mshw => STMHSTRLS (STMHSTRLSM)
  array('TGLLSTRLS', "C", 10),     // 7. Tgl Lulus => TGLLSTRLS (TGLLSTRLSM)
  array('SKSTTTRLS', 'N', 19, 5),  // 8. Total SKS => SKSTTTRLS, "N", 4 (SKSTTTRLSM) (, 'N', 19, 5)
  array('NLIPKTRLS', 'N', 19, 5),  // 9. IPK => NLIPKTRLS (NLIPKTRLSM)
  array('NOSKRTRLS', 'C', 30),     // 10. SK Rektor => NOSKRTRLS (NOSKRTRLSM)
  array('TGLRETRLS', "C", 10),     // 11. Tgl SK => TGLRETRLS (TGLRETRLSM)
  array('NOIJATRLS', 'C', 40),     // 12. No ijazah => NOIJATRLS (NOIJATRLSM)
  array('STLLSTRLS', 'C', 1),      // 13. Status lulus => STLLSTRLS (STLLSTRLSM)		
  
	array('JNLLSTRLS', 'C', 1),		   // 14. Jenis Skripsi Kelompok/Individual => JNLLSTRLS (JNLLSTRLSM)
  array('BLAWLTRLS', 'C', 6),		   // 15. Bulan dan Tahun Awal Skripsi => BLAWLTRLS (BLAWLTRLSM)
  array('BLAKHTRLS', 'C', 6),		   // 16. Bulan dan Tahun Akhir Skripsi => BLAKHTRLS (BLAKHTRLSM)
  array('NODS1TRLS', 'C', 10),		 // 17. Dosen Pembimbing 1 => NODS1TRLS (NODS1TRLSM)
  array('NODS2TRLS', 'C', 10),		 // 18. Dosen Pembimbing 2 => NODS2TRLS (NODS2TRLSM)
  array('NODS3TRLS', 'C', 10),		 // 19. => NODS3TRLS (NODS3TRLSM)
  array('NODS4TRLS', 'C', 10),     // 20. => NODS4TRLS (NODS4TRLSM)
  array('NODS5TRLS', 'C', 10),     // 21. => NODS5TRLS (NODS5TRLSM)	

	array('MAHASISWA', 'C', 50)     // 22. *** Tambahan untuk STKIP PGRI Pontianak => MAHASISWA (TIDAK ADA)

	);



$HeaderNilaiMhsw = array( // TRNLM
  array("THSMSTRNL", "C", 5),			// 1. Tahun Semester Pelaporan data => THSMSTRNL (THSMSTRNLM)
  array("KDPTITRNL", "C", 6),			// 2. Kode Perguruan Tinggi => KDPTITRNL (KDPTITRNLM)
	array("KDJENTRNL", "C", 1),			// 3. Kode Jenjang Studi => KDJENTRNL (KDJENTRNLM)
  array("KDPSTTRNL", "C", 5),			// 4. Kode Program Studi => KDPSTTRNL (KDPSTTRNLM)  
  array("NIMHSTRNL", "C", 15),		// 5. NIM Mahasiswa => NIMHSTRNL, "C", 15 (NIMHSTRNLM)  
  array("KDKMKTRNL", "C", 10),		// 6. Kode Mata Kuliah => KDKMKTRNL (KDKMKTRNLM)  
  array("NLAKHTRNL", "C", 2),			// 7. Nilai Berupa A B C D E => NLAKHTRNL (NLAKHTRNLM)
  array("BOBOTTRNL", "N", 19, 5),	// 8. Bobot Nilai => BOBOTTRNL (BOBOTTRNLM)	
	array("KELASTRNL", "C", 5),			// 9. => BARU, asli "C", 2

	array("MAHASISWA", "C", 50),		// 10. *** Tambahan untuk STKIP PGRI Pontianak => MAHASISWA (TIDAK ADA)
	array("MATAKULIAH", "C", 50),		// 11. *** Tambahan untuk STKIP PGRI Pontianak => MATAKULIAH (TIDAK ADA)
  array("DOSEN", "C", 50)					// 12. *** Tambahan untuk STKIP PGRI Pontianak => DOSEN (TIDAK ADA)

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

?>

