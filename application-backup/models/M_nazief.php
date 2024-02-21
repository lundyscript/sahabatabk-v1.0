<?php

class M_nazief extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}

	function remove_html($string)
	{
		$string = strip_tags($string);
		$string = strtolower($string);
		return $string;
	}

	function stem_query($string)
	{
		$this->load->model("M_dcosmetic");
		$string = preg_replace('/[^A-Za-z0-9\  ]/', '', $string);
		$pencarian = $this->db->escape_str(str_replace(" ", ",", $string));

		$qterm = $this->db->query("SELECT
			SUBSTRING_INDEX(SUBSTRING_INDEX(DEMO.Keyword, ',', numbers.n), ',', -1) AS kata,
			(select tb.kata from tbl_buang tb where tb.kata=
			SUBSTRING_INDEX(SUBSTRING_INDEX(DEMO.Keyword, ',', numbers.n), ',', -1) limit 1) as buang
			FROM
			(SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5) numbers INNER JOIN 
			(select '" . $pencarian . "' as Keyword) DEMO
			ON CHAR_LENGTH(DEMO.Keyword)-CHAR_LENGTH(REPLACE(DEMO.Keyword, ',', '')) >= numbers.n-1
			having (kata!='' or kata!=' ') and buang is null
			
			ORDER BY n")->result();
		$keyword = "";
		foreach ($qterm as $key => $val) {
			$word = $this->stemming($val->kata);
			if ($keyword == "") {
				$keyword = $word;
			} else {
				$keyword = $keyword . " " . $word;
			}
		}
		$qterm = $this->db->query("SELECT
			SUBSTRING_INDEX(SUBSTRING_INDEX(DEMO.Keyword, ',', numbers.n), ',', -1) AS kata,
			(select tb.kata from tbl_buang tb where tb.kata=
			SUBSTRING_INDEX(SUBSTRING_INDEX(DEMO.Keyword, ',', numbers.n), ',', -1) limit 1) as buang,
			(select count(ts.Term) from tbl_stemming ts where ts.Term=SUBSTRING_INDEX(SUBSTRING_INDEX(DEMO.Keyword, ',', numbers.n), ',', -1)) as Stem
			FROM
			(SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5) numbers INNER JOIN 
			(select '" . $pencarian . "' as Keyword) DEMO
			ON CHAR_LENGTH(DEMO.Keyword)-CHAR_LENGTH(REPLACE(DEMO.Keyword, ',', '')) >= numbers.n-1
			having (kata!='' or kata!=' ') and buang is null and Stem is not null
			ORDER BY n");
		if ($qterm->num_rows() > 0) {
			$pencarian = $this->db->escape_str(str_replace(" ", ",", $keyword));
			$Mac = $this->M_dcosmetic->getMac();
			$this->db->query("DELETE FROM tbl_pencarian WHERE Mac='" . $Mac . "' OR TIMESTAMPDIFF(MINUTE,now(),Tanggal_Create)<=-5");
			$this->db->query("INSERT INTO tbl_pencarian (Mac, IdKeyword, IdBlog, Kesamaan, Tf, df, idf, LWF, TRQ)
				select 
				'" . $Mac . "' as Mac,
				tk.IdKeyword,
				tk.IdBlog,
			
				(select count(tk1.IdKeyword) from tbl_stemming ts1 
				left join tbl_keyword tk1 on tk1.IdKeyword=ts1.IdDokumen
				where ts1.Term in (SELECT
				SUBSTRING_INDEX(SUBSTRING_INDEX(DEMO.Keyword, ',', numbers.n), ',', -1) AS kata
				FROM
				(SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5) numbers INNER JOIN 
				(select '" . $pencarian . "' as Keyword) DEMO
				ON CHAR_LENGTH(DEMO.Keyword)-CHAR_LENGTH(REPLACE(DEMO.Keyword, ',', '')) >= numbers.n-1
				having (kata!='' or kata!=' ') and (select tb.kata from tbl_buang tb where tb.kata=
				SUBSTRING_INDEX(SUBSTRING_INDEX(DEMO.Keyword, ',', numbers.n), ',', -1) limit 1) is null
				ORDER BY n) and tk1.IdKeyword=tk.IdKeyword) as Kesamaan,
			
			
				@Tf:=(select count(tk1.IdBlog) from tbl_stemming ts1 
				left join tbl_keyword tk1 on tk1.IdKeyword=ts1.IdDokumen
				where ts1.Term=ts.Term and tk1.IdBlog=tk.IdBlog) as Tf,

				@df:=(select count(distinct(tk1.IdBlog)) from tbl_stemming ts1 
				left join tbl_keyword tk1 on tk1.IdKeyword=ts1.IdDokumen
				where ts1.Term in (SELECT
				SUBSTRING_INDEX(SUBSTRING_INDEX(DEMO.Keyword, ',', numbers.n), ',', -1) AS kata
				FROM
				(SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5) numbers INNER JOIN 
				(select '" . $pencarian . "' as Keyword) DEMO
				ON CHAR_LENGTH(DEMO.Keyword)-CHAR_LENGTH(REPLACE(DEMO.Keyword, ',', '')) >= numbers.n-1
				having (kata!='' or kata!=' ') and (select tb.kata from tbl_buang tb where tb.kata=
				SUBSTRING_INDEX(SUBSTRING_INDEX(DEMO.Keyword, ',', numbers.n), ',', -1) limit 1) is null
				ORDER BY n)) as df,

				@idf:=round(log10(cast(@df as decimal)/
				(select count(distinct(tk1.IdBlog)) from tbl_stemming ts1 
				left join tbl_keyword tk1 on tk1.IdKeyword=ts1.IdDokumen
				where ts1.Term=ts.Term))+1,3) as idf,


				@LWF:=round(1/(1+log10(@df/@Tf)),3) as LWF,

				(round(0.25*@LWF+(1-0.25)*@idf,3)) as Trq

				from tbl_stemming ts
				left join `tbl_keyword` `tk` ON `tk`.`IdKeyword` = `ts`.`IdDokumen`
				where ts.Term in (SELECT
				SUBSTRING_INDEX(SUBSTRING_INDEX(DEMO.Keyword, ',', numbers.n), ',', -1) AS kata
				FROM
				(SELECT 1 n UNION ALL SELECT 2 UNION ALL SELECT 3 UNION ALL SELECT 4 UNION ALL SELECT 5) numbers INNER JOIN 
				(select '" . $pencarian . "' as Keyword) DEMO
				ON CHAR_LENGTH(DEMO.Keyword)-CHAR_LENGTH(REPLACE(DEMO.Keyword, ',', '')) >= numbers.n-1
				having (kata!='' or kata!=' ') and (select tb.kata from tbl_buang tb where tb.kata=
				SUBSTRING_INDEX(SUBSTRING_INDEX(DEMO.Keyword, ',', numbers.n), ',', -1) limit 1) is null
				ORDER BY n)
				order by tk.IdKeyword");

			$return = $this->db->query("select tk.IdKeyword, tk.Kata_Kunci, avg(tp.TRQ) as Bobot
				from tbl_pencarian tp
				left join tbl_keyword tk on tk.IdKeyword=tp.IdKeyword
				WHERE tp.Mac='" . $Mac . "'
				group by tk.Kata_Kunci
				order by tp.Kesamaan desc, avg(tp.TRQ) asc
				limit 6 ");
			return $return;
		} else {
			$return = $this->db->query("select tk.IdKeyword, tk.Kata_Kunci, avg(tp.TRQ) as Bobot
				from tbl_pencarian tp
				left join tbl_keyword tk on tk.IdKeyword=tp.IdKeyword
				WHERE tp.Mac='No Data Ya'
				group by tk.Kata_Kunci
				order by tp.Kesamaan desc, avg(tp.TRQ) asc
				limit 6 ");
			return $return;
		}
	}

	function tokenizing($id_data, $string)
	{
		$string = $this->remove_html($string);
		$this->db->query("DELETE FROM tbl_tokenizing WHERE IdDokumen='" . $id_data . "'");
		$text = preg_replace('/[^A-Za-z0-9\  ]/', '', $string);
		$token = strtok($text, " ");
		while ($token !== false) {
			$term = $token;
			$this->db->query("INSERT INTO tbl_tokenizing SET IdDokumen='" . $id_data . "', Term='" . $term . "'");
			$token = strtok(" ");
		}
	}

	function filtering($id_data)
	{
		$this->db->query("DELETE FROM tbl_stopword WHERE IdDokumen='" . $id_data . "'");
		$this->db->query("INSERT INTO tbl_stopword (IdDokumen, Term)
			select tt.IdDokumen, tt.Term from tbl_tokenizing tt
			left join tbl_buang tb on tb.kata=tt.Term
			where tb.kata is null
			and tt.IdDokumen='" . $id_data . "'");
	}

	function root_word($id_data)
	{
		$this->db->query("DELETE FROM tbl_stemming WHERE IdDokumen='" . $id_data . "'");
		$row = $this->db->query("select tt.IdDokumen, tt.Term from tbl_stopword tt where tt.IdDokumen='" . $id_data . "'")->result();
		foreach ($row as $key => $val) {
			$word = $this->stemming($val->Term);
			if ($word != "" && $word != " ") {
				$this->db->query("INSERT INTO tbl_stemming SET IdDokumen='" . $id_data . "', Term='" . $word . "'");
			}
		}
	}

	//fungsi untuk mengecek kata dalam tabel dictionary
	function cekKamus($kata)
	{
		$sql = $this->db->query("SELECT * FROM tbl_kbbi WHERE word = '" . $this->db->escape_str($kata) . "' LIMIT 1");
		$result = $sql->num_rows;
		if ($result == 1) {
			return true; // True jika ada
		} else {
			return false; // jika tidak ada FALSE
		}
	}

	//fungsi untuk menghapus suffix seperti -ku, -mu, -kah, dsb
	function Del_Inflection_Suffixes($kata)
	{
		$kataAsal = $kata;

		if (preg_match('/([km]u|nya|[kl]ah|pun)\z/i', $kata)) { // Cek Inflection Suffixes
			$__kata = preg_replace('/([km]u|nya|[kl]ah|pun)\z/i', '', $kata);

			return $__kata;
		}
		return $kataAsal;
	}

	// Cek Prefix Disallowed Sufixes (Kombinasi Awalan dan Akhiran yang tidak diizinkan)
	function Cek_Prefix_Disallowed_Sufixes($kata)
	{

		if (preg_match('/^(be)[[:alpha:]]+/(i)\z/i', $kata)) { // be- dan -i
			return true;
		}

		if (preg_match('/^(se)[[:alpha:]]+/(i|kan)\z/i', $kata)) { // se- dan -i,-kan
			return true;
		}

		if (preg_match('/^(di)[[:alpha:]]+/(an)\z/i', $kata)) { // di- dan -an
			return true;
		}

		if (preg_match('/^(me)[[:alpha:]]+/(an)\z/i', $kata)) { // me- dan -an
			return true;
		}

		if (preg_match('/^(ke)[[:alpha:]]+/(i|kan)\z/i', $kata)) { // ke- dan -i,-kan
			return true;
		}
		return false;
	}

	// Hapus Derivation Suffixes ("-i", "-an" atau "-kan")
	function Del_Derivation_Suffixes($kata)
	{
		$kataAsal = $kata;
		if (preg_match('/(i|an)\z/i', $kata)) { // Cek Suffixes
			$__kata = preg_replace('/(i|an)\z/i', '', $kata);
			if ($this->cekKamus($__kata)) { // Cek Kamus
				return $__kata;
			} else if (preg_match('/(kan)\z/i', $kata)) {
				$__kata = preg_replace('/(kan)\z/i', '', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata;
				}
			}
			/*– Jika Tidak ditemukan di kamus –*/
		}
		return $kataAsal;
	}

	// Hapus Derivation Prefix ("di-", "ke-", "se-", "te-", "be-", "me-", atau "pe-")
	function Del_Derivation_Prefix($kata)
	{
		$kataAsal = $kata;

		/* —— Tentukan Tipe Awalan ————*/
		if (preg_match('/^(di|[ks]e)/', $kata)) { // Jika di-,ke-,se-
			$__kata = preg_replace('/^(di|[ks]e)/', '', $kata);

			if ($this->cekKamus($__kata)) {
				return $__kata;
			}

			$__kata__ = $this->Del_Derivation_Suffixes($__kata);

			if ($this->cekKamus($__kata__)) {
				return $__kata__;
			}

			if (preg_match('/^(diper)/', $kata)) { //diper-
				$__kata = preg_replace('/^(diper)/', '', $kata);
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);

				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}

			}

			if (preg_match('/^(ke[bt]er)/', $kata)) {  //keber- dan keter-
				$__kata = preg_replace('/^(ke[bt]er)/', '', $kata);
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);

				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}

		}

		if (preg_match('/^([bt]e)/', $kata)) { //Jika awalannya adalah "te-","ter-", "be-","ber-"

			$__kata = preg_replace('/^([bt]e)/', '', $kata);
			if ($this->cekKamus($__kata)) {
				return $__kata; // Jika ada balik
			}

			$__kata = preg_replace('/^([bt]e[lr])/', '', $kata);
			if ($this->cekKamus($__kata)) {
				return $__kata; // Jika ada balik
			}

			$__kata__ = $this->Del_Derivation_Suffixes($__kata);
			if ($this->cekKamus($__kata__)) {
				return $__kata__;
			}
		}

		if (preg_match('/^([mp]e)/', $kata)) {
			$__kata = preg_replace('/^([mp]e)/', '', $kata);
			if ($this->cekKamus($__kata)) {
				return $__kata; // Jika ada balik
			}
			$__kata__ = $this->Del_Derivation_Suffixes($__kata);
			if ($this->cekKamus($__kata__)) {
				return $__kata__;
			}

			if (preg_match('/^(memper)/', $kata)) {
				$__kata = preg_replace('/^(memper)/', '', $kata);
				if ($this->cekKamus($kata)) {
					return $__kata;
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}

			if (preg_match('/^([mp]eng)/', $kata)) {
				$__kata = preg_replace('/^([mp]eng)/', '', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}

				$__kata = preg_replace('/^([mp]eng)/', 'k', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}

			if (preg_match('/^([mp]eny)/', $kata)) {
				$__kata = preg_replace('/^([mp]eny)/', 's', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}

			if (preg_match('/^([mp]e[lr])/', $kata)) {
				$__kata = preg_replace('/^([mp]e[lr])/', '', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}

			if (preg_match('/^([mp]en)/', $kata)) {
				$__kata = preg_replace('/^([mp]en)/', 't', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}

				$__kata = preg_replace('/^([mp]en)/', '', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}

			if (preg_match('/^([mp]em)/', $kata)) {
				$__kata = preg_replace('/^([mp]em)/', '', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}
				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}

				$__kata = preg_replace('/^([mp]em)/', 'p', $kata);
				if ($this->cekKamus($__kata)) {
					return $__kata; // Jika ada balik
				}

				$__kata__ = $this->Del_Derivation_Suffixes($__kata);
				if ($this->cekKamus($__kata__)) {
					return $__kata__;
				}
			}
		}
		return $kataAsal;
	}

	//fungsi pencarian akar kata
	function stemming($kata)
	{

		$kataAsal = $kata;

		$cekKata = $this->cekKamus($kata);
		if ($cekKata == true) { // Cek Kamus
			return $kata; // Jika Ada maka kata tersebut adalah kata dasar
		} else { //jika tidak ada dalam kamus maka dilakukan stemming
			$kata = $this->Del_Inflection_Suffixes($kata);
			if ($this->cekKamus($kata)) {
				return $kata;
			}

			$kata = $this->Del_Derivation_Suffixes($kata);
			if ($this->cekKamus($kata)) {
				return $kata;
			}

			$kata = $this->Del_Derivation_Prefix($kata);
			if ($this->cekKamus($kata)) {
				return $kata;
			} else {
				return $kata;
			}
		}
	}
}

?>
