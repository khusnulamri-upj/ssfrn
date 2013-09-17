<?php
function ResetAplikan() {
  $w = array();
  $w['AplikanID'] = '';
  $w['PresenterID'] = '';
  $w['PMBPeriodID'] = GetaField('pmbperiod', 'NA', 'N', 'PMBPeriodID');
  $w['Nama'] = '';
  $w['KodeID'] = $_SESSION['KodeID'];
  $w['Kelamin'] = 'P';
  $w['TempatLahir'] = '';
  $w['TanggalLahir'] = date('Y-m-d');
  $w['Agama'] = '';
  $w['Alamat'] = '';
  $w['Kota'] = '';
  $w['RT'] = '';
  $w['RW'] = '';
  $w['KodePos'] = '';
  $w['Telepon'] = '';
  $w['Handphone'] = '';
  $w['Email'] = '';
  $w['NamaOrtu'] = '';
  $w['PendidikanOrtu'] = '';
  $w['PekerjaanOrtu'] = '';
  $w['AsalSekolah'] = '';
  $w['JurusanSekolah'] = '';
  $w['TahunLulus'] = date('Y');
  $w['PilihanD3'] = '';
  $w['PilihanS1'] = '';
  return $w;
}

function GetCheckboxes2($table, $key, $Fields, $Label, $Nilai='', $Separator=',', $whr = '', $antar='<br />') {
  $_whr = (empty($whr))? '' : "and $whr";
  $s = "select $key, $Fields
    from $table
    where NA='N' $_whr order by $key";
  $r = _query($s);
  $_arrNilai = explode($Separator, $Nilai);
  $str = '';
  while ($w = _fetch_array($r)) {
    $_ck = (array_search($w[$key], $_arrNilai) === false)? '' : 'checked';
    $str .= "<input type=checkbox name='".$key."[]' value='$w[$key]' $_ck> $w[$Label]$antar";
  }
  return $str;
}

