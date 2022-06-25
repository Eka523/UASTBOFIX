<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class hiyorin extends CI_Controller {

	public function index()
	{
		$data = [
			'title'   => 'HiyorinJoki',
			'url'     => 'Hiyorin',
			'header' => 'Kelola Data Joki',
			'content' => 'view_hiyorin'
		];		

		$this->load->view('layout/index', $data, FALSE);
	}

	public function loadTable()
	{
		$select = '*';
		$table  = 'joki';

		$limit = [
			'start'  => $this->input->get('start'),
			'finish' => $this->input->get('length')
		];

		$like['data'][] = [
			'column' => 'id_joki, nama, daftar_joki, pembayaran, nomor_hp',
			'param'  => $this->input->get('search[value]')
		];

		$indexOrder = $this->input->get('order[0][column]');
		$order['data'][] = [
			'column' => $this->input->get('columns['.$indexOrder.'][name]'),
			'type'   => $this->input->get('order[0][dir]')
		];

		

		$totalData  = $this->query->dataComplete($select, $table, NULL, NULL, NULL, NULL, NULL);
		$filterData = $this->query->dataComplete($select, $table, NULL, $like, $order, NULL, NULL);
		$queryData  = $this->query->dataComplete($select, $table, $limit, $like, $order, NULL, NULL);

		$result['data'] = [];
		if($queryData <> FALSE) {
			$no = $limit['start'] + 1;

			foreach($queryData->result() as $query) {
				if($query->id_joki > 0) {
					$result['data'][] = [
						$no,
						$query->nama,
						$query->daftar_joki,
						$query->pembayaran,
						$query->nomor_hp,
						'
						<button onclick="showData('.$query->id_joki.')" class="btn btn-warning btn-circle btn-sm"><i class="fas fa-edit"></i></button>
						<button onclick="deleted('.$query->id_joki.')" class="btn btn-danger btn-circle btn-sm" title="Delete Data"><i class="fa fa-trash"></i></button>
						'
					];
					$no++;
				}
			}
		}

		$result['recordsTotal'] = 0;
		if($totalData <> FALSE) {
			$result['recordsTotal'] = $totalData->num_rows();
		}

		$result['recordsFiltered'] = 0;
		if($filterData <> FALSE) {
			$result['recordsFiltered'] = $filterData->num_rows();
		}

		echo json_encode($result);
	}

	public function showData($id)
	{
		$where = ['id_joki' => $id];
		$data  = $this->query->getData('*', 'joki', $where)->row();
		echo json_encode($data);
	}

	public function insertData()
	{
		$data = [
				'nama' => $this->input->post('nama'),
				'daftar_joki' => $this->input->post('daftar_joki'),
				'pembayaran' => $this->input->post('pembayaran'),
				'nomor_hp' => $this->input->post('nomor_hp')
		];

		$insert = $this->query->insert('joki', $data);
		if($insert) {
			$response['ping'] = 200;
		} else {
			$response['ping'] = 500;
		}

		echo json_encode($response);
	}

	public function updateData($id)
	{
		$data = [
			'nama' => $this->input->post('nama'),
				'daftar_joki' => $this->input->post('daftar_joki'),
				'pembayaran' => $this->input->post('pembayaran'),
				'nomor_hp' => $this->input->post('nomor_hp')
		];

		$table  = ['column' => 'id_joki', 'param' => $id, 'table' => 'joki'];
		$update = $this->query->update($table, $data);
		if($update) {
			$response['ping'] = 200;
		} else {
			$response['ping'] = 500;
		}

		echo json_encode($response);
	}

	public function deleted($id)
	{
		
		$where = [
			'id_joki' => $id
		];

		$deleteData = $this->query->delete('joki', $where);
		if($deleteData) {
			$response['ping'] = 200;
		} else {
			$response['ping'] = 500;
		}

		echo json_encode($response);
	}

}

/* End of file Guru.php */
/* Location: ./application/controllers/Guru.php */