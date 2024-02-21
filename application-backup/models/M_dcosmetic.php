<?php
class M_dcosmetic extends CI_Model{
	function __construct(){
		parent::__construct();
	}

	function getIPv(){
		$ip = 'undefined';
	
		if(isset($_SERVER)){
		
			$ip = $_SERVER['REMOTE_ADDR'];
		
			if(isset($_SERVER['HTTP_X_FORWARDED_FOR'])){
			
				$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
			
			} elseif(isset($_SERVER['HTTP_CLIENT_IP'])){
			
				$ip = $_SERVER['HTTP_CLIENT_IP'];
			}
		
		} else{
		
			$ip = getenv('REMOTE_ADDR');
		
			if(getenv('HTTP_X_FORWARDED_FOR')){
			
				$ip = getenv('HTTP_X_FORWARDED_FOR');
			
			} elseif(getenv('HTTP_CLIENT_IP')){
			
				$ip = getenv('HTTP_CLIENT_IP');
			}
		}
	
		$ip = htmlspecialchars($ip, ENT_QUOTES, 'UTF-8');
		if($ip=="::1" && $this->session->userdata('Ip')==""){

			$curl = curl_init();

			curl_setopt_array($curl, array(
					CURLOPT_URL => "https://api.ipify.org/?format=json",
					CURLOPT_RETURNTRANSFER => true,
					CURLOPT_ENCODING => "",
					CURLOPT_MAXREDIRS => 10,
					CURLOPT_TIMEOUT => 60,
					CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
					CURLOPT_CUSTOMREQUEST => "GET",
					CURLOPT_POSTFIELDS => "",
					CURLOPT_HTTPHEADER => array(
						"Postman-Token: 977f873a-149f-459f-b4ca-21b8112f398a",
						"cache-control: no-cache"
					),
				));

			$response = curl_exec($curl);
			$err = curl_error($curl);

			curl_close($curl);

			if(!$err){
				$response = json_decode($response);
				$ip = $response->ip;
			}
			$this->session->set_userdata('Ip', $ip);
		}else{
			$ip = $this->session->userdata('Ip');
		}
		
		return $ip;
	}
	
	function getMac(){
		ob_start(); 
		system('ipconfig /all'); 
		$mycom=ob_get_contents();
		ob_clean(); 

		$findme = "Physical"; 
		$pmac 	= strpos($mycom, $findme); 
		$mac	= substr($mycom,($pmac+36),17); 

		return $mac; 
	}
	
	function create_seo($url){
		$string = str_replace(array('[\', \']'), '', $url);
		$string = preg_replace('/\[.*\]/U', '', $string);
		$string = preg_replace('/&(amp;)?#?[a-z0-9]+;/i', '-', $string);
		$string = preg_replace(array('/[^a-z0-9]/i', '/[-]+/') , '-', $string);
		$pre_slug= strtolower(trim($string, '-'));
    
		$slug=$pre_slug.'.html'; // tambahkan ektensi .html pada slug
		return $slug;
	}
	
	function removeTags($str){
		$result = strip_tags($str);
		return $result;
	}
	
	
	function set_Substring($text, $max=10){
		$str_len = strlen($text);
		if($str_len>$max){
			$arr = explode(" ", $text);
			$ret = "";
			for($i = 0; $i < count($arr); $i++){
				if($ret==""){
					$ret = $arr[$i]; if(strlen($ret)>$max){ $i=count($arr); }
				}else{
					if(strlen($ret." ".$arr[$i])<=$max){ $ret = $ret." ".$arr[$i]; }else{
						$i=count($arr);
					}
					
				}
			}
		}else{
			$ret = $text;
		}
		return $ret;
	}
	
	function strToDate($String){
		$String	= explode("/",$String);
		if(count($String)>=3){
			$ret = $String[2]."-".$String[1]."-".$String[0];
		}else{
			$ret = "";
		}
		return $ret;
	}
	
	
	function getUsernameID($IdUser){
		$get	= $this->db->query("SELECT * FROM `tbl_user` WHERE IdUser='".$this->db->escape_str($IdUser)."'");
		return $get;
	}
	
	function img_exist($url = NULL){
		if(!$url) return FALSE;

		$noimage = base_url("themes/images/icon/logo.png");

		$headers = get_headers($url);
		return stripos($headers[0], "200 OK") ? $url : $noimage;
	}
	
