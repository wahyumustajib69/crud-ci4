<?php 
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\PostModel;

class Post extends Controller{

	public function index()
	{
		$postModel = new PostModel();

		$pager =\Config\Services::pager();

		$data = array(
			'posts' => $postModel->paginate(3, 'post'),
			'pager' => $postModel->pager
		);

		return view('post-index', $data);
	}

	public function create()
	{
	
		return view('post-create');
	}

	public function store()
	{
		//load helper form dan url
		helper(['form','url']);

		//define validation
		$validation = $this->validate([
			'title' =>[
				'rules' => 'required',
				'errors' => ['required'=>'Masukkan Judul']
			],
			'content' =>[
				'rules' => 'required',
				'errors' => ['required'=>'Masukkan Isi Post']
			],
		]);

		if(!$validation){
			return view('post-create',['validation' => $this->validator]);
		}else{
			$postModel = new PostModel();

			$postModel->insert([
				'title'	=> $this->request->getPost('title'),
				'content'=> $this->request->getPost('content'),
			]);

			session()->setFlashdata('message','Data Berhasil Disimpan');

			return redirect()->to(base_url('post'));
		}
	}

	public function edit($id)
	{
		$postModel = new PostModel();

		$data = array(
			'post' => $postModel->find($id)
		);
		return view ('post-edit', $data);
	}

	public function update($id)
	{
		helper(['form','url']);

		$validation = $this->validate([
			'title' =>[
				'rules' => 'required',
				'errors' => ['required'=>'Masukkan Judul']
			],
			'content' =>[
				'rules' => 'required',
				'errors' => ['required'=>'Masukkan Isi Post']
			],
		]);	

		if(!$validation){
			return view('post-edit',['validation' => $this->validator]);
		}else{
			$postModel = new PostModel();

			$postModel->update($id, [
				'title'		=> $this->request->getPost('title'),
				'content'	=> $this->request->getPost('content'),
			]);

			session()->setFlashdata('message','Data Berhasil Diupdate');

			return redirect()->to(base_url('post'));
		}
	}

	public function delete($id)
	{
		$postModel = new PostModel;

		$post = $postModel->find($id);

		if($post){
			$postModel->delete($id);

			session()->setFlashdata('message','Hapus Data Berhasil');
			return redirect()->to(base_url('post'));
		}
	}

}
?>