function edtAplikan(){
    $md = $_REQUEST['md']+0;
    if ($md == 0) {
      $w = GetFields('aplikan', 'AplikanID', $_REQUEST['aplikan'], '*');
      $jdl = "Edit Form Aplikan";
      $_pmbid = "<input type=hidden name='AplikanID' value='$w[AplikanID]'><b>$w[AplikanID]</b>";
    }
    else {
	$w = ResetAplikan();
	$jdl = "Tambah Form Aplikan";
	$_pmbid = "<input type=hidden name='AplikanID' value='' /><font color=red>[AutoNumber]</font>";
      }
    
    $prdchkS1 = GetCheckboxes2('prodi', 'ProdiID', "concat(ProdiID, ' - ', Nama) as PRD", 'PRD', $w['ProdiID'], ',', "JenjangID='C'", "&nbsp;");
    $prdchkD3 = GetCheckboxes2('prodi', 'ProdiID', "concat(ProdiID, ' - ', Nama) as PRD", 'PRD', $w['ProdiID'], ',', "JenjangID='E'", "&nbsp;");
    // Asal Sekolah
    $NamaSekolah = GetaField('asalsekolah', 'SekolahID', $w['AsalSekolah'], "concat(Nama, ', ', Kota)");
    // Kelamin
    $optkelamin = GetRadio("select * from kelamin where NA='N'", 'Kelamin', 'Nama', 'Kelamin', $w['Kelamin'], '&nbsp;');
  
    $_TanggalLahir = GetDateOption($w['TanggalLahir'], 'TanggalLahir');
    $_Agama = GetOption2('agama', "concat(Agama, ' - ', Nama)", '', $w['Agama'], '', 'Agama');
  
    $_PendidikanAyah = GetOption2('pendidikanortu', "concat(Pendidikan, ' - ', Nama)", 'Pendidikan', $w['PendidikanAyah'], '', 'Pendidikan');
    $_PekerjaanAyah = GetOption2('pekerjaanortu', "concat(Pekerjaan, ' - ', Nama)", 'Pekerjaan', $w['PekerjaanAyah'], '', 'Pekerjaan');
  
    $_PendidikanIbu = GetOption2('pendidikanortu', "concat(Pendidikan, ' - ', Nama)", 'Pendidikan', $w['PendidikanIbu'], '', 'Pendidikan');
    $_PekerjaanIbu = GetOption2('pekerjaanortu', "concat(Pekerjaan, ' - ', Nama)", 'Pekerjaan', $w['PekerjaanIbu'], '', 'Pekerjaan');
    
    $_JurusanSekolah = GetOption2('jurusansekolah', "concat(Nama, ' - ', NamaJurusan)", 'Nama', $w['JurusanSekolah'], '', 'JurusanSekolahID');    
    CariSekolahScript();
    // Tampilkan formulir Aplikan
    $cekfields = "Nama,PMBFormJualID,ProgramID,Pilihan1,TempatLahir";
    CheckFormScript($cekfields);
    echo "
    <form name='data' action='$act' method=POST onSubmit=\"return CheckForm(this);\">
    <input type=hidden name='BypassMenu' value=1>
    <input type=hidden name='md' value='$md'>
    <input type=hidden name='mnux' value='$mnux'>
    <input type=hidden name='gos' value='$pmbgos'>
    <input type=hidden name='pmbaktif' value='$_SESSION[pmbaktif]'>
    
	<fieldset style='width:80%'>
	    <legend>$jdl</legend>
		<ol>
		    <li>
			<label for='AplikanID'>Nomor Aplikan:</label>
			<input type=text name='Nama' value='$w[Nama]' size=30 maxlength='50' class=text />
		    </li>
		    <li>
			<label for='Nama'>Nama:</label>
			<input type=text name='Nama' value='$w[Nama]' size=30 maxlength='50' class=text />
		    </li>
		    <li>
			<label for=TempatLahir>Tempat Lahir:</label>
			<input type=text name='TempatLahir' value='$w[TempatLahir]' size=30 maxlength=50 class=text />
			<label for=TanggalLahir class=none>Tanggal Lahir:</label>
			$_TanggalLahir
		    </li>
		    <li>
			<label for='Nama'>Jenis Kelamin:</label>
			$optkelamin
		    </li>
		    <li>
			<label for='Nama'>Agama:</label>
			<select name='Agama'>$_Agama</select>
		    </li>
		    <li>
			<label for='Alamat'>Alamat:</label>
			<textarea name='Alamat' cols=40 rows=2>$w[Alamat]</textarea>
		    </li>
		    <li>
			<label for='RT'>RT:</label>
			<input type=text name='RT' value='$w[RT]' size=8 maxlength=8 class=text />
		    </li>
		    <li>
			<label for='RW'>RW:</label>
			<input type=text name='RW' value='$w[RW]' size=8 maxlength=8 class=text />
		    </li>
		    <li>
			<label for='KodePos'>Kode Pos:</label>
			<input type=text name='KodePos' value='$w[KodePos]' size=25 maxlength=50 class=text />
		    </li>
		    <li>
			<label for='Email'>Email:</label>
			<input type=text name='Email' value='$w[Email]' size=25 maxlength=100 class=text />
		    </li>
		    <li>
			<label for='Telepon'>Telepon:</label>
			<input type=text name='Telepon' value='$w[Telepon]' size=25 maxlength=50 />
			<label for='Handphone' class=none>Handphone:</label>
			<input type=text name='Handphone' value='$w[Handphone]' size=25 maxlength=50 />
		    </li>
		</ol>
	    </fieldset>
	    <fieldset style='width:80%'>
		<legend>Pendidikan Terakhir</legend>
		<ol>
		    <li>
			<label for='NamaSekolah'>Sekolah:</label>
			<input type=text name='NamaSekolah' value='$NamaSekolah' size=50 maxlength=255 /><a href='javascript:carisekolah(data)'> Cari</a>
			<input type=hidden name='AsalSekolah' value='$w[AsalSekolah]' />
		    </li>
		    <li>
			<label for=TahunLulus>Lulus Tahun:</label>
			<input type=text name='TahunLulus' value='$w[TahunLulus]' size=10 maxlength=5>
		    </li>
		    <li>
			<label for=Jurusan>Jurusan:</label>
			<select name='JurusanSekolah'>$_JurusanSekolah</select>
		    </li>
		</ol>
	    </fieldset>
	    <fieldset style='width:80%'>
		<legend>Orang Tua</legend>
		<ol>
		    <li>
			<label for='NamaAyah'>Nama Ayah:</label>
			<input type=text name='NamaAyah' value='$w[NamaAyah]' size=40 maxlength=50>
		    </li>
		    <li>
			<label for=PendidikanAyah>Pendidikan Ayah:</label>
			<select name='PendidikanAyah'>$_PendidikanAyah</select>
		    </li>
		    <li>
			<label for=PekerjaanAyah>Pekerjaan Ayah:</label>
			<select name='PekerjaanAyah'>$_PekerjaanAyah</select>
		    </li>
		    <li>
			<label for='NamaIbu's>Nama Ibu:</label>
			<input type=text name='NamaIbu' value='$w[NamaIbu]' size=40 maxlength=50>
		    </li>
		    <li>
			<label for=PendidikanIbu>Pendidikan Ibu:</label>
			<select name='PendidikanIbu'>$_PendidikanIbu</select>
		    </li>
		    <li>
			<label for=PekerjaanIbu>Pekerjaan Ibu</label>
			<select name='PekerjaanIbu'>$_PekerjaanIbu</select>
		    </li>
		</ol>
	    </fieldset>
	    <fieldset style='width:80%'>
		<legend>Pilihan Program Studi</legend>
		<ol>
		    <li>
			<label for='Diploma'>Diploma Tiga (D3):</label>
			$prdchkD3
		    </li>
		    <li>
			<label for='strata'>Strata Satu (S1):</label>
			$prdchkS1
		    </li>
		</ol>
	    </fieldset>
	    <fieldset class=submit style='width:80%'>
		<input class=submit type=submit value=Submit />
	    </fieldset>
	</form>";
}

