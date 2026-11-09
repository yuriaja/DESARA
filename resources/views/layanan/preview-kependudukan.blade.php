@extends('layanan.layout')
@section('title','Demo Form - '.$service->nama)
@section('content')
<style>
.preview-banner{background:#eaf4ef;border:1px solid #bfd8cc;border-radius:15px;padding:15px 20px;margin:17px 0 22px;display:flex;gap:13px;align-items:flex-start}.preview-banner strong{color:#16573c}.preview-banner p{margin:3px 0 0}.preview-steps{display:flex;gap:8px;margin:22px 0}.preview-steps div{flex:1;padding:12px 10px;border:1px solid #dbe7df;background:white;border-radius:11px;font-size:13px;color:#63786c;text-align:center}.preview-steps div.current{background:#22694c;color:white;border-color:#22694c;font-weight:bold}.preview-steps div.passed{border-color:#90bea6;color:#22694c}.preview-panel{background:#fff;border:1px solid #dce7e0;border-radius:17px;padding:25px;margin:12px 0}.preview-panel h2{margin:0 0 6px}.preview-panel .intro{margin:0 0 21px}.preview-form-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:5px 19px}.preview-field.full{grid-column:1/-1}.preview-field label{margin-top:12px}.preview-field small{color:#667a6e;display:block;margin-top:5px}.preview-field em{color:#bb493d;font-style:normal}.preview-field input[readonly],.preview-field textarea[readonly]{background:#f2f5f2;color:#496454}.preview-file{display:block;border:1.5px dashed #a7c8b3;background:#f8fbf8;border-radius:12px;padding:15px;margin:10px 0 16px}.preview-file input{margin-top:8px}.preview-file p{font-size:12px;color:#658170;margin:7px 0 0}.preview-file .chosen{font-size:12px;color:#146045;font-weight:600}.preview-action{display:flex;justify-content:space-between;gap:10px;margin:18px 0 34px}.preview-action button{min-width:125px}.preview-review{display:grid;gap:9px}.preview-review article{border-bottom:1px solid #e6ece8;padding:9px 0}.preview-review article strong{display:block;color:#50685b;font-size:12px}.preview-review article span{white-space:pre-wrap;overflow-wrap:anywhere}.preview-check{display:flex;gap:10px;align-items:flex-start;margin:16px 0}.preview-check input{width:auto;margin-top:6px}.preview-info{display:flex;gap:11px;padding:12px 15px;border-radius:11px;background:#f4f7f4;color:#546b5e;font-size:13px}.preview-empty{color:#6b7f72}.preview-req{font-size:12px;color:#68786d}.preview-back{display:inline-block;margin-bottom:8px;text-decoration:none}@media(max-width:720px){.preview-form-grid{grid-template-columns:1fr}.preview-steps{gap:4px}.preview-steps div{font-size:11px;padding:9px 4px}.preview-panel{padding:17px}.preview-action button{min-width:unset}}
</style>
<a class="preview-back" href="{{route('warga.category',$service->kategori)}}">← Kembali ke Administrasi Kependudukan</a>
<p class="muted" style="margin:0 0 9px">Pelayanan Administrasi / Administrasi Kependudukan / Pratinjau Formulir</p>
<h1>{{ $service->nama }}</h1><p class="muted">{{ $spec['summary'] }}</p>
<div class="preview-banner"><span style="font-size:25px">🧪</span><div><strong>Mode Pratinjau Tampilan</strong><p>Formulir dapat dicoba, termasuk pilihan bersyarat dan upload foto/scan dokumen. Tidak ada data atau file yang dikirim ke server dan tidak ada pengajuan yang dibuat.</p></div></div>
<div class="preview-steps" id="previewSteps"><div class="current">01 · Data Pemohon</div><div>02 · Data Surat</div><div>03 · Upload Berkas</div><div>04 · Tinjau</div></div>
<form id="demoForm" novalidate>
  <section class="preview-panel" data-panel="0">
    <h2>Data Pemohon</h2><p class="muted intro">Data akun yang tersedia ditampilkan otomatis. Lengkapi data lainnya sebagai contoh tampilan.</p>
    <div class="preview-form-grid">
      <div class="preview-field"><label>Nama lengkap</label><input readonly value="{{auth()->user()->name}}"></div>
      <div class="preview-field"><label>NIK</label><input readonly value="{{auth()->user()->nik}}"></div>
      <div class="preview-field full"><label>Alamat sesuai profil</label><textarea readonly>{{auth()->user()->alamat}}</textarea></div>
      <div class="preview-field"><label>Nomor KK <em>*</em></label><input name="data_nomor_kk" inputmode="numeric" maxlength="16" minlength="16" pattern="[0-9]{16}" placeholder="16 digit nomor KK" required></div>
      <div class="preview-field"><label>Nomor HP aktif <em>*</em></label><input name="data_hp" inputmode="tel" type="tel" placeholder="08xxxxxxxxxx" required></div>
    </div><div class="preview-info" style="margin-top:17px">🔒 NIK dan nomor KK sebaiknya diproses sebagai teks. Form demo tidak menyimpan informasi yang kamu ketik.</div>
  </section>
  <section class="preview-panel hidden" data-panel="1">
    <h2>Informasi Khusus Surat</h2><p class="muted intro">Pertanyaan berikut disesuaikan dengan jenis surat yang kamu pilih.</p><div class="preview-form-grid" id="dynamicFields"></div>
  </section>
  <section class="preview-panel hidden" data-panel="2">
    <h2>Upload Dokumen Persyaratan</h2><p class="muted intro">Untuk KTP, KK, surat pengantar, dan dokumen lain, pilih foto/scan JPG, PNG, atau PDF. Maksimal 4 MB per file pada demo ini.</p><div id="dynamicDocs"></div>
    <div class="preview-info">📎 KTP/KK cukup berupa foto yang jelas dan terbaca untuk pratinjau. Saat fitur disambungkan ke backend, berkas harus disimpan secara privat dan diverifikasi petugas.</div>
  </section>
  <section class="preview-panel hidden" data-panel="3">
    <h2>Konfirmasi Data</h2><p class="muted intro">Periksa isian dan berkas yang dipilih. Ini bukan pengiriman pengajuan.</p><div id="reviewContent" class="preview-review"></div>
    <label class="preview-check"><input id="agree" type="checkbox">Saya sudah memeriksa data yang diisi (untuk simulasi).</label>
    <div class="preview-info">✅ Di aplikasi final, setelah konfirmasi sistem akan menyimpan pengajuan dan petugas memverifikasinya. Fitur tersebut tidak diaktifkan dalam pratinjau ini.</div>
  </section>
  <div class="preview-action"><button type="button" id="prevBtn" class="btn alt" hidden>← Sebelumnya</button><button type="button" id="nextBtn" class="btn">Selanjutnya →</button></div>
</form>
<script>
(()=>{
  'use strict';
  const spec = @json($spec);
  const form = document.getElementById('demoForm');
  const fieldWrap = document.getElementById('dynamicFields');
  const docWrap = document.getElementById('dynamicDocs');
  const panels = [...document.querySelectorAll('[data-panel]')];
  const steps = [...document.querySelectorAll('#previewSteps div')];
  const prev = document.getElementById('prevBtn');
  const next = document.getElementById('nextBtn');
  let current = 0;
  const e = (tag,attrs={},text)=>{const x=document.createElement(tag);for(const [k,v] of Object.entries(attrs)){if(k==='class')x.className=v;else x.setAttribute(k,v)}if(text!==undefined)x.textContent=text;return x};
  const controls = {};
  function matches(when){if(!when)return true;return (when.in||[]).includes(controls[when.key]?.value||'')}
  function makeField(f){
    const wrap=e('div',{class:'preview-field '+(f.type==='textarea'?'full':'')});wrap.dataset.field=f.key;
    const label=e('label',{},f.label);if(f.required)label.append(e('em',{},' *'));wrap.append(label);
    let input;
    if(f.type==='textarea'){input=e('textarea',{rows:'3',placeholder:'Isi '+f.label.toLowerCase()});}
    else if(f.type==='select'){input=e('select');input.append(e('option',{value:''},'Pilih salah satu...'));for(const opt of f.options||[])input.append(e('option',{value:opt},opt));}
    else input=e('input',{type:f.type==='date'?'date':f.type==='number'?'number':'text',placeholder:f.type==='text'?'Isi '+f.label.toLowerCase():''});
    input.id='field_'+f.key;input.name='demo_'+f.key;input.required=!!f.required;if(f.type==='number')input.min='0';controls[f.key]=input;wrap.append(input);
    if(f.help)wrap.append(e('small',{},f.help));fieldWrap.append(wrap);
    input.addEventListener('change',refreshConditions);input.addEventListener('input',refreshConditions);
  }
  const files={};
  function makeDoc(d){
    const wrap=e('div',{class:'preview-file'});wrap.dataset.doc=d.key;
    const label=e('label',{},d.label+(d.required?' *':' (opsional)'));
    label.style.margin='0';wrap.append(label);
    const input=e('input',{type:'file',accept:'.jpg,.jpeg,.png,.pdf,image/jpeg,image/png,application/pdf',name:'file_'+d.key});input.required=!!d.required;input.setAttribute('aria-label',d.label);wrap.append(input);
    const note=e('p',{},'Foto JPG/PNG atau dokumen PDF · Maksimum 4 MB');wrap.append(note);
    const chosen=e('div',{class:'chosen'});wrap.append(chosen);
    input.addEventListener('change',()=>{const f=input.files[0];chosen.textContent=f?'Dipilih: '+f.name+(f.size>4*1024*1024?' — melebihi 4 MB':''):'';});
    files[d.key]=input;docWrap.append(wrap);
  }
  (spec.fields||[]).forEach(makeField);(spec.docs||[]).forEach(makeDoc);
  function refreshConditions(){
    for(const f of spec.fields||[]){const active=matches(f.when),container=fieldWrap.querySelector(`[data-field="${f.key}"]`);container.hidden=!active;controls[f.key].required=active&&!!f.required;}
    for(const d of spec.docs||[]){const active=matches(d.when),container=docWrap.querySelector(`[data-doc="${d.key}"]`);container.hidden=!active;files[d.key].required=active&&!!d.required;}
  }
  function validatePanel(){
    const panel=panels[current];for(const input of panel.querySelectorAll('input,textarea,select')){
      if(input.closest('[hidden]')||input.type==='file'&&!input.files.length&&!input.required)continue;
      if(input.type==='file'&&input.files[0]&&input.files[0].size>4*1024*1024){input.setCustomValidity('Ukuran file maksimal 4 MB.');input.reportValidity();return false;}input.setCustomValidity('');
      if(!input.checkValidity()){input.reportValidity();return false;}
    }return true;
  }
  function showReview(){const out=document.getElementById('reviewContent');out.replaceChildren();
    const add=(k,v)=>{const item=e('article');item.append(e('strong',{},k),e('span',{},v||'—'));out.append(item)};
    add('Nama pemohon',@json(auth()->user()->name));add('NIK pemohon','••••••••••••'+String(@json(auth()->user()->nik)).slice(-4));
    add('Nomor KK','••••••••••••'+String(form.elements.data_nomor_kk.value).slice(-4));add('No. HP',form.elements.data_hp.value);
    for(const f of spec.fields||[])if(matches(f.when))add(f.label,controls[f.key].value);
    for(const d of spec.docs||[])if(matches(d.when))add(d.label,files[d.key].files[0]?.name||'Tidak dipilih');
  }
  function show(){panels.forEach((p,i)=>p.classList.toggle('hidden',i!==current));steps.forEach((p,i)=>{p.classList.toggle('current',i===current);p.classList.toggle('passed',i<current)});prev.hidden=current===0;next.textContent=current===3?'Selesai Lihat Demo ✓':'Selanjutnya →';if(current===3)showReview();}
  next.addEventListener('click',()=>{if(current===3){if(!document.getElementById('agree').checked){alert('Centang konfirmasi untuk menyelesaikan simulasi.');return;}alert('Simulasi selesai! Tidak ada data yang dikirim atau disimpan.');return;}if(!validatePanel())return;current++;show();window.scrollTo({top:0,behavior:'smooth'})});
  prev.addEventListener('click',()=>{current=Math.max(0,current-1);show();window.scrollTo({top:0,behavior:'smooth'})});
  form.addEventListener('submit',event=>event.preventDefault());refreshConditions();show();
})();
</script>
@endsection
