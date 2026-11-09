<?php
namespace App\Http\Controllers;
use App\Models\{LayananKategori,LayananDesa,Pengajuan,PengajuanRiwayat};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth,DB,Hash,Storage};
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
class LayananWargaController extends Controller {
    private function petugas(): void {abort_unless(in_array(Auth::user()?->role,['admin','petugas','kepala_desa'],true),403);}
    public function loginPage(){return view('layanan.login');}
    public function registerPage(){return view('layanan.register');}
    public function register(Request $r){$d=$r->validate(['name'=>'required|string|max:150','email'=>'required|email|unique:users,email','nik'=>'required|digits:16|unique:users,nik','alamat'=>'required|string|max:1500','password'=>'required|string|min:8|confirmed']);$u=\App\Models\User::create(['name'=>$d['name'],'email'=>$d['email'],'nik'=>$d['nik'],'alamat'=>$d['alamat'],'password'=>$d['password'],'role'=>'warga']);Auth::login($u);$r->session()->regenerate();return redirect()->route('warga.home');}
    public function login(Request $r){$d=$r->validate(['email'=>'required|email','password'=>'required']);if(!Auth::attempt($d)){return back()->withErrors(['email'=>'Email atau kata sandi tidak sesuai.'])->onlyInput('email');}$r->session()->regenerate();return redirect()->route(in_array(Auth::user()->role,['admin','petugas','kepala_desa'],true)?'petugas.home':'warga.home');}
    public function logout(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('layanan.login');}
    public function home(Request $r){$this->warga();$categories=LayananKategori::withCount(['layanan as aktif_count'=>fn($q)=>$q->where('status','aktif')])->get();return view('layanan.home',compact('categories'));}
    private function warga(): void {abort_unless(Auth::user()?->role==='warga',403);}
    public function category(LayananKategori $category){$this->warga();$q=$category->layanan()->orderBy('nama');if(request('q'))$q->where('nama','like','%'.request('q').'%');$services=$q->get();return view('layanan.category',compact('category','services'));}
    public function apply(LayananDesa $service){$this->warga();abort_unless($service->status==='aktif' && count($service->form_schema??[])>0,404);return view('layanan.apply',compact('service'));}
    public function submit(Request $r,LayananDesa $service){
        $this->warga();abort_unless($service->status==='aktif' && count($service->form_schema??[])>0,404);
        $rules=['keperluan'=>'required|string|max:1500','setuju'=>'accepted','lampiran.*'=>'file|mimes:pdf,jpg,jpeg,png|max:4096'];
        foreach($service->form_schema as $field){$key=$field['key'];abort_unless(preg_match('/^[a-z][a-z0-9_]*$/',$key),500);$rule=($field['required']??false?'required':'nullable');$type=$field['type']??'text';$rule.='|'.($type==='date'?'date':($type==='number'?'numeric|min:0':'string|max:1500'));if(str_starts_with($type,'select:')){$rule .= '|in:'.str_replace('|',',',substr($type,7));}$rules['jawaban.'.$key]=$rule;}
        foreach($service->persyaratan??[] as $doc){if(!empty($doc['required']))$rules['lampiran.'.$doc['key']]='required|file|mimes:pdf,jpg,jpeg,png|max:4096';}
        $d=$r->validate($rules);
        $u=Auth::user();$p=DB::transaction(function()use($r,$service,$u,$d){
            $p=Pengajuan::create(['user_id'=>$u->id,'layanan_desa_id'=>$service->id,'nomor_pengajuan'=>(string)Str::uuid(),'status'=>'Diajukan','data_pemohon'=>['nama'=>$u->name,'nik'=>$u->nik,'alamat'=>$u->alamat,'email'=>$u->email,'keperluan'=>$d['keperluan']],'jawaban'=>$d['jawaban']??[]]);
            foreach($r->file('lampiran',[]) as $key=>$file){$p->lampirans()->create(['jenis'=>$key,'nama_asli'=>$file->getClientOriginalName(),'path'=>$file->store('lampiran-pengajuan/'.$p->id,'local')]);}
            $p->riwayats()->create(['actor_id'=>$u->id,'status'=>'Diajukan']);return $p;
        });return redirect()->route('warga.detail',$p)->with('success','Pengajuan berhasil dikirim.');
    }
    public function history(){ $this->warga();$items=Pengajuan::where('user_id',Auth::id())->with('layanan')->latest()->paginate(10);return view('layanan.history',compact('items'));}
    public function detail(Pengajuan $p){abort_unless($p->user_id===Auth::id()||in_array(Auth::user()->role,['admin','petugas','kepala_desa'],true),403);$p->load('layanan','lampirans','riwayats');return view('layanan.detail',compact('p'));}
    public function attachment(\App\Models\PengajuanLampiran $file){$p=$file->pengajuan;abort_unless($p->user_id===Auth::id()||in_array(Auth::user()->role,['admin','petugas','kepala_desa'],true),403);return Storage::disk('local')->download($file->path,basename($file->nama_asli));}
    public function hasil(Pengajuan $p){abort_unless($p->user_id===Auth::id()||in_array(Auth::user()->role,['admin','petugas','kepala_desa'],true),403);abort_unless($p->status==='Selesai' && $p->hasil_path,404);return Storage::disk('local')->download($p->hasil_path);}
    public function repair(Request $r,Pengajuan $p){$this->warga();abort_unless($p->user_id===Auth::id()&&$p->status==='Perlu Perbaikan',403);$schema=$p->layanan->form_schema??[];$rules=['lampiran.*'=>'file|mimes:pdf,jpg,jpeg,png|max:4096'];foreach($schema as $f){$rules['jawaban.'.$f['key']]=($f['required']??false?'required':'nullable').'|'.(($f['type']??'')==='number'?'numeric|min:0':(($f['type']??'')==='date'?'date':'string|max:1500'));}$d=$r->validate($rules);DB::transaction(function()use($p,$r,$d){$p->update(['jawaban'=>$d['jawaban']??[],'status'=>'Diajukan']);foreach($r->file('lampiran',[]) as $key=>$file){$p->lampirans()->create(['jenis'=>$key,'nama_asli'=>$file->getClientOriginalName(),'path'=>$file->store('lampiran-pengajuan/'.$p->id,'local')]);}$p->riwayats()->create(['actor_id'=>Auth::id(),'status'=>'Diajukan','catatan'=>'Perbaikan dikirim ulang']);});return redirect()->route('warga.detail',$p)->with('success','Perbaikan dikirim.');}
    public function adminHome(){ $this->petugas();$items=Pengajuan::with('layanan','warga')->when(request('status'),fn($q)=>$q->where('status',request('status')))->latest()->paginate(15);return view('layanan.admin',compact('items'));}
    public function update(Request $r,Pengajuan $p){$this->petugas();$d=$r->validate(['status'=>['required',Rule::in(['Dalam Verifikasi','Perlu Perbaikan','Diproses','Disetujui','Ditolak','Selesai'])],'catatan'=>'nullable|string|max:2000','hasil'=>'nullable|file|mimes:pdf|max:10240']);if(in_array($d['status'],['Perlu Perbaikan','Ditolak'],true) && empty($d['catatan']))return back()->withErrors(['catatan'=>'Catatan diperlukan untuk perbaikan atau penolakan.']);if($d['status']==='Selesai'&&!$p->hasil_path&&!$r->hasFile('hasil'))return back()->withErrors(['hasil'=>'Unggah dokumen PDF resmi sebelum menyelesaikan pengajuan.']);DB::transaction(function()use($r,$p,$d){$data=['status'=>$d['status'],'catatan_petugas'=>$d['catatan']??null];if($r->hasFile('hasil'))$data['hasil_path']=$r->file('hasil')->store('hasil-pengajuan/'.$p->id,'local');$p->update($data);$p->riwayats()->create(['actor_id'=>Auth::id(),'status'=>$d['status'],'catatan'=>$d['catatan']??null]);});return back()->with('success','Status diperbarui.');}
}