function CariSekolahScript() {
  echo <<<EOF
  <SCRIPT LANGUAGE="JavaScript1.2">
  <!--
  function carisekolah(frm){
    lnk = "cetak/carisekolah.php?Cari="+frm.NamaSekolah.value;
    win2 = window.open(lnk, "", "width=600, height=600, scrollbars, status");
    win2.creator = self;
  }
  -->
  </script>
EOF;
}

function aplikanSav($gos='') {
  $md = $_REQUEST['md']+0;
  $PresenterID = sqling($_REQUEST['PresenterID']);
  $Nama = sqling($_REQUEST['Nama']);
  $Kelamin = $_REQUEST['Kelamin'];
  $TempatLahir = sqling($_REQUEST['TempatLahir']);
  $TanggalLahir = "$_REQUEST[TanggalLahir_y]-$_REQUEST[TanggalLahir_m]-$_REQUEST[TanggalLahir_d]";
  $Agama = $_REQUEST['Agama'];
  $Alamat = sqling($_REQUEST['Alamat']);
  $Kota = sqling($_REQUEST['Kota']);
  $KodePos = sqling($_REQUEST['KodePos']);
  $Telepon = sqling($_REQUEST['Telepon']);
  $Handphone = sqling($_REQUEST['Handphone']);
  $Email = sqling($_REQUEST['Email']);

  $NamaAyah = sqling($_REQUEST['NamaAyah']);
  $PendidikanAyah = $_REQUEST['PendidikanAyah'];
  $PekerjaanAyah = $_REQUEST['PekerjaanAyah'];

  $NamaIbu = sqling($_REQUEST['NamaIbu']);
  $PendidikanIbu = $_REQUEST['PendidikanIbu'];
  $PekerjaanIbu = $_REQUEST['PekerjaanIbu'];

  $AsalSekolah = sqling($_REQUEST['AsalSekolah']);
  $JurusanSekolah = sqling($_REQUEST['JurusanSekolah']);
  $TahunLulus = $_REQUEST['TahunLulus'];
  $NilaiSekolah = sqling($_REQUEST['NilaiSekolah']);
  
  $JenisSekolahID = GetaField('asalsekolah', "SekolahID", $AsalSekolah, "JenisSekolahID");

  // Simpan
  if ($md == 0) {
    $AplikanID = $_REQUEST['AplikanID'];
    
    $s = "update aplikan set PMBRef='$PMBRef', Nama='$Nama', PMBFormJualID='$PMBFormJualID', PSSBID='$PSSBID',
      StatusAwalID='$StatusAwalID', MhswPindahanID='$MhswPindahanID', 
      Kelamin='$Kelamin', WargaNegara='$WargaNegara', Harga='$Harga',
      ProgramID='$ProgramID',
      Kebangsaan='$Kebangsaan', TempatLahir='$TempatLahir', TanggalLahir='$TanggalLahir',
      Agama='$Agama', StatusSipil='$StatusSipil',
      Alamat='$Alamat', Kota='$Kota',
      KodePos='$KodePos', Propinsi='$Propinsi', Negara='$Negara',
      RT='$RT', RW='$RW', Telepon='$Telepon', Handphone='$Handphone', Email='$Email',
      AlamatAsal='$AlamatAsal', KotaAsal='$KotaAsal',
      KodePosAsal='$KodePosAsal', PropinsiAsal='$PropinsiAsal', NegaraAsal='$NegaraAsal',
      RTAsal='$RTAsal', RWAsal='$RWAsal', TeleponAsal='$TeleponAsal',
      NamaAyah='$NamaAyah', AgamaAyah='$AgamaAyah', PendidikanAyah='$PendidikanAyah', PekerjaanAyah='$PekerjaanAyah', HidupAyah='$HidupAyah',
      NamaIbu='$NamaIbu', AgamaIbu='$AgamaIbu', PendidikanIbu='$PendidikanIbu', PekerjaanIbu='$PekerjaanIbu', HidupIbu='$HidupIbu',
      AlamatOrtu='$AlamatOrtu', KotaOrtu='$KotaOrtu',
      KodePosOrtu='$KodePosOrtu', PropinsiOrtu='$PropinsiOrtu', NegaraOrtu='$NegaraOrtu',
      RTOrtu='$RTOrtu', RWOrtu='$RWOrtu',
      TeleponOrtu='$TeleponOrtu', HandphoneOrtu='$HandphoneOrtu', EmailOrtu='$EmailOrtu',
      AsalSekolah='$AsalSekolah', AlamatSekolah='$AlamatSekolah', KotaSekolah='$KotaSekolah',
      JenisSekolahID='$JenisSekolahID',
      JurusanSekolah='$JurusanSekolah', NilaiSekolah='$NilaiSekolah', TahunLulus='$TahunLulus',
      AsalPT='$AsalPT', ProdiAsalPT='$ProdiAsalPT', LulusAsalPT='$LulusAsalPT', TglLulusAsalPT='$TglLulusAsalPT',
      LoginEdit='$_SESSION[_Login]', TanggalEdit=now(), ProdiID='$_REQUEST[Pilihan1]' 
      $_p $_pssb
      where PMBID='$PMBID' ";
    $r = _query($s);
  }
  elseif ($md == 1) {
    // Jika nomer kwitansi sdh dipakai oleh orang lain
    $sdhdipakai = GetFields("pmb", "PMBFormJualID", $PMBFormJualID, "*");
    if (!empty($sdhdipakai))
      die(ErrorMsg("Tidak Dapat Disimpan",
        "Nomer kwitansi <font size=+1>$PMBFormJualID</font> sudah dipakai oleh orang lain,<br />
        Yaitu: <font size=+1>$sdhdipakai[Nama]</font> (No PMB: <b>$sdhdipakai[PMBID]</b>.
        <hr size=1 color=silver>
        Opsi: <a href='?mnux=$_SESSION[mnux]'>Kembali</a>"));
    // Update PMBFormulir dulu
    if ($BeliFormulir == 'Y') {
      $s0 = "update pmbformjual set OK='Y' where PMBFormJualID='$PMBFormJualID' ";
      $r0 = _query($s0);
    }
    // Baru simpan data
    $_p = ''; $_pn = '';
    for ($i=1; $i<=$_PMBMaxPilihan; $i++) {
      $_n = "Pilihan$i";
      $_p .= ", $_n";
      $_pn .= ($i <= $_REQUEST['JumlahPilihan'])? ", '$_REQUEST[$_n]'" : ", ''";
    }
    $_fpssb = ($StatusAwalID == 'S')? ", GradeNilai" : '';
    $_npssb = ($StatusAwalID == 'S')? ", 'A'" : '';
    $_LulusUjian = ($StatusAwalID == 'S')? "Y" : $_LulusUjian;
    $BIPOTID = GetaField('bipot', "Def='Y' and ProgramID='$ProgramID' and ProdiID", $_REQUEST['Pilihan1'], 'BIPOTID');
    $PMBID = GetNextPMBID($_REQUEST['Pilihan1']);
    $s = "insert into pmb (PMBID, PMBRef, PMBPeriodID, Nama, PMBFormJualID,
      MhswPindahanID,
      PSSBID, StatusAwalID, Harga, Kelamin,
      KodeID, ProgramID, BIPOTID,
      PMBFormulirID, WargaNegara, Kebangsaan,
      TempatLahir, TanggalLahir,
      Agama, StatusSipil,
      Alamat, Kota,
      KodePos, Propinsi, Negara,
      RT, RW, Telepon, Handphone, Email,
      AlamatAsal, KotaAsal,
      KodePosAsal, PropinsiAsal, NegaraAsal,
      RTAsal, RWAsal, TeleponAsal,
      NamaAyah, AgamaAyah, PendidikanAyah, PekerjaanAyah, HidupAyah,
      NamaIbu, AgamaIbu, PendidikanIbu, PekerjaanIbu, HidupIbu,
      AlamatOrtu, KotaOrtu,
      KodePosOrtu, PropinsiOrtu, NegaraOrtu,
      RTOrtu, RWOrtu, TeleponOrtu, HandphoneOrtu, EmailOrtu,
      AsalSekolah, JenisSekolahID, AlamatSekolah, KotaSekolah,
      NilaiSekolah, JurusanSekolah, TahunLulus, 
      AsalPT, ProdiAsalPT, LulusAsalPT, TglLulusAsalPT,
      LoginBuat, TanggalBuat, ProdiID, LulusUjian
      $_p $_fpssb)

      values ('$PMBID', '$PMBRef', '$_SESSION[pmbaktif]', '$Nama', '$PMBFormJualID',
      '$MhswPindahanID',
      '$PSSBID', '$StatusAwalID', '$Harga', '$Kelamin',
      '$_SESSION[KodeID]', '$ProgramID', '$BIPOTID',
      '$_REQUEST[PMBFormulirID]', '$WargaNegara', '$Kebangsaan',
      '$TempatLahir', '$TanggalLahir',
      '$Agama', '$StatusSipil',
      '$Alamat', '$Kota',
      '$KodePos', '$Propinsi', '$Negara',
      '$RT', '$RW', '$Telepon', '$Handphone', '$Email',
      '$AlamatAsal', '$KotaAsal',
      '$KodePosAsal', '$PropinsiAsal', '$NegaraAsal',
      '$RTAsal', '$RWAsal', '$TeleponAsal',
      '$NamaAyah', '$AgamaAyah', '$PendidikanAyah', '$PekerjaanAyah', '$HidupAyah',
      '$NamaIbu', '$AgamaIbu', '$PendidikanIbu', '$PekerjaanIbu', '$HidupIbu',
      '$AlamatOrtu', '$KotaOrtu',
      '$KodePosOrtu', '$PropinsiOrtu', '$NegaraOrtu',
      '$RTOrtu', '$RWOrtu', '$TeleponOrtu', '$HandphoneOrtu', '$Email',
      '$AsalSekolah', '$JenisSekolahID', '$AlamatSekolah', '$KotaSekolah',
      '$NilaiSekolah', '$JurusanSekolah', '$TahunLulus', 
      '$AsalPT', '$ProdiAsalPT', '$LulusAsalPT', '$TglLulusAsalPT',
      '$_SESSION[_Login]', now(), '$_REQUEST[Pilihan1]', '$_LulusUjian' 
      $_pn $_npssb)";
    $r = _query($s);
  }
  else {
    $strStatusAwalID = GetaField('statusawal', 'StatusAwalID', $StatusAwalID, 'Nama');
    die (ErrorMsg("Formulir Pendaftaran Tidak Ditemukan",
      "Tidak ditemukan pembelian formulir dengan nomer: <b>$PMBFormJualID</b>.<br />
      Calon Mahasiswa dengan status: $StatusAwalID - <b>$strStatusAwalID</b> harus membeli formulir pendaftaran terlebih dahulu."));
  }
  echo "<script>window.location='?';</script>";
}

?>