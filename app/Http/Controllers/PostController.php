<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

class PostController extends Controller
{
    public function index() //function yang akan dijalankan secara default
    {
        $posts = Post::latest()->get(); // mendapatkan data dari model Post dengan function get()
        //make response JSON
        return response()->json([ // mengubah data yang didapatkan dari model menjadi bentuk JSON
            'success' => true, // menginisialisasi isi data success dengan nilai TRUE
            'message' => 'List Data Post', // menginisialisasi isi pesan
            'data' => $posts // menginisialisasi data dengan isi dari yang didapatkan dari model
        ], 200);
    }

    public function show($id) // function untuk mengambil data dengan parameter id
    {
        $post = Post::findOrfail($id); // mendapatkan data dari model Post dengan function findOrfail($id)
        //make response JSON
        return response()->json([ // mengubah data yang didapatkan dari model menjadi bentuk JSON
            'success' => true, // menginisialisasi isi data success dengan nilai TRUE
            'message' => 'Detail Data Post', // menginisialisasi isi pesan
            'data' => $post // menginisalisasi data dengan isi dari yang didapatkan dari model
        ], 200);
    }

    public function store(Request $request) // function untuk melakukan penambahan data ke dalam database dengan parameter Request
    {
        //set validation
        $validator = Validator::make($request->all(), [ // membuat validasi/peraturan
            'title' => 'required', //peraturan bahwa judul/title harus berisi
            'context' => 'required', //peraturan bahwa context harus berisi juga agar data yang ada didalam context ini di masukkan ke dalam database
        ]);
        //response error validation
        if ($validator->fails()) { // melihat apakah validator bernilai salah atau tidak, jika salah maka akan menjalankan kondisi dibawah ini
            return response()->json($validator->errors(), 400); // mengembalikan JSON dengan pesan error, dan kode 400
        }
        //save to database
        $post = Post::create([ // memanggil function create dengan parameter array
            'title' => $request->title, // mendapatkan nilai title dari request yang diterima kemudian dimasukkan ke dalam field title
            'context' => $request->context // mendapatkan nilai context dari request yang diterima kemudian dimasukkan ke dalam field context
        ]);
        //success save to database
        if ($post) { // jika inputan berhasil, maka akan menjalankan sintaks dibawah
            return response()->json([ // mengirimkan JSON
                'success' => true, // menginisialisasi isi data success dengan nilai TRUE
                'message' => 'Post Created', // menginisialisasi isi pesan
                'data' => $post // menginisalisasi data dengan isi dari yang didapatkan dari model
            ], 201); // mengirimkan kode 201 ke penerima
        }
        //failed save to database
        return response()->json([ // jika inputan tidak berhasil, maka akan menjalankan sintaks dibawah
            'success' => false, // menginisialisasi isi data success dengan nilai FALSE
            'message' => 'Post Failed to Save', // menginisialisasi isi pesan
        ], 409); // mengirimkan kode 409 ke penerima
    }

    public function update(Request $request, Post $post) // function untuk melakukan perubahan data di dalam database dengan parameter Request dan Post
    {
        //set validation
        $validator = Validator::make($request->all(), [// membuat validasi/peraturan
            'title' => 'required', //peraturan bahwa judul/title harus berisi
            'context' => 'required', //peraturan bahwa context harus berisi juga agar data yang ada didalam context ini di masukkan ke dalam database
        ]);
        //response error validation
        if ($validator->fails()) { // melihat apakah validator bernilai salah atau tidak, jika salah maka akan menjalankan kondisi dibawah ini
            return response()->json($validator->errors(), 400); // mengembalikan JSON dengan pesan error, dan kode 400
        }
        //find post by ID
        $post = Post::findOrFail($post->id); // mendapatkan data dari model Post dengan function findOrfail($id)
        if ($post) { // jika post ada isinya/datanya ada, maka akan menjalankan perintah dibawah ini
            //update post
            $post->update([ // melakukan update
                'title' => $request->title, //dengan isi field dari title adalah JSON dari request yang dikirim dengan nama title
                'context' => $request->context // dengan isi field dari title adalah JSON dari request yang dikirim dengan nama context
            ]);
            return response()->json([ // mengembalikan JSON
                'success' => true, // dengan success nya adalah TRUE
                'message' => 'Post Updated', // dengan judul nya adalah berhasil di update
                'data' => $post // dengan mengirimkan data yang sudah diubah tadi
            ], 200); // mengirimkan kode 200
        }
        //data post not found
        return response()->json([ // jika perubahan tidak berhasil dilakukan, maka mengirimkan JSON
            'success' => false, // dengan pesan success nya adalah FALSE
            'message' => 'Post Not Found', // mengisi pesan kesalahan
        ], 404); // dengan kode 404
    }

    public function destroy($id) // function untuk melakukan penghapusan data di dalam database dengan parameter $id
    {
        //find post by ID
        $post = Post::findOrfail($id); // mendapatkan data
        if ($post) { // jika datanya ada, maka akan menjalankan perintah di dalam post
            //delete post
            $post->delete(); // melakukan perintah penghapusan
            return response()->json([ // mengirimkan data JSON
                'success' => true, // mengirimkan pesan sukses true
                'message' => 'Post Deleted', // mengirimkan pesan message dengan pesan data berhasil di hapus
            ], 200); // mengirimkan pesan 200
        }
        //data post not found
        return response()->json([ // mengirimkan data JSON
            'success' => false, // mengirimkan pesan sukses false
            'message' => 'Post Not Found', // mengirimkan pesan message dengan pesan data tidak berhasil di hapus
        ], 404); // mengirimkan pesan 404
    }
}
