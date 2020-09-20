<?php

namespace Models;

use DateTime;

class Penduduk extends \Model
{
	protected $table;

	public function __construct()
	{
		parent::__construct();
		$this->table = 'data_penduduk';
	}

	public function getDaftarPenduduk()
	{
		$this->db->query("SELECT * FROM $this->table INNER JOIN hubungan_keluarga USING(id_stat_hbkel)");
		$daftarPenduduk = $this->db->result();

		foreach ($daftarPenduduk as $k => $v) {
			$infoNik = $this->extractInfoNik($v['nik']);

			$daftarPenduduk[$k] = array_merge($daftarPenduduk[$k], $infoNik);
		}

		return $daftarPenduduk;
	}

	private function extractInfoNik($nik)
	{
		$birthCode = substr($nik, 6, 6);

		// Determine birthdate and gender
		if ($birthCode > 400000) {
			$gender = 'Perempuan';
			$birthDate = str_pad(substr($birthCode, 0, 2) - 40, 2, '0', STR_PAD_LEFT) . substr($birthCode, 2);
		} else {
			$gender = 'Laki-Laki';
			$birthDate = $birthCode;
		}

		// This if block below for override php default behavior for handling 2 digits year number
		// Default range is 1970-2069. And will change to 1945-2044
		if (substr($birthDate, 4) < 45) {
			$birthDate = substr($birthDate, 0, 4) . '20' . substr($birthDate, 4);
		} else {
			$birthDate = substr($birthDate, 0, 4) . '19' . substr($birthDate, 4);
		}

		$birthDate = DateTime::createFromFormat('dmY', $birthDate);

		$age = $birthDate->diff(new DateTime)->format('%y');

		return [
			'gender' => $gender,
			'birth_date' => $birthDate->format('d-m-Y'),
			'age' => $age
		];
	}
}