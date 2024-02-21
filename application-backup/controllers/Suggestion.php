<?php

class Suggestion extends CI_Controller
{
	function autocomplete()
	{
//		if ($this->input->server('REQUEST_METHOD') == 'POST') {
		$this->load->model("M_nazief");
		$keyword = $this->input->get("search");
		$data = $this->M_nazief->stem_query($keyword);
		echo "<ul style='position: absolute;
    z-index: 9999999;
    display: block;
    width: 100%;
    min-width: 100%;
    margin: 2px 0 0;
    padding: 10px;
    font-size: 14px;
    text-align: left;
    list-style: none;
    background-color: #fff;
    background-clip: padding-box;
    border: 1px solid rgba(0,0,0,.15);
    border-radius: 0px;
    -webkit-box-shadow: 0 6px 12px rgba(0,0,0,.175);
    box-shadow: 0 6px 12px rgba(0,0,0,.175);
    max-height: 10rem;
    overflow-x: auto;'>";
		foreach ($data->result() as $row) {
//			echo "<li style='border-bottom: 1px solid silver; padding: 5px; cursor: pointer;'>" . anchor(base_url("search?q=" . urlencode($row->Kata_Kunci)), $row->Kata_Kunci) . "</li>";
			echo "<a href='" . base_url('search?q=' . urlencode($row->Kata_Kunci)) . "' style='padding: 5px; cursor: pointer; font-size: 17px !important;'>" . $row->Kata_Kunci . "</a><div style='width: 100%; border-bottom: 1px solid silver; height: 5px;'></div>";
		}
		echo "</ul>";
	}
//	}
}
