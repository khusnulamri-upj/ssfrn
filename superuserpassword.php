<?php
// Author : Irvandy Goutama
// Email  : irvandygoutama@gmail.com
// Start  : 26 April 2009

// *** Parameters ***
if ($_SESSION['_LevelID'] == '29' or
	$_SESSION['_LevelID'] == '33' or
	$_SESSION['_LevelID'] == '100' or
	$_SESSION['_LevelID'] == '120')
  die(ErrorMsg('Error',
  "Anda tidak berhak menjalankan modul ini."));

$gos = (empty($_REQUEST['gos']))? 'FormPassword' : $_REQUEST['gos'];
$gos();
?>
<?
function FormPassword()
{
$LevelID = $_SESSION['_LevelID'];
$Login   = $_SESSION['_Login'];
$NamaKaryawan	= GetaField('karyawan', 'Login', $Login, 'Nama');	
$NamaLevel 		= GetaField('level', 'LevelID', $LevelID, 'Nama');
TampilkanJudul("Ubah Password $NamaLevel");
?>

<form action='?' method='POST' onSubmit='return CekPassword(this)'>
<input type=hidden name='gos' value='Simpan' />
<table class=box cellspacing=1 align=center width=600>  
  <tr>
      <td class=inp width=80>Nama:</td>
      <td class=ul width=80><b><?=$NamaKaryawan?></b>&nbsp;</td>
      </tr>
  <tr>
	  <td class=inp>Level:</td>
	  <td class=ul><?=$LevelID?> - <?=$NamaLevel?></td>
	  </tr>
  <tr><td class=inp>Password Lama:</td>
	  <td class=ul1><input type=password name='PasswordLama' size=20 maxlength=20></td>
	  </tr>
  <tr><td class=inp valign=top>Password Baru:</td>
      <td class=ul valign=top>
        <input type=password name='PasswordBaru1' size=20 maxlength=20 />
      </td>
  </tr>
  </tr>
      <td class=inp valign=top>Password Baru:</td>
      <td class=ul valign=top>
        <input type=password name='PasswordBaru2' size=20 maxlength=20/><br />
        <sup>Tuliskan password baru sekali lagi</sup>
      </td>
  </tr>
  <tr>
    <td class=ul colspan=4 align=center>
      <input type='Submit' name='Submit' value='Simpan Password Baru' />
    </td>
  </tr>
</table>
</form>
<script>
	function CekPassword(frm)
	{	
		var pesan = "";
		var UpperChar = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
		var LowerChar = 'abcdefghijklmnopqrstuvwxyz';
		var IntegerChar = '01233456789';
							
		if (frm.PasswordBaru1.value == '' || frm.PasswordBaru2.value == '')
		  pesan += "Password tidak boleh kosong. \n";
		if (frm.PasswordBaru1.value.length > 10)
		  pesan += "Password harus maksimal 10 karakter. \n";
		if (frm.PasswordBaru1.value != frm.PasswordBaru2.value)
		  pesan += "Ketikkan password baru yang sama 2 kali. \n";
		
		var ada = 'N';
		ada = 'N';
		for (var i = 0; i < frm.PasswordBaru1.value.length; i++) {
			if (UpperChar.indexOf(frm.PasswordBaru1.value.charAt(i)) != -1)
			{	ada = 'Y';
				break;
			}
		}
		if (ada == 'N')
		   pesan += "Password harus mengandung minimal 1 huruf kapital (contoh: A, B, ..)\n";
		  
		ada = 'N';
		for (var i = 0; i < frm.PasswordBaru1.value.length; i++) {
			if (LowerChar.indexOf(frm.PasswordBaru1.value.charAt(i)) != -1)
			{	ada = 'Y';
				break;
			}
		}
		if (ada == 'N')
		  pesan += "Password harus mengandung minimal 1 huruf tidak kapital (contoh: a, b, ..)\n";
		
		ada = 'N';
		for (var i = 0; i < frm.PasswordBaru1.value.length; i++) {
			if (IntegerChar.indexOf(frm.PasswordBaru1.value.charAt(i)) != -1)
			{	ada = 'Y';
				break;
			}
		}
		if (ada == 'N')
		  pesan += "Password harus mengandung minimal 1 angka (contoh: a, b, ..)\n";
		
		if (pesan != "") alert(pesan);
		return pesan == "";
	}
</script>

<?
}

function Simpan()
{	$pl = $_POST['PasswordLama'];
	$PasswordLama = GetaField('karyawan', "Login='$_SESSION[_Login]' and KodeID", KodeID, "Password");
	if(GetaField('karyawan', "KodeID", KodeID, "LEFT(PASSWORD('$pl'), 10)") != $PasswordLama)
		die(ErrorMsg("Password Lama Tidak Sesuai", "Anda memasukkan password lama yang salah.<br>
						Bila anda lupa password anda, harap menghubungi Sistem Administrstor untuk mereset password anda<br>
						<input type=button name='CobaLagi' value='Coba Lagi' onClick=\"location='?mnux=$_SESSION[mnux]&gos='\">"));
		
	$p1 = $_POST['PasswordBaru1'];
	$p2 = $_POST['PasswordBaru2'];
	
	$ErrorList = array();
	
	if(empty($p1))
		$ErrorList[] = "Password Baru tidak boleh kosong.";
	if(empty($p2))
		$ErrorList[] = "Password Baru ulang tidak boleh kosong.";
	if(strlen($p1) > 10)
		$ErrorList[] = "Password harus maksimal 10 karakter";
	if($p2 != $p1)
		$ErrorList[] = "Password Baru harus sama dengan pengulangannya.";
	
	if(!empty($ErrorList)) {	
		$Message = '';
		foreach($ErrorList as $el)
			$Message .= "&bull; $el<br>";		
		die(ErrorMsg("Password Gagal Disimpan", "Terdapat beberapa kekurangan dari password yang anda masukkan:<br>$Message<br><input type=button name='CobaLagi' value='Coba Lagi' onClick=\"location='?mnux=$_SESSION[mnux]&gos='\">"));
	} else {	
		$s2 = "UPDATE `karyawan` SET `Password` = LEFT(PASSWORD( '$p1' ), 10) WHERE Login = '$_SESSION[_Login]' LIMIT 1";
		$q2 = _query($s2); 
		if($q2)
			BerhasilSimpan("?mnux=$_SESSION[mnux]&gos=", 1000); 		
	}
}
?>