	function get_images($html_string){
		//Create a new DOMDocument object.
		$htmlDom = new DOMDocument;

		//Load the HTML string into our DOMDocument object.
		@$htmlDom->loadHTML($html_string);

		//Extract all img elements / tags from the HTML.
		$imageTags = $htmlDom->getElementsByTagName('img');

		//Create an array to add extracted images to.
		$extractedImages = array();

		//Loop through the image tags that DOMDocument found.
		foreach($imageTags as $imageTag){

			//Get the src attribute of the image.
			$imgSrc = $imageTag->getAttribute('src');
			$imgSrc	= $this->img_exist($imgSrc);
			//Get the alt text of the image.
			$altText = $imageTag->getAttribute('alt');

			//Get the title text of the image, if it exists.
			$titleText = $imageTag->getAttribute('title');
			
			//Add the image details to our $extractedImages array.
			if(base_url("themes/images/icon/logo.png")!=$imgSrc){
				$extractedImages[] = array(
					'src' => $imgSrc,
					'alt' => $altText,
					'title' => $titleText
				);
			}
		}

		return $extractedImages;
	}
	
	
	function getProduk($Limit="", $Offset=""){
		$Limit_Data = "LIMIT 30";
		if($Offset!=""){ $Limit_Data = "LIMIT $Limit, $Offset"; }
		$pencarian	= $this->db->escape_str($this->input->get("pencarian"));
		$pecah	= explode(" ",$pencarian);
		$Where = "";
		for($y=0; $y<count($pecah)-1; $y++){
			if($Where==""){
				$Where = "tk.Kata_Kunci like '%".$pecah[$y]." ".$pecah[$y+1]."%'";
			}else{
				$Where = $Where." OR tk.Kata_Kunci like '%".$pecah[$y]." ".$pecah[$y+1]."%'";
			}
		}
		if($Where!=""){
			$Where = "and ($Where)";
		}
		
		
		if($pencarian!=""){
			$get = $this->db->query("select 
				vb.IdBlog,
				vb.Nama as Title,
				vb.Blog_Url,
				vb.Konten,
				vb.Tanggal,
				if(vb.Tahun>1, date_format(vb.Tanggal,'%d-%b-%Y'),
				if(vb.Bulan>1 or vb.Hari>=1, date_format(vb.Tanggal,'%d-%b'),
				if(vb.Jam>1, concat(vb.Jam, ' Hours Ago'),
				concat(vb.Menit,' Minutes Ago')))) as Waktu
				from v_blog vb
				left join tbl_keyword tk on tk.IdBlog=vb.IdBlog
				WHERE vb.JPost='P'
				$Where
				GROUP BY vb.IdBlog
				
				Limit 15");
		}else{
			
		
			$get = $this->db->query("select 
				IdBlog,
				Nama as Title,
				Blog_Url,
				Konten,
				Tanggal,
				if(Tahun>1, date_format(Tanggal,'%d-%b-%Y'),
				if(Bulan>1 or Hari>=1, date_format(Tanggal,'%d-%b'),
				if(Jam>1, concat(Jam, ' Hours Ago'),
				concat(Menit,' Minutes Ago')))) as Waktu
				from v_blog
				WHERE JPost='P'
				ORDER BY Tanggal DESC
				$Limit_Data");
		}
		
		return $get;
	}
	
	function getProdukSidebar($Blog_Url=""){
		$Colom = "IdBlog,
		Nama as Title,
		Blog_Url,
		Konten,
		Tanggal,
		if(Tahun>1, date_format(Tanggal,'%d-%b-%Y'),
		if(Bulan>1 or Hari>=1, date_format(Tanggal,'%d-%b'),
		if(Jam>1, concat(Jam, ' Hours Ago'),
		concat(Menit,' Minutes Ago')))) as Waktu";
		$get = $this->db->query("select 
			$Colom
			from v_blog
			WHERE JPost='P'
			AND Blog_Url!='".$this->db->escape_str($Blog_Url)."'
			ORDER BY Tanggal DESC LIMIT 10");
		return $get;
	}
	
	function remove_img($content){
		$content = preg_replace("/<img[^>]+\>/i", "", $content);
		return $content; 
	}
	
	function toNum($data){
		$alphabet = array( 'a', 'b', 'c', 'd', 'e',
			'f', 'g', 'h', 'i', 'j',
			'k', 'l', 'm', 'n', 'o',
			'p', 'q', 'r', 's', 't',
			'u', 'v', 'w', 'x', 'y',
			'z');
		$alpha_flip = array_flip($alphabet);
		$return_value = -1;
		$length = strlen($data);
		for($i = 0; $i < $length; $i++){
			$return_value += ($alpha_flip[$data[$i]] + 1) * pow(26, ($length - $i - 1));
		}
		return $return_value;
	}

}
?>