const P={dash:"M4 4h7v7H4z|M13 4h7v4h-7z|M13 11h7v9h-7z|M4 14h7v6H4z",plus:"M12 5v14|M5 12h14",arch:"M3 7h6l2 2h10v11H3z",types:"M4 6h16|M4 12h16|M4 18h10",book:"M4 5a2 2 0 012-2h13v16H6a2 2 0 00-2 2z|M4 19V5",out:"M9 4H5v16h4|M16 8l4 4-4 4|M20 12H9",search:"M11 4a7 7 0 100 14 7 7 0 000-14|M20 20l-4-4",eye:"M2 12s4-7 10-7 10 7 10 7-4 7-10 7S2 12 2 12|M12 9a3 3 0 100 6 3 3 0 000-6",off:"M3 3l18 18|M10 6a9 9 0 0112 6 9 9 0 01-3 3.5|M6 7.5A9 9 0 002 12s4 7 10 7c1.5 0 2.8-.4 4-1",check:"M5 12l5 5L20 7",print:"M7 9V3h10v6|M7 17H4v-7h16v7h-3|M7 14h10v7H7z",dl:"M12 4v11|M7 11l5 5 5-5|M5 20h14",save:"M5 4h11l3 3v13H5z|M8 4v5h7V4|M8 20v-6h8v6",menu:"M4 6h16|M4 12h16|M4 18h16",doc:"M7 3h7l5 5v13H7z|M14 3v5h5|M9.5 13h6|M9.5 17h6",home:"M3 11l9-8 9 8|M5 10v10h14V10|M10 20v-6h4v6",shop:"M4 9l1-5h14l1 5|M4 9v11h16V9|M9 20v-6h6v6",users:"M9 11a3 3 0 100-6 3 3 0 000 6|M3 20c0-3.5 3-6 6-6s6 2.5 6 6|M17 5a3 3 0 010 6|M18 14c2 .6 3 2.7 3 6",leaf:"M12 21V11|M12 11c0-4 3-6 7-6 0 4-3 6-7 6|M12 14c0-3-2-5-6-5 0 3 2 5 6 5",shield:"M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z|M8.5 12l2.5 2.5 4.5-5",l:"M15 6l-6 6 6 6",r:"M9 6l6 6-6 6"};
const I=(n,s=20)=>`<svg class="i" width="${s}" height="${s}" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">${P[n].split('|').map(d=>`<path d="${d}"/>`).join('')}</svg>`;
const LG={mg:"images/logo-magetan.png",un:"images/logo-unesa.png"};
P.moon="M20 14A8 8 0 1110 4a7 7 0 0010 10z";
const IM=(k,h)=>`<img src="${LG[k]}" height="${h}" style="width:auto" alt="${k=='mg'?'Lambang Kabupaten Magetan':'Logo UNESA'}">`;
const LOGO=s=>`<svg width="${s}" height="${s}" viewBox="0 0 64 64" role="img" aria-label="Logo DESARA"><rect width="64" height="64" rx="18" fill="#1f1c18"/><path d="M25 14H30C40.5 14 48 21 48 32C48 43 40.5 50 30 50H16V23Z" fill="#fbf6ea"/><path d="M25 14L16 23H25Z" fill="#e2a648"/><path d="M27 50C27 45 29 42 31 42C33 42 35 45 35 50Z" fill="#0e9f6e"/><path d="M23 50C23 42 26.500 37 31 37C35.500 37 39 42 39 50M19 50C19 39 24.500 32 31 32C37.500 32 43 39 43 50" fill="none" stroke="#1f1c18" stroke-width="1.800" stroke-linecap="round"/><circle cx="31" cy="24.500" r="3.400" fill="#d9922b"/><circle cx="31" cy="24.500" r="6.400" fill="none" stroke="#d9922b" stroke-width="1" stroke-dasharray="1.600 1.900"/></svg>`;
const CT=(n=9)=>Array.from({length:n},(_,i)=>`<path transform="translate(200 150) scale(${.25+i*.16}) translate(-200 -150)" d="M40 170C60 90 130 60 190 80s110-40 160 30-10 130-90 140S30 240 40 170z" vector-effect="non-scaling-stroke"/>`).join('');
const $=s=>document.querySelector(s),esc=s=>String(s??'').replace(/[&<>"]/g,c=>({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;'}[c]));
const fd=d=>new Date(d).toLocaleDateString('id-ID',{day:'numeric',month:'long',year:'numeric'}),fmtDMY=d=>{if(!d)return '';const [y,m,day]=String(d).split('-');return day&&m&&y?`${day}-${m}-${y}`:''};
const nowLocal=new Date(),pad2=n=>String(n).padStart(2,'0'),TODAY=`${nowLocal.getFullYear()}-${pad2(nowLocal.getMonth()+1)}-${pad2(nowLocal.getDate())}`,CURRENT_YEAR=nowLocal.getFullYear();
// Template surat resmi berdasarkan dokumen Desa Milangasri yang diberikan.
// Catatan: bagian yang belum memiliki template PDF resmi tidak diisi dengan asumsi.
const LT=[
 {id:'ket',name:'Surat Keterangan',icon:'doc',
  description:'Surat keterangan serbaguna. Judul setelah “Surat Keterangan” diisi manual sesuai kebutuhan, misalnya Usaha atau Kehilangan.',
  purpose:'Digunakan untuk berbagai kebutuhan administratif warga dengan jenis keterangan ditulis manual sesuai keperluan.',
  example:'Contoh judul: Surat Keterangan Kehilangan atau Surat Keterangan Usaha.',
  status:'Template tersedia',template:'keterangan'},
 {id:'mat',name:'Surat Kematian',icon:'leaf',
  description:'Memuat tiga format sesuai dokumen: Surat Kematian baru, Surat Kematian lama/arsip, dan Surat Keterangan Kematian.',
  purpose:'Pilih format yang sesuai karena struktur surat dan kode penomorannya berbeda.',
  example:'Baru · Lama/Arsip · Surat Keterangan Kematian.',
  status:'3 format tersedia',template:'kematian'},
 {id:'pindah',name:'Surat Pengantar Pindah',icon:'l',
  description:'Surat pengantar perpindahan warga beserta formulir DATA DAERAH ASAL dan DATA DAERAH TUJUAN F-08/DAFDUK/2003.',
  purpose:'Digunakan untuk pengurusan administrasi warga yang pindah dari daerah asal ke daerah tujuan.',
  example:'Contoh alamat tujuan lengkap: dusun, RT/RW, desa/kelurahan, kecamatan, kabupaten/kota, provinsi, dan kode pos.',
  status:'2 halaman tersedia',template:'pindah'},
 {id:'bantuan',name:'Mengajukan Bantuan',icon:'shield',
  description:'Surat permohonan bantuan sosial dengan format sesuai contoh surat yang diberikan, termasuk tujuan surat, lampiran, uraian bantuan, dan tanda tangan.',
  purpose:'Digunakan untuk mengajukan bantuan sesuai kebutuhan, dengan tujuan surat dapat disesuaikan dengan instansi atau pihak penerima permohonan.',
  example:'Contoh: permohonan bantuan kursi roda dan sembako.',
  status:'Template tersedia',template:'bantuan'},
 {id:'rekening',name:'Surat Permohonan Membuka Rekening',icon:'book',
  description:'Surat perintah kepada dua orang pengurus untuk membuka rekening atas nama organisasi/majelis.',
  purpose:'Digunakan untuk memberikan perintah kepada pihak yang ditunjuk sebagai Ketua dan Bendahara untuk membuka rekening.',
  example:'Contoh: rekening Tabungan Bank JATIM atas nama Majelis Ta’lim AL-IKHLAS.',
  status:'Template tersedia',template:'rekening'}
];
const tn=id=>LT.find(t=>t.id==id)?.name||'';
const DEFAULT_ARS=Array.from({length:24},(_,i)=>({
 no:[`474.4/652/403.408.4/${CURRENT_YEAR}`,`474.3/109/403.408.04/${CURRENT_YEAR}`,`475/108/403.408.04/${CURRENT_YEAR}`][i%3],
 nama:['Desy Marlindawati','Warti','Clara Mega Amelia'][i%3],
 nik:['3577024412890001','3520000000000000','3520085909680001'][i%3],
 type:['ket','mat','pindah','bantuan','rekening'][i%5],
 tgl:new Date(nowLocal.getFullYear(),nowLocal.getMonth(),Math.max(1,nowLocal.getDate()-i)).toISOString().slice(0,10),st:'Selesai'
}));
const ARCHIVE_KEY='desara.archive.v18';
let ARS=(()=>{try{const raw=localStorage.getItem(ARCHIVE_KEY);const parsed=raw?JSON.parse(raw):null;return Array.isArray(parsed)&&parsed.length?parsed:DEFAULT_ARS}catch{return DEFAULT_ARS}})();
const persistArchive=()=>{try{localStorage.setItem(ARCHIVE_KEY,JSON.stringify(ARS))}catch{}};
const S0=()=>({step:1,type:null,subtype:null,nama:'',nik:'',alamat:'',nomorUrut:'',tgl:TODAY,z:1,saved:null,
 ttl:'',tempatLahir:'',tanggalLahir:'',pekerjaan:'',agama:'',statusKawin:'',jenisKelamin:'',judulKeterangan:'',isiKeterangan:'',keperluan:'',masaBerlaku:'',masaMulai:'',masaSelesai:'',
 pejabatNama:'',pejabatJabatan:'',
 umur:'',hari:'',jam:'',tanggalMeninggal:'',tempat:'',sebab:'',
 nikKK:'',noKKAsal:'',namaKK:'',alamatSekarang:'',alamatTujuan:'',
 rekeningPemberiNama:'',rekeningPemberiJabatan:'',rekeningOrang1Nama:'',rekeningOrang1NIK:'',rekeningOrang1Alamat:'',rekeningOrang1Jabatan:'',rekeningOrang1Telp:'',rekeningOrang2Nama:'',rekeningOrang2NIK:'',rekeningOrang2Alamat:'',rekeningOrang2Jabatan:'',rekeningOrang2Telp:'',
 bantuanTempatLahir:'',bantuanTanggalLahir:'',bantuanPekerjaan:'',bantuanAgama:'',bantuanStatusKawin:'',bantuanDetail:'',bantuanLampiran:'',bantuanSifat:'',bantuanPerihal:'',bantuanKepada:'',bantuanCq:'',bantuanPembuka:'',bantuanKeterangan:'',bantuanDaftarKebutuhan:[],bantuanDaftarLampiran:[],bantuanPenutup:'',bantuanTempatTanggal:'',bantuanMengetahui:'',
 alamatTujuan:'',jumlahPindah:'',alasanPindah:'',klasifikasi:'',jenisKepindahan:'',statusKKTidakPindah:'',statusKKPindah:'',rencanaTanggal:'',
 tujuanKK:'',tujuanNamaKK:'',tujuanNIKKK:'',tujuanStatusKK:'',tujuanTanggalDatang:'',tujuanAlamat:'',tujuanDsn:'',tujuanDesa:'',tujuanKec:'',tujuanKab:'',tujuanProv:'',tujuanKodePos:'',tujuanRT:'',tujuanRW:'',tujuanTelp:'',tujuanAnggotaNik:'',tujuanAnggotaNama:'',tujuanAnggotaShdk:'',ttdTempatTanggal:'',ttd2Label:'',ttd2Jabatan:'',ttd2Nama:'',pemohonNama:'',mengJabatan:'',mengNama:'',asalCamatNama:'',asalCamatNip:'',asalKadesJabatan:'',dtCamatNama:'',dtCamatNip:'',dtKadesJabatan:'',dtKadesNama:'',dtKadesNip:'',dtAlamat:'',dtRT:'',dtRW:'',dtDesa:'',dtKec:'',dtKab:'',dtProv:'',asalDsn:'',asalDesa:'Milangasri',asalKec:'Panekan',asalKab:'Magetan',asalProv:'Jawa Timur',asalKodePos:'',asalRT:'',asalRW:'',asalTelp:'',
 deathCertPejabatNama:'',deathCertPejabatAlamat:'',deathCertPejabatJabatan:'',deathCertNama:'',deathCertNIK:'',deathCertUmur:'',deathCertAgama:'',deathCertAlamat:'',deathCertHari:'',deathCertTanggal:'',deathCertPukul:'',deathCertSebab:'',deathCertTempat:'',rekeningKeterangan:'',anggotaNama:'',anggotaNik:'',shdk:'',anggota:[],tujuanAnggota:[]
});
let S=S0(),E={},F={q:'',t:'',d:''},view='dash';
const V={nama:v=>v.trim().length>1};
const MSG={nama:'Nama minimal 2 karakter.'};
const NAV=[['dash','Dashboard','dash'],['buat','Buat Surat','plus'],['arsip','Arsip Surat','arch'],['jenis','Jenis Surat','types'],['panduan','Panduan','book']];
function toast(m){const t=$('#ts');t.textContent=m;t.classList.add('on');clearTimeout(t.h);t.h=setTimeout(()=>t.classList.remove('on'),2600)}
/* components */
const lcard=(t,sel,ex='')=>`<button class="lc ${sel?'sel':''}" data-a="pick" data-id="${t.id}" aria-pressed="${!!sel}"><span class="ck">${I('check',14)}</span><span class="ic">${I(t.icon,24)}</span><h3>${t.name}</h3><p>${t.description}</p>${ex}</button>`;
const stepper=()=>`<ol class="stp" aria-label="Langkah">${['Jenis Surat','Penomoran','Data Pemohon','Data Surat','Preview','Selesai'].map((n,i)=>`<li class="${i+1<S.step?'dn':i+1==S.step?'cu':''}" ${i+1==S.step?'aria-current="step"':''}><b>${i+1<S.step?I('check',15):'0'+(i+1)}</b><span>${n}</span></li>`).join('')}</ol>`;
const fld=(k,l,ph,h,ex='')=>{const nikAttr=/nik/i.test(k)?` inputmode="numeric" maxlength="16" pattern="[0-9]{16}" oninput="this.value=this.value.replace(/\\D/g, '').slice(0,16)"`:'';return `<label class="fl"><span>${l}</span><input class="in ${E[k]?'bad':V[k]&&V[k](S[k])?'ok':''}" data-k="${k}" value="${esc(S[k])}" placeholder="${ph||''}" ${ex}${nikAttr}><small class="hp">${h||''}</small><small class="er" role="alert">${E[k]||''}</small></label>`};
const fdate=(k,l,h)=>`<label class="fl"><span>${l}</span><input class="in ${E[k]?'bad':''}" type="date" data-k="${k}" value="${esc(S[k])}"><small class="hp">${h||''}</small><small class="er" role="alert">${E[k]||''}</small></label>`;
const fdateRange=()=>`<div class="date-range"><label class="fl"><span>Mulai Berlaku</span><input class="in ${E.masaMulai?'bad':''}" type="date" data-k="masaMulai" value="${esc(S.masaMulai)}"><small class="er" role="alert">${E.masaMulai||''}</small></label><span class="range-sep">s/d</span><label class="fl"><span>Tanggal Selesai</span><input class="in ${E.masaSelesai?'bad':''}" type="date" data-k="masaSelesai" value="${esc(S.masaSelesai)}" ${S.masaMulai?`min="${esc(S.masaMulai)}"`:''}><small class="er" role="alert">${E.masaSelesai||''}</small></label></div><small class="hp">Pilih kedua tanggal melalui kalender. Tanggal selesai tidak boleh sebelum tanggal mulai.</small>`;
const fselect=(k,l,opts,h)=>`<label class="fl"><span>${l}</span><select class="in ${E[k]?'bad':''}" data-k="${k}"><option value="">Pilih ${l.toLowerCase()}</option>${opts.map(o=>`<option value="${esc(o)}" ${S[k]===o?'selected':''}>${esc(o)}</option>`).join('')}</select><small class="hp">${h||''}</small><small class="er" role="alert">${E[k]||''}</small></label>`;
const nav=(back,lbl)=>`<div class="act">${back?`<button class="b" data-a="prev">Kembali</button>`:'<span></span>'}<button class="b p" data-a="next">${lbl||'Lanjutkan'}</button></div>`;
const numField=()=>`<div class="fsec"><h2>Penomoran</h2>
<p class="hp">Nomor mengikuti format template resmi. Hanya nomor urut yang berubah dan diisi manual.</p>
${fld('nomorUrut','Nomor urut','Contoh: 652','Nomor urut surat yang diberikan oleh administrasi Desa.','inputmode="numeric"')}
</div>`;
const fullNumber=()=>{
 const n=S.nomorUrut||'____';
 if(S.type==='ket') return `474.4/${n}/403.408.4/${CURRENT_YEAR}`;
 if(S.type==='mat') return S.subtype==='keterangan' ? `474.4/${n}/403.408.4/${CURRENT_YEAR}` : `474.3/${n}/403.408.04/${CURRENT_YEAR}`;
 if(S.type==='pindah') return `475/${n}/403.408.04/${CURRENT_YEAR}`;
 if(S.type==='bantuan') return `474.4/${n}/403.408.4/${CURRENT_YEAR}`;
 if(S.type==='rekening') return `474.4/${n}/403.408.4/${CURRENT_YEAR}`;
 return n==='____'?'':n;
};
const kop=(variant='keterangan')=>{
 let rows;
 if(variant==='death'){
  rows=[
   ['b','PEMERINTAH KABUPATEN MAGETAN'],
   ['b','KECAMATAN : PANEKAN'],
   ['b','DESA : MILANGASRI'],
   ['addr','Jalan Mayjen Sungkono Nomor 35 Telepon No 892826']
  ];
 } else if(variant==='pindah'){
  rows=[
   ['b','PEMERINTAH KABUPATEN MAGETAN'],
   ['b','KECAMATAN : PANEKAN'],
   ['b','DESA : MILANGASRI'],
   ['addr','Jalan Mayjen Sukowati Nomor 35 Telepon No. 892826'],
   ['b','M I L A N G A S R I']
  ];
 } else {
  rows=[
   ['b','PEMERINTAH KABUPATEN MAGETAN'],
   ['b','KECAMATAN PANEKAN'],
   ['b','DESA MILANGASRI'],
   ['addr','Kantor : Jl. Mayjend. Sukowati No. 35, Telp. (0351) 892826']
  ];
 }
 return `<div class="official-head"><img src="${LG.mg}" alt="Lambang Kabupaten Magetan"><div class="official-head-text">${rows.map(([kind,x])=>kind==='addr'?`<span class="kop-address">${x}</span>`:`<b>${x}</b>`).join('')}</div></div><div class="head-rule"></div>`;
};
/* ===== Surat Kematian, Surat Keterangan Kematian, Surat Keterangan =====
   Tata letak mengikuti scan template resmi Desa Milangasri. */
const dlSp=w=>String(w).split('').join('&nbsp;');
const dlBlank='________________';
const dlBoldWord=(t,w)=>{w=String(w||'').trim();if(!t||!w)return t;const re=new RegExp('(^|[^A-Za-z])('+w.replace(/[.*+?^${}()|[\]\\]/g,'\\$&')+')(?![A-Za-z])','i');return t.replace(re,'$1<b>$2</b>')};
const dlRows=(rows)=>`<div class="dl-fields">${rows.map(([l,v,c])=>`<b>${l}</b><span class="${c||''}">: ${v}</span>`).join('')}</div>`;
const dlSign=(tempat,defJab,name,always2)=>{
 const tt=esc(S.ttdTempatTanggal)||`${tempat}, ${fmtDMY(S.tgl)||dlBlank}`;
 const main=`<div class="dl-sign-b"><div>${tt}</div><div>${esc(S.pejabatJabatan)||defJab||dlBlank}</div><div class="dl-name"><u>${name||dlBlank}</u></div></div>`;
 const j2=S.ttd2Jabatan||S.bantuanMengetahui,n2=S.ttd2Nama;
 if(!(j2||n2||always2)) return `<div class="dl-signs one">${main}</div>`;
 return `<div class="dl-signs two"><div class="dl-sign-b"><div>${esc(S.ttd2Label)||'Mengetahui'}</div><div>${esc(j2)||dlBlank}</div><div class="dl-name"><u>${esc(n2)||dlBlank}</u></div></div>${main}</div>`;
};
const kopDeath=()=>`<div class="dl-kop dl-kop-a"><img src="${LG.mg}" alt="Lambang Kabupaten Magetan"><div class="dl-kop-t"><div>PEMERINTAH KABUPATEN MAGETAN</div><div class="dl-kop-g"><span>KECAMATAN</span><span>:</span><span>PANEKAN</span><span>${dlSp('DESA')}</span><span>:</span><span>MILANGASRI</span></div></div></div><div class="dl-addr"><u>Jalan Mayjen Sungkono Nomor 35 Telpon No 892826</u></div>`;
const kopOfficial=()=>`<div class="dl-kop dl-kop-b"><img src="${LG.mg}" alt="Lambang Kabupaten Magetan"><div class="dl-kop-t"><div>PEMERINTAH KABUPATEN MAGETAN</div><div>KECAMATAN PANEKAN</div><div class="dl-big">DESA MILANGASRI</div><div class="dl-kantor"><span>Kantor : Jl. Mayjend. Sukowati No. 35, Telp. (0351) 892826</span></div></div></div><div class="dl-rule"></div>`;
const deathTemplate=(variant='baru')=>{
 if(variant==='keterangan') return deathCertificateTemplate();
 const d=S||{};
 const old=variant==='lama';
 return `<div class="dl-doc dl-cent">${kopDeath()}
 <div class="dl-title">${old?'ARSIP DESA MILANGASRI':'UNTUK YANG BERSANGKUTAN'}<br><u>SURAT KEMATIAN</u><div class="dl-no">Nomor : ${esc(fullNumber().replace(/\/(\d+|____)\//,'/ $1 /'))}</div></div>
 <p class="dl-lead">Yang Bertanda Tangan di bawah ini menerangkan bahwa :</p>
 ${dlRows([[dlSp('Nama'),esc(S.nama)||dlBlank],['Jenis Kelamin',esc(d.jenisKelamin)||dlBlank],[dlSp('Alamat'),esc(S.alamat)||dlBlank],[dlSp('Umur'),esc(d.umur)||dlBlank],['Telah meninggal dunia pada',esc(d.hari)||dlBlank],['Jam',esc(d.jam)||dlBlank],['Tanggal',fmtDMY(d.tanggalMeninggal)||dlBlank],['Di',esc(d.tempat)||dlBlank],['Disebabkan&nbsp; Karena',esc(d.sebab)||dlBlank]])}
 <p class="dl-close">Surat Keterangan ini di buat atas dasar yang sebenarnya.</p>
 ${dlSign('Milangasri','',esc(d.pejabatNama))}</div>`;
};
const deathCertificateTemplate=()=>{
 const d=S||{};
 return `<div class="dl-doc dl-arial dl-v-mk">${kopOfficial()}
 <div class="dl-title"><u>SURAT KETERANGAN KEMATIAN</u><div class="dl-no">Nomor&nbsp; : ${esc(fullNumber().replace(/\/(\d+|____)\//,'/ $1 /'))}</div></div>
 <p class="dl-lead">Yang bertanda tangan di bawah ini :</p>
 ${dlRows([[dlSp('Nama'),esc(d.deathCertPejabatNama)||dlBlank,'dl-bd'],['Alamat',esc(d.deathCertPejabatAlamat)||dlBlank],['Jabatan',esc(d.deathCertPejabatJabatan)||dlBlank]])}
 <p class="dl-lead">Dengan ini menerangkan bahwa warga kami :</p>
 ${dlRows([[dlSp('Nama'),esc(d.deathCertNama)||dlBlank,'dl-bd'],['Nomor NIK',esc(d.deathCertNIK)||'-'],['Umur',esc(d.deathCertUmur)||dlBlank],['Agama',esc(d.deathCertAgama)||dlBlank],['Alamat',esc(d.deathCertAlamat)||dlBlank]])}
 <p class="dl-lead">Telah Meninggal Dunia pada :</p>
 ${dlRows([[dlSp('Hari'),esc(d.deathCertHari)||dlBlank],['Tanggal',fmtDMY(d.deathCertTanggal)||dlBlank],['Pukul',esc(d.deathCertPukul)||dlBlank],['Disebabkan Karena',esc(d.deathCertSebab)||dlBlank],['Tempat',esc(d.deathCertTempat)||dlBlank]])}
 <p class="dl-close dl-just">Demikian&nbsp; surat&nbsp; keterangan&nbsp; ini di buat dengan sebenar- benarnya , untuk dapat dipergunakan sebagaimana mestinya.</p>
 ${dlSign('Milangasri','Kepala Desa Milangasri',esc(d.pejabatNama||d.deathCertPejabatNama))}</div>`;
};
const keteranganTemplate=()=>{
 const d=S||{};
 const judul=d.judulKeterangan?`SURAT KETERANGAN ${esc(d.judulKeterangan).toUpperCase()}`:'SURAT KETERANGAN';
 const ttl=d.tempatLahir&&d.tanggalLahir?`${esc(d.tempatLahir)}, ${fmtDMY(d.tanggalLahir)}`:esc(d.ttl)||dlBlank;
 const masa=d.masaMulai&&d.masaSelesai?`${fmtDMY(d.masaMulai)} s/d ${fmtDMY(d.masaSelesai)}`:dlBlank;
 return `<div class="dl-doc dl-arial dl-v-ket">${kopOfficial()}
 <div class="dl-title"><u>${judul}</u><div class="dl-no">Nomor&nbsp; : ${esc(fullNumber().replace(/\/(\d+|____)\//,'/ $1 /'))}</div></div>
 <p class="dl-lead dl-just">Yang bertanda tangan di bawah ini Kepala Desa Milangasri Kecamatan Panekan Kabupaten Magetan ,dengan ini menerangkan dengan sesungguhnya bahwa :</p>
 ${dlRows([['Nama',esc(S.nama)||dlBlank,'dl-bd'],['NIK',esc(S.nik)||dlBlank],['Tempat Tanggal Lahir',ttl],['Pekerjaan',esc(d.pekerjaan)||dlBlank],['Agama',esc(d.agama)||dlBlank],['Status Perkawinan',esc(d.statusKawin)||dlBlank],['Jenis Kelamin',esc(d.jenisKelamin)||dlBlank],[dlSp('Alamat'),esc(S.alamat)||dlBlank],['Keterangan',dlBoldWord(esc(d.isiKeterangan),d.judulKeterangan)||dlBlank],['Keperluan',esc(d.keperluan)||dlBlank],['Mulai Berlaku',masa]])}
 <p class="dl-close">Demikian&nbsp; surat&nbsp; keterangan&nbsp; ini&nbsp; kami&nbsp; buat&nbsp; untuk&nbsp; digunakan&nbsp; seperlunya.</p>
 ${dlSign('Milangasri','Kepala Desa Milangasri',esc(d.pejabatNama))}</div>`;
};
/* ===== Surat Pengantar Pindah (2 halaman) =====
   Semua posisi mengikuti scan template resmi (satuan mm pada kertas 210 mm).
   Halaman 1 dipakai 1:1. Halaman 2 (form F-08/DAFDUK/2003) aslinya dicetak di kertas lebih panjang,
   jadi kanvasnya diperkecil seragam (0,88) agar muat satu lembar A4 tanpa mengubah proporsi. */
const pdDigits=v=>String(v||'').replace(/\D/g,'');
const pdPad=(v,n)=>{const d=pdDigits(v).slice(0,n);return d?d.padStart(n,'0'):''};
const pdWord=['','Satu','Dua','Tiga','Empat','Lima','Enam'];
const pdMembers=()=>{const a=(S.anggota||[]).filter(x=>x&&(x.nama||x.nik||x.shdk));return a.length?a:(S.anggotaNama||S.anggotaNik?[{nama:S.anggotaNama,nik:S.anggotaNik,shdk:S.shdk}]:[])};
const pdMk=(y0=0)=>{
 const st=o=>Object.entries(o).filter(([,v])=>v!==undefined).map(([k,v])=>`--${k}:${v}`).join(';');
 const t=(x,y,h,c='',w)=>`<span class="pd-t ${c}" style="${st({x,y:+(y-y0).toFixed(2),w})}">${h}</span>`;
 const b=(x,y,w,hh,h='',c='')=>`<div class="pd-b ${c}" style="${st({x,y:+(y-y0).toFixed(2),w,h:hh})}"><span>${h}</span></div>`;
 const cells=(x,y,w,hh,n,val='')=>{const v=String(val||'');return `<div class="pd-cells" style="${st({x,y:+(y-y0).toFixed(2),w,h:hh,n})}">${Array.from({length:n},(_,i)=>`<i>${esc(v[i]||'')}</i>`).join('')}</div>`};
 const mark=(x,y,v)=>b(x,y,5.8,5.2,esc((String(v||'').match(/^\d+/)||[''])[0]),'pd-mark');
 const opts=a=>a.map(([x,y,s])=>t(x,y,s)).join('');
 const dcells=(xs,ws,y,hh,val)=>{const [yy,mm,dd]=String(val||'').split('-');return cells(xs[0],y,ws[0],hh,2,dd)+cells(xs[1],y,ws[1],hh,2,mm)+cells(xs[2],y,ws[2],hh,4,yy)};
 return {t,b,cells,mark,opts,dcells};
};
const pindahPage1=()=>{
 const {t}=pdMk(0);
 const ms=pdMembers(), n=Number(S.jumlahPindah);
 const jml=Number.isInteger(n)&&n>=1&&n<=6?`${String(n).padStart(2,'0')} (${pdWord[n]}) Orang`:'__ (____) Orang';
 const nomor=`475 / ${S.nomorUrut||'____'}/403.408.04/${CURRENT_YEAR}`;
 const blank=(v,f='________')=>esc(v)||f;
 const rows=Array.from({length:6},(_,i)=>{const m=ms[i];return `<div class="c n">${m?i+1:''}</div><div class="c up">${m?esc(m.nama):''}</div><div class="c up">${m?esc(m.shdk):''}</div><div class="c"></div>`}).join('');
 const tujuan=S.alamatTujuan||S.tujuanDsn;
 return `<section class="pd-sheet pd-p1">
 <img class="pd-logo" src="${LG.mg}" alt="Lambang Kabupaten Magetan">
 ${t(112,38.5,'PEMERINTAH KABUPATEN MAGETAN','pd-kop c',150)}
 ${t(84,43.5,'KECAMATAN','pd-kop')}${t(115.4,43.5,':','pd-kop')}${t(121,43.5,'PANEKAN','pd-kop')}
 ${t(84,48.5,'D&nbsp;E&nbsp;S&nbsp;A','pd-kop')}${t(115.4,48.5,':','pd-kop')}${t(121,48.5,'MILANGASRI','pd-kop')}
 ${t(114,52.9,'Jalan Mayjen Sukowati Nomor 35 Telepon No. 892826','pd-kop pd-kop2 c',150)}
 ${t(112,57.8,'M&nbsp;I&nbsp;L&nbsp;A&nbsp;N&nbsp;G&nbsp;A&nbsp;S&nbsp;R&nbsp;I','pd-kop c',150)}
 <div class="pd-rule"></div>
 <div class="pd-b pd-formcode" style="--x:159;--y:75;--w:22;--h:7.2"><span>F - 1.14.</span></div>
 ${t(107,85.2,'<u>SURAT PENGANTAR PINDAH</u>','pd-title c',140)}
 ${t(107,90.4,`NOMOR : ${esc(nomor)}`,'pd-sub c',140)}
 ${t(37.5,100,'Yang bertanda tangan di bawah ini menerangkan bahwa','pd-body')}
 ${t(39,109,'1.&nbsp; NIK Kepala Keluarga','pd-body')}${t(96,109,`: ${blank(pdDigits(S.nikKK),'________________')}`,'pd-body')}
 ${t(39,113.5,'2.&nbsp; Nama Kepala Keluarga','pd-body')}${t(96,113.5,`: ${blank(S.namaKK)}`,'pd-body up')}
 ${t(39,117.7,'3.&nbsp; Alamat Sekarang','pd-body')}${t(96,117.7,`: ${blank(S.asalDsn)}&nbsp; RT.${esc(pdPad(S.asalRT,3))||'___'} RW.${esc(pdPad(S.asalRW,3))||'___'}`,'pd-body')}
 ${t(98.8,121.8,`Desa ${blank(S.asalDesa,'____')}&nbsp; Kec. ${blank(S.asalKec,'____')} Kab. ${blank(S.asalKab,'____')}`,'pd-body')}
 ${t(98.8,125.9,`Prov ${blank(S.asalProv,'____')}`,'pd-body')}
 ${t(39,130.8,'4.&nbsp; Alamat Tujuan Pindah','pd-body')}${t(96,130.8,`: ${blank(tujuan)}&nbsp; RT.${esc(pdPad(S.tujuanRT,3))||'___'} RW.${esc(pdPad(S.tujuanRW,3))||'___'}&nbsp; Ds.${blank(S.tujuanDesa,'____')}`,'pd-body')}
 ${t(98.8,134.9,`Kec. ${blank(S.tujuanKec,'____')}&nbsp; Kab.${blank(S.tujuanKab,'____')} Prov.${blank(S.tujuanProv,'____')}.`,'pd-body')}
 ${t(39,143.2,'5.&nbsp; Keluarga&nbsp; Yang&nbsp; Pindah','pd-body')}${t(96,143.2,`: ${jml}`,'pd-body')}
 <div class="pd-tbl pd-t1" style="--x:17.4;--y:149.3"><div class="c h">NO</div><div class="c h">NAMA</div><div class="c h s2">SHDK</div>${rows}</div>
 ${t(97,192.6,'Demikian Surat Pengantar Pindah ini agar dipergunakan','pd-body c',140)}
 ${t(33,196.7,'Sebagaimana&nbsp; mestinya&nbsp; :','pd-body')}
 ${t(52,205,'Mengetahui :','pd-body c',70)}${t(52,209.8,esc(S.mengJabatan)||'CAMAT PANEKAN','pd-body pd-bold c',70)}
 ${S.mengNama?t(52,231,esc(S.mengNama),'pd-body pd-bold pd-u c',70):t(52,231.6,'-'.repeat(38),'pd-body pd-dots c',70)}
 ${t(153,205,esc(S.ttdTempatTanggal)||`Milangasri Tanggal : ${fmtDMY(S.tgl)||'__-__-____'}`,'pd-body c',70)}
 ${t(153,209.8,esc(S.pejabatJabatan)||'a.n.Kepala Desa Milangasri','pd-body c',70)}
 ${t(153,231,esc(S.pejabatNama)||'________________','pd-body pd-bold pd-u c',70)}
 ${t(21.5,248.5,'**)) SHDK ( Status Hubungan Dalam Keluarga )','pd-note')}
 </section>`;
};
const pindahPage2=()=>{
 const Y0=17, K=pdMk(Y0), {t,b,cells,mark,opts,dcells}=K;
 const ms=pdMembers();
 const nomor=`474/ ${S.nomorUrut||'____'}/403.408.04.${CURRENT_YEAR}`;
 const pemohon=S.pemohonNama||ms[0]?.nama||S.namaKK||'';
 const dots='(......................................................)',nip=n=>`NIP. ${n?esc(n):'..............................................'}`;
 const hdr=(y,h,x=19.5)=>t(x,y,h,'pd-bold');
 const row=(y,no,label,x=19.5,nx=14)=>t(nx,y,no)+t(x,y,label);
 const sub=(y,l1,l2,xl=26)=>t(xl,y,l1)+(l2?t(127,y,l2):'');
 const kodepos=(y,v)=>t(59,y,'Kode Pos')+cells(74,y-2.4,25.5,4.8,5,pdDigits(v).slice(0,5))+t(132,y,'Telepon');
 const tbl=(cls,x,y,rows,cols)=>`<div class="pd-tbl ${cls}" style="--x:${x};--y:${+(y-Y0).toFixed(2)}">${rows}</div>`;
 const famRows=(list,count,nikUp)=>Array.from({length:count},(_,i)=>{const m=list[i];const nik=m?pdDigits(m.nik).slice(0,16):'';return `<div class="c n">${m&&nikUp?i+1:''}</div>${Array.from({length:16},(_,k)=>`<div class="c d">${nik[k]||''}</div>`).join('')}<div class="c nm ${nikUp?'up':''}">${m?esc(m.nama):''}</div><div class="c nm ${nikUp?'up':''}">${m?esc(m.shdk):''}</div><div class="c"></div>`}).join('');
 const asalRows=`<div class="c h n">No</div><div class="c h s16">NIK</div><div class="c h">NAMA</div><div class="c h s2">SHDK</div>${famRows(ms,6,true)}`;
 const tujRows=`<div class="c h n">No</div><div class="c h s16">NIK</div><div class="c h">NAMA</div><div class="c h s2">SHDK</div>${famRows((S.tujuanAnggota||[]).filter(x=>x&&(x.nama||x.nik||x.shdk)),5,false)}`;
 const dtAlamat=S.dtAlamat??S.tujuanAlamat;
 return `<section class="pd-sheet pd-p2"><div class="pd-canvas">
 ${b(163,21,32,5.5,'F-08/DAFDUK/2003','pd-code')}
 ${hdr(30.6,'DATA DAERAH ASAL')}
 ${row(35.3,'1.','Nomor Kartu Keluarga')}${cells(59,32.9,84,4.8,16,pdDigits(S.noKKAsal).slice(0,16))}
 ${row(40.3,'2.','Nama Kepala Keluarga')}${b(59,38,81.5,5,esc(S.namaKK),'up')}
 ${row(45,'3.','Alamat')}${b(59,42.6,73.5,4.8,esc(S.asalDsn),'up')}${t(143.5,45,'RT')}${cells(148,42.6,15,4.8,3,pdPad(S.asalRT,3))}${t(169,45,'RW')}${cells(178.5,42.6,16,4.8,3,pdPad(S.asalRW,3))}
 ${t(26,50.7,'a.')}${t(31,50.7,'Desa / Kelurahan')}${b(59,48.3,41.5,4.8,esc(S.asalDesa),'up')}${t(127,50.7,'c.')}${t(132,50.7,'Kab / Kota')}${b(152.5,48.3,42,4.8,esc(S.asalKab),'up')}
 ${t(26,56,'b.')}${t(31,56,'Kecamatan')}${b(59,53.6,41.5,4.8,esc(S.asalKec),'up')}${t(127,56,'d.')}${t(132,56,'Propinsi')}${b(152.5,53.6,42,4.8,esc(S.asalProv),'up')}
 ${kodepos(60.4,S.asalKodePos)}${b(152.5,58,42,4.6,esc(S.asalTelp))}
 ${hdr(67,'DATA KEPINDAHAN')}
 ${opts([[69.5,70.7,'1.&nbsp; Pekerjaan'],[95.5,70.7,'3.&nbsp; Keamanan'],[122,70.7,'5.&nbsp; Perumahan'],[148,70.7,'7.&nbsp; Lainnya'],[168,70.7,'( Sebutkan )'],[69.5,75.3,'2.&nbsp; Pendidikan'],[95.5,75.3,'4.&nbsp; Kesehatan'],[122,75.3,'6.&nbsp; Keluarga']])}
 ${row(75.7,'1.','Alasan Pindah')}${mark(58.5,73.1,S.alasanPindah)}
 ${row(80.7,'2.','Alamat Tujuan Pindah')}${b(58.5,78.3,78.5,4.8,esc(S.alamatTujuan||S.tujuanDsn))}${t(143.5,80.7,'RT')}${cells(147.5,78.3,16.5,4.8,3,pdPad(S.tujuanRT,3))}${t(169,80.7,'RW')}${cells(178,78.3,16.5,4.8,3,pdPad(S.tujuanRW,3))}
 ${t(26,86.1,'a.')}${t(31,86.1,'Desa / Kelurahan')}${b(58.5,83.7,41.5,4.8,esc(S.tujuanDesa))}${t(127,86.1,'c.')}${t(132,86.1,'Kab / Kota')}${b(152,83.7,42.5,4.8,esc(S.tujuanKab))}
 ${t(26,91.4,'b.')}${t(31,91.4,'Kecamatan')}${b(58.5,89,41.5,4.8,esc(S.tujuanKec))}${t(127,91.4,'d.')}${t(132,91.4,'Propinsi')}${b(152,89,42.5,4.8,esc(S.tujuanProv))}
 ${t(59,95.9,'Kode Pos')}${cells(74,93.5,26,4.8,5,pdDigits(S.tujuanKodePos).slice(0,5))}${t(132,95.9,'Telepon')}${b(152,93.4,42.5,4.8,esc(S.tujuanTelp))}
 ${row(102,'3.','Klasifikasi Pindah',18.5)}${mark(58,99.4,S.klasifikasi)}${opts([[69.5,100.8,'1.&nbsp; Dalam satu Desa/Kelurahan'],[122,100.8,'3.&nbsp; Antar Kecamatan'],[158,100.8,'5.&nbsp; Antar Provinsi'],[69.5,105.5,'2.&nbsp; Antar Desa / Kelurahan'],[122,105.5,'4.&nbsp; Antar Kab /Kota']])}
 ${row(110.3,'4.','Jenis Kepindahan',18.5)}${mark(58,107.9,S.jenisKepindahan)}${opts([[69.5,109.6,'1.&nbsp; Kep. Keluarga'],[137,109.6,'3.&nbsp; Kep. Keluarga dan sbg Angg Keluarga'],[69.5,114.2,'2.&nbsp; Ke. Keluarga dan Seluruh Angg. Keluarga'],[137,114.2,'4.&nbsp; Angg. Keluarga']])}
 ${row(119.8,'5.','Status Nomor KK',18.5)}${t(21.5,124.3,'Bagi Yang Tidak Pindah')}${mark(58,117.1,S.statusKKTidakPindah)}${opts([[69.5,118.9,'1.&nbsp; Numpang KK'],[122,118.9,'3.&nbsp; Tidak ada Angg Keluarga Yang Ditinggal'],[69.5,123.6,'2.&nbsp; Membuat KK Baru'],[122,123.6,'4.&nbsp; Nomor KK Tetap']])}
 ${row(129.3,'6.','Status Nomor KK',18.5)}${t(21.5,133.8,'Bagi Yang Pindah')}${mark(58,126.9,S.statusKKPindah)}${opts([[69.5,128.8,'1.&nbsp; Numpang KK'],[100,128.8,'2.&nbsp; Membuat KK Baru'],[137,128.8,'3.&nbsp; Nama Kep. Keluarga dan No KK Tetap']])}
 ${row(138.1,'7.','Rencana Tgl Pindah',18.5)}${dcells([58.5,73.5,88.5],[9.5,10,21],135.7,4.8,S.rencanaTanggal)}
 ${row(142.7,'8.','Keluarga Yang Pindah',18.5)}${t(60.5,142.7,esc(String(Number(S.jumlahPindah)>0?String(Number(S.jumlahPindah)).padStart(2,'0'):'').split('').join(' ')))}
 ${tbl('pd-t2',17,145.4,asalRows)}
 ${t(24.5,182.6,'Diketahui Oleh :')}${t(24.5,187.3,'Camat')}${t(24.5,191.5,'No..............................Tgl.............20...')}
 ${t(24.5,206.2,S.asalCamatNama?`( ${esc(S.asalCamatNama)} )`:dots,S.asalCamatNama?'pd-bold':'')}${t(24.5,210.7,nip(S.asalCamatNip))}
 ${t(99,182.4,'Pemohon')}${b(82,203.4,53,4.8,esc(pemohon),'pd-center up')}
 ${t(146.5,182.5,'Dikeluarkan Oleh :')}${t(146.5,187,esc(S.asalKadesJabatan)||'a.n.Kepala Desa / Lurah')}${t(146.5,191.3,esc(nomor))}${t(146.5,206,esc(S.pejabatNama)||'________________','pd-bold pd-u')}
 ${hdr(218.3,'DATA DAERAH TUJUAN',16.5)}
 ${row(223.4,'1.','Nomor Kartu Keluarga',17,12.5)}${cells(56,220.8,84,5.2,16,pdDigits(S.tujuanKK).slice(0,16))}
 ${row(229.2,'2.','Nama Kepala Keluarga',17,12.5)}${b(56,226.6,84,5,esc(S.tujuanNamaKK))}
 ${row(234.8,'3.','NIK Kepala Keluarga',17,12.5)}${cells(56,232.4,84,5.2,16,pdDigits(S.tujuanNIKKK).slice(0,16))}
 ${row(239.8,'4.','Status Nomor KK',17,12.5)}${t(21,244.4,'Bagi Yang Pindah')}${b(55.5,241,5.8,5.4,esc((String(S.tujuanStatusKK||'').match(/^\d+/)||[''])[0]),'pd-mark')}${opts([[67,243.8,'1.&nbsp; Numpang KK'],[98.5,243.8,'2.&nbsp; Membuat KK Baru'],[135,243.8,'3.&nbsp; Nama Kep. Keluarga dan No KK Tetap']])}
 ${row(249.6,'5.','Tanggal Kedatangan',17,12.5)}${dcells([55.5,71,87],[11,11,21.5],247.2,5,S.tujuanTanggalDatang)}
 ${row(254.9,'6.','Alamat',17,12.5)}${b(55.5,252.3,58,5.2,esc(dtAlamat))}${t(141,254.9,'RT')}${cells(149.5,252.3,15.5,5.2,3,pdPad(S.dtRT,3))}${t(170.5,254.9,'RW')}${cells(180,252.3,11.5,5.2,2,pdPad(S.dtRW,2))}
 ${t(23,260.6,'a.')}${t(28,260.6,'Desa / Kelurahan')}${b(55.5,258,41.5,5.2,esc(S.dtDesa))}${t(123.5,260.6,'c.')}${t(128.5,260.6,'Kab / Kota')}${b(149.5,258,41.5,5.2,esc(S.dtKab))}
 ${t(23,266.1,'b.')}${t(28,266.1,'Kecamatan')}${b(55.5,263.5,41.5,5.2,esc(S.dtKec))}${t(123.5,266.1,'d.')}${t(128.5,266.1,'Propinsi')}${b(149.5,263.5,41.5,5.2,esc(S.dtProv))}
 ${t(15,271.3,'7.')}${t(19,271.3,'Keluarga Yang Datang')}
 ${tbl('pd-t3',15.5,273.3,tujRows)}
 ${t(28,303.8,'Diketahui Oleh :')}${t(28,308.4,'Camat')}${t(28,313,'No..............................Tgl.............20...')}
 ${t(144,302.4,'Dikeluarkan Oleh :')}${t(144,306.9,esc(S.dtKadesJabatan)||'Kepala Desa / Lurah')}${t(144,311.4,'No......................Tgl.............20.......')}
 ${t(28,325.4,S.dtCamatNama?`( ${esc(S.dtCamatNama)} )`:dots,S.dtCamatNama?'pd-bold':'')}${t(28,333.5,nip(S.dtCamatNip))}
 ${t(144,325.4,S.dtKadesNama?`( ${esc(S.dtKadesNama)} )`:dots,S.dtKadesNama?'pd-bold':'')}${t(144,333.5,nip(S.dtKadesNip))}
 </div></section>`;
};
const bantuanTemplate=()=>{
 const d=S||{};
 const ttl=d.bantuanTempatLahir&&d.bantuanTanggalLahir?`${esc(d.bantuanTempatLahir)}, ${fd(d.bantuanTanggalLahir)}`:esc(d.bantuanTempatLahir)||'________________';
 const lamps=lampiranList().map(x=>String(x).trim()).filter(Boolean);
 return `${kop('bantuan')}<div class="letter-date">${esc(S.ttdTempatTanggal||d.bantuanTempatTanggal)||`Magetan, ${fd(S.tgl)}`}</div>
 <div class="bantuan-meta"><div><b>Nomor</b><span>: ${fullNumber()}</span><b>Sifat</b><span>: ${esc(d.bantuanSifat)||'________________'}</span><b>Lampiran</b><span>: ${lamps.length||'____'}</span><b>Perihal</b><span>: ${esc(d.bantuanPerihal)||'________________'}</span></div><div><b>Kepada</b><span>: ${esc(d.bantuanKepada)||'________________'}${d.bantuanCq?`<br>Cq. ${esc(d.bantuanCq)}`:''}</span></div></div>
 <p class="bantuan-lead">${esc(d.bantuanPembuka)||'Yang bertanda tangan di bawah ini dengan ini menerangkan dengan sesungguhnya bahwa :'}</p>
 <div class="letter-fields"><b>Nama</b><span>: ${esc(S.nama)||'________________'}</span><b>NIK</b><span>: ${esc(S.nik)||'________________'}</span><b>Tempat/Tggl Lhr</b><span>: ${ttl}</span><b>Pekerjaan</b><span>: ${esc(d.bantuanPekerjaan)||'________________'}</span><b>Agama</b><span>: ${esc(d.bantuanAgama)||'________________'}</span><b>Status Perkawinan</b><span>: ${esc(d.bantuanStatusKawin)||'________________'}</span><b>Alamat</b><span>: ${esc(S.alamat)||'________________'}</span></div>
 ${(() => { const needs=(Array.isArray(d.bantuanDaftarKebutuhan)?d.bantuanDaftarKebutuhan:[]).map(x=>String(x).trim()).filter(Boolean); return needs.length?`<p>Adapun kebutuhan bantuan yang kami ajukan:</p><ol class="letter-list">${needs.map(x=>`<li>${esc(x)}</li>`).join('')}</ol>`:''; })()}
 ${d.bantuanKeterangan?`<p><b>Uraian</b> : ${esc(d.bantuanKeterangan)}</p>`:''}
 <p>Untuk melengkapi berkas permohonan bantuan, kami lampirkan:</p>
 ${lamps.length?`<ol class="letter-list">${lamps.map(x=>`<li>${esc(x)}</li>`).join('')}</ol>`:'<div class="letter-list">1. ____________________</div>'}
 <p>${esc(d.bantuanPenutup)||'Demikian permohonan bantuan ini dibuat untuk dapat dipergunakan sebagaimana mestinya. Atas perhatian dan kerjasamanya yang baik kami mengucapkan terima kasih.'}</p>
 ${dlSign('Magetan','',esc(d.pejabatNama),true)}`;
};
const rekeningTemplate=()=>{
 const d=S||{};
 const person=(nama,nik,alamat,jabatan,telp)=>`<div class="rekening-person"><div class="letter-fields"><b>Nama</b><span>: ${esc(nama)||'________________'}</span><b>NIK</b><span>: ${esc(nik)||'________________'}</span><b>Alamat</b><span>: ${esc(alamat)||'________________'}</span><b>Jabatan</b><span>: ${esc(jabatan)||'________________'}</span><b>No. Telp.</b><span>: ${esc(telp)||'________________'}</span></div></div>`;
 return `${kop()}<div class="letter-title"><u>SURAT PERMOHONAN MEMBUKA REKENING</u><div class="letter-no">Nomor : ${fullNumber()}</div></div>
 <p>Yang bertanda tangan di bawah ini :</p>
 <div class="letter-fields"><b>Nama</b><span>: ${esc(d.rekeningPemberiNama)||'________________'}</span><b>Jabatan</b><span>: ${esc(d.rekeningPemberiJabatan)||'________________'}</span></div>
 <p>Dengan ini memberikan perintah kepada;</p>
 ${person(d.rekeningOrang1Nama,d.rekeningOrang1NIK,d.rekeningOrang1Alamat,d.rekeningOrang1Jabatan,d.rekeningOrang1Telp)}
 ${person(d.rekeningOrang2Nama,d.rekeningOrang2NIK,d.rekeningOrang2Alamat,d.rekeningOrang2Jabatan,d.rekeningOrang2Telp)}
 <p>Bahwa kedua orang tersebut di atas adalah sebagai Ketua dan Bendahara Majelis Ta’lim AL-IKHLAS RT.005 RW.002 Desa Milangasri untuk membuka Rekening Tabungan di Bank JATIM atas nama Majelis Ta’lim AL-IKHLAS Desa Milangasri Kec. Panekan Kab. Magetan.</p>
 <p><b>Keterangan</b> : ${esc(d.rekeningKeterangan)||'____________________________________________________________'}</p>
 ${dlSign('Magetan','',esc(d.pejabatNama))}`;
};
const renderLetter=()=>{
 if(S.type==='ket') return keteranganTemplate();
 if(S.type==='mat') return deathTemplate(S.subtype||'baru');
 if(S.type==='pindah') return `<div class="pd-doc">${pindahPage1()}${pindahPage2()}</div>`;
 if(S.type==='bantuan') return bantuanTemplate();
 if(S.type==='rekening') return rekeningTemplate();
 return '';
};
const paperIn=()=>renderLetter();
const peek=()=>`<div class="pkp"><div class="paper official-paper"><div class="pin" id="pin">${paperIn()}</div></div><p class="hp" style="text-align:center;margin-top:8px">Pratinjau mengikuti susunan template dari dokumen yang Anda kirim.</p></div>`;
const dataFields=()=>{
 if(S.type==='ket') return `<div class="fsec"><h2>Identitas Pemohon</h2>${fld('nik','NIK','16 digit angka','Nomor Induk Kependudukan.')}<div class="form-two">${fld('tempatLahir','Tempat Lahir','Contoh: Magetan','Diisi manual sesuai dokumen.')} ${fdate('tanggalLahir','Tanggal Lahir','Pilih langsung dari kalender.')}</div>${fld('pekerjaan','Pekerjaan','Contoh: Wiraswasta','Diisi sesuai dokumen warga.')}${fld('agama','Agama','Contoh: Islam','Diisi sesuai dokumen warga.')}${fselect('statusKawin','Status Perkawinan',['Kawin','Belum Kawin'],'Pilih sesuai dokumen.')}${fselect('jenisKelamin','Jenis Kelamin',['Laki-laki','Perempuan'],'Pilih salah satu.')}</div>`;
 if(S.type==='bantuan') return `<div class="fsec"><h2>Data Pemohon</h2>${fld('nama','Nama','Nama pemohon','')}${fld('nik','NIK','16 digit angka','Nomor Induk Kependudukan.')}<div class="form-two">${fld('bantuanTempatLahir','Tempat Lahir','Contoh: Waru Kulon','')} ${fdate('bantuanTanggalLahir','Tanggal Lahir','Pilih dari kalender.')}</div>${fld('bantuanPekerjaan','Pekerjaan','Pekerjaan pemohon','')}${fld('bantuanAgama','Agama','Contoh: Islam','')}${fselect('bantuanStatusKawin','Status Perkawinan',['Kawin','Belum Kawin'],'')}${fld('alamat','Alamat','Alamat lengkap','')}</div>`;
 if(S.type==='mat'){
   if(S.subtype==='keterangan') return `<div class="fsec"><h2>Data Pemberi Keterangan</h2>${fld('deathCertPejabatNama','Nama','Nama pejabat','')}${fld('deathCertPejabatAlamat','Alamat','Alamat lengkap pejabat','')}${fld('deathCertPejabatJabatan','Jabatan','Contoh: Kepala Desa Milangasri','')}</div><div class="fsec"><h2>Data Warga yang Meninggal</h2>${fld('deathCertNama','Nama','Nama warga','')}${fld('deathCertNIK','Nomor NIK','16 digit angka','')} ${fld('deathCertUmur','Umur','Contoh: 71 Tahun','')}${fld('deathCertAgama','Agama','Contoh: Islam','')}${fld('deathCertAlamat','Alamat','Alamat lengkap','')}</div>`;
   return `<div class="fsec"><h2>Data Warga yang Meninggal</h2>${fld('nama','Nama','Nama warga','')}${fselect('jenisKelamin','Jenis Kelamin',['Laki-laki','Perempuan'],'')}${fld('alamat','Alamat','Alamat lengkap','')}${fld('umur','Umur','Contoh: 62 Tahun','')}</div>`;
 }
 if(S.type==='pindah') return `<div class="fsec"><h2>Data Daerah Asal</h2>${fld('noKKAsal','Nomor Kartu Keluarga','16 digit angka','Nomor KK daerah asal (halaman 2, form F-08).')}${fld('nikKK','NIK Kepala Keluarga','16 digit angka','Dicetak pada halaman 1 surat pengantar.')}${fld('namaKK','Nama Kepala Keluarga','Nama kepala keluarga','')}${fld('asalDsn','Alamat / Dusun','Contoh: DSN WARU KULON','')}${fld('asalRT','RT','001','')}${fld('asalRW','RW','002','')}${fld('asalDesa','Desa / Kelurahan','MILANGASRI','')}${fld('asalKec','Kecamatan','PANEKAN','')}${fld('asalKab','Kab / Kota','MAGETAN','')}${fld('asalProv','Provinsi','JAWA TIMUR','')}${fld('asalKodePos','Kode Pos','63352','')}${fld('asalTelp','Telepon','Nomor telepon','')}</div>${anggotaInputs()}`;
 if(S.type==='rekening') return `<div class="fsec"><h2>Data Pemberi Perintah</h2>${fld('rekeningPemberiNama','Nama','Nama pemberi perintah','')}${fld('rekeningPemberiJabatan','Jabatan','Jabatan pemberi perintah','')}</div><div class="fsec"><h2>Data Orang Pertama</h2>${fld('rekeningOrang1Nama','Nama','Nama','')}${fld('rekeningOrang1NIK','NIK','16 digit angka','')}${fld('rekeningOrang1Alamat','Alamat','Alamat lengkap','')}${fld('rekeningOrang1Jabatan','Jabatan','Jabatan','')}${fld('rekeningOrang1Telp','No. Telp.','Nomor telepon','')}</div><div class="fsec"><h2>Data Orang Kedua</h2>${fld('rekeningOrang2Nama','Nama','Nama','')}${fld('rekeningOrang2NIK','NIK','16 digit angka','')}${fld('rekeningOrang2Alamat','Alamat','Alamat lengkap','')}${fld('rekeningOrang2Jabatan','Jabatan','Jabatan','')}${fld('rekeningOrang2Telp','No. Telp.','Nomor telepon','')}</div>`;
 return '';
};
const kebutuhanList=()=>Array.isArray(S.bantuanDaftarKebutuhan)&&S.bantuanDaftarKebutuhan.length?S.bantuanDaftarKebutuhan:[''];
const kebutuhanInputs=()=>`<div class="fsec"><h2>Kebutuhan Bantuan</h2><p class="hp">Tulis kebutuhan satu per satu. Nomor akan otomatis dimulai dari 1 dan bertambah saat item ditambahkan.</p><small class="er" role="alert">${E.bantuanDaftarKebutuhan||''}</small><div class="lampiran-builder">${kebutuhanList().map((v,i)=>`<div class="lampiran-row"><b class="lampiran-no">${i+1}.</b><input class="in" data-bk="${i}" value="${esc(v)}" placeholder="Kebutuhan bantuan"><button type="button" class="b kebutuhan-remove" data-a="kebutuhanRemove" data-i="${i}" aria-label="Hapus kebutuhan ${i+1}">×</button></div>`).join('')}<button type="button" class="b" data-a="kebutuhanAdd">+ Tambah Kebutuhan</button></div></div>`;
const lampiranList=()=>Array.isArray(S.bantuanDaftarLampiran)&&S.bantuanDaftarLampiran.length?S.bantuanDaftarLampiran:[''];
const lampiranInputs=()=>`<div class="fsec"><h2>Dokumen yang Dilampirkan</h2><p class="hp">Tambahkan sesuai dokumen yang benar-benar dilampirkan pada permohonan.</p><small class="er" role="alert">${E.bantuanDaftarLampiran||''}</small><div class="lampiran-builder">${lampiranList().map((v,i)=>`<div class="lampiran-row"><b class="lampiran-no">${i+1}.</b><input class="in" data-bl="${i}" value="${esc(v)}" placeholder="Nama dokumen lampiran"><button type="button" class="b lampiran-remove" data-a="lampRemove" data-i="${i}" aria-label="Hapus dokumen ${i+1}">×</button></div>`).join('')}<button type="button" class="b" data-a="lampAdd">+ Tambah Dokumen</button></div></div>`;
const anggotaInputs=()=>{
 const rows=Math.max(1,S.anggota?.length||1);
 return `<div class="fsec"><h2>Data Keluarga yang Pindah</h2>${fld('jumlahPindah','Jumlah Keluarga yang Pindah','1–6','Jumlah orang yang pindah. Maksimal 6 orang. Terisi otomatis saat anggota ditambah atau dihapus.','inputmode="numeric" maxlength="1"')}<small class="er member-summary-error" role="alert">${E.anggota||''}</small>${Array.from({length:rows},(_,i)=>{const a=S.anggota?.[i]||{};S[`anggotaNama_${i}`]=a.nama||'';S[`anggotaNik_${i}`]=a.nik||'';S[`anggotaShdk_${i}`]=a.shdk||'';return `<div class="member-builder-row"><b>${i+1}.</b>${fld(`anggotaNama_${i}`,'Nama','Nama anggota','')}${fld(`anggotaNik_${i}`,'NIK','16 digit angka','')}${fld(`anggotaShdk_${i}`,'SHDK','Contoh: ANAK','')}<button type="button" class="b member-remove" data-a="anggotaRemove" data-i="${i}" aria-label="Hapus anggota ${i+1}" ${rows<2?'disabled':''}>Hapus</button></div>`}).join('')}<button type="button" class="b" data-a="anggotaAdd" ${rows>=6?'disabled':''}>+ Tambah Anggota Keluarga</button>${rows>=6?'<small class="hp">Maksimal 6 anggota sesuai kapasitas tabel template.</small>':''}</div>`};
const tujuanAnggotaInputs=()=>{
 const rows=Math.max(1,S.tujuanAnggota?.length||1);
 return `<div class="fsec"><h2>Keluarga Yang Datang</h2><p class="hp">Opsional. Biasanya diisi desa tujuan.</p>${Array.from({length:rows},(_,i)=>{const a=S.tujuanAnggota?.[i]||{};S[`tujuanAnggotaNik_${i}`]=a.nik||'';S[`tujuanAnggotaNama_${i}`]=a.nama||'';S[`tujuanAnggotaShdk_${i}`]=a.shdk||'';return `<div class="member-builder-row"><b>${i+1}.</b>${fld(`tujuanAnggotaNik_${i}`,'NIK','16 digit angka','')}${fld(`tujuanAnggotaNama_${i}`,'Nama','Nama anggota','')}${fld(`tujuanAnggotaShdk_${i}`,'SHDK','Contoh: ANAK','')}<button type="button" class="b member-remove" data-a="tujuanAnggotaRemove" data-i="${i}" aria-label="Hapus anggota ${i+1}" ${rows<2?'disabled':''}>Hapus</button></div>`}).join('')}<button type="button" class="b" data-a="tujuanAnggotaAdd" ${rows>=5?'disabled':''}>+ Tambah Anggota</button>${rows>=5?'<small class="hp">Maksimal 5 anggota sesuai kapasitas tabel template.</small>':''}</div>`};
const pindahSigners=()=>`<div class="fsec"><h2>Tanda Tangan Halaman 1 (Surat Pengantar)</h2><p class="hp">Semua diisi manual karena pejabat yang menandatangani bisa berbeda-beda.</p>
${fld('ttdTempatTanggal','Tempat dan tanggal (opsional)','Contoh: Milangasri Tanggal : 05-10-2026','Kosongkan agar otomatis memakai tanggal surat.')}${fld('pejabatJabatan','Jabatan penandatangan desa','Contoh: a.n.Kepala Desa Milangasri','Contoh pengisian: a.n.Kepala Desa Milangasri / Kepala Desa Milangasri / Sekretaris Desa')}${fld('pejabatNama','Nama penandatangan desa','Contoh: FATTIKA RINDI AKBARA','Contoh pengisian: FATTIKA RINDI AKBARA')}${fld('mengJabatan','Jabatan yang mengetahui','Contoh: CAMAT PANEKAN','Kosongkan untuk memakai "CAMAT PANEKAN".')}${fld('mengNama','Nama yang mengetahui','Contoh: Drs. BUDI SANTOSO, M.Si','Opsional. Kosongkan agar tercetak garis putus-putus untuk tanda tangan basah.')}</div>
<div class="fsec"><h2>Tanda Tangan Halaman 2 (Form F-08)</h2><p class="hp">Daerah Asal. Semua bersifat opsional; yang kosong tercetak seperti template.</p>
${fld('pemohonNama','Nama pemohon','Contoh: CLARA MEGA AMELIA','Kosongkan untuk memakai nama anggota pertama.')}${fld('asalKadesJabatan','Jabatan "Dikeluarkan Oleh"','Contoh: a.n.Kepala Desa / Lurah','Kosongkan untuk memakai "a.n.Kepala Desa / Lurah".')}${fld('asalCamatNama','Nama Camat (Diketahui Oleh)','Contoh: Drs. BUDI SANTOSO, M.Si','')}${fld('asalCamatNip','NIP Camat','Contoh: 19700101 199503 1 002','')}</div>
<div class="fsec"><h2>Tanda Tangan Data Daerah Tujuan</h2><p class="hp">Biasanya diisi desa tujuan. Boleh dikosongkan.</p>
${fld('dtCamatNama','Nama Camat (Diketahui Oleh)','Contoh: Drs. AHMAD FAUZI','')}${fld('dtCamatNip','NIP Camat','Contoh: 19680505 199303 1 004','')}${fld('dtKadesJabatan','Jabatan "Dikeluarkan Oleh"','Contoh: Kepala Desa / Lurah','Kosongkan untuk memakai "Kepala Desa / Lurah".')}${fld('dtKadesNama','Nama Kepala Desa / Lurah','Contoh: SUPARNO, S.Sos','')}${fld('dtKadesNip','NIP Kepala Desa / Lurah','Contoh: 19750710 200003 1 001','')}</div>`;
const signerFields=()=>`<div class="fsec"><h2>Tanda Tangan</h2><p class="hp">Semua diisi manual sesuai pejabat yang menandatangani. Tulisan akan tampil rata tengah.</p>
${fld('ttdTempatTanggal','Tempat dan tanggal (opsional)','Contoh: Milangasri, 05-10-2026','Kosongkan agar otomatis memakai tanggal surat. Contoh pengisian: Magetan, 5 Oktober 2026')}
${fld('pejabatJabatan','Jabatan penandatangan','Contoh: Kepala Desa Milangasri','Contoh pengisian: Kepala Desa Milangasri / A.n KEPALA DESA MILANGASRI / Sekretaris Desa')}
${fld('pejabatNama','Nama penandatangan','Contoh: ANGGIT ARDIYANTO, SH','Contoh pengisian: ANGGIT ARDIYANTO, SH atau FATIKA RINDI AKBARA, S.Pd')}
<p class="hp" style="margin-top:14px"><b>Tanda tangan kedua (opsional)</b>${S.type==='bantuan'?'':' Isi bila surat perlu diketahui pihak lain; kosongkan jika tidak ada.'}</p>
${fld('ttd2Label','Keterangan','Contoh: Mengetahui','Kosongkan untuk memakai "Mengetahui". Contoh lain: Menyetujui')}
${fld('ttd2Jabatan','Jabatan','Contoh: CAMAT PANEKAN','Contoh pengisian: CAMAT PANEKAN / Ketua RW 002 / Kepala Dusun Waru Kulon')}
${fld('ttd2Nama','Nama','Contoh: Drs. BUDI SANTOSO, M.Si','Contoh pengisian: Drs. BUDI SANTOSO, M.Si')}</div>`;
const suratFields=()=>{
 if(S.type==='ket') return `<div class="fsec"><h2>Data Surat</h2>${fld('judulKeterangan','Jenis Keterangan','Contoh: Kehilangan / Usaha / Domisili','Ditampilkan sebagai judul surat.')}${fld('isiKeterangan','Keterangan','Uraian keterangan sesuai kebutuhan','Opsional.')}${fld('keperluan','Keperluan','Keperluan surat','')}${fdateRange()}</div>${signerFields()}`;
 if(S.type==='bantuan') return `<div class="fsec"><h2>Detail Permohonan Bantuan</h2>${fld('bantuanSifat','Sifat','Contoh: Penting','Diisi manual.')}${fld('bantuanPerihal','Perihal','Contoh: Permohonan Bantuan Kursi Roda','Kebutuhan bantuan ditulis manual.')}${fld('bantuanKepada','Kepada','Contoh: Yth. instansi / pihak tujuan','Tujuan surat dapat disesuaikan dengan kebutuhan.')}${fld('bantuanCq','Cq.','Contoh: Kepala Bidang / Bagian terkait','Opsional.')}${fld('bantuanPembuka','Pembuka','Kalimat pembuka surat','Diisi manual sesuai kebutuhan.')}${kebutuhanInputs()}${fld('bantuanKeterangan','Uraian / Keterangan Tambahan','Keterangan tambahan bila diperlukan','Opsional.')}${lampiranInputs()}${fld('bantuanPenutup','Keterangan','Kalimat penutup / keterangan tambahan','Diisi manual.')}</div>${signerFields()}`;
 if(S.type==='mat'){
   const choices=`<div class="fsec"><h2>Format Surat Kematian</h2><div class="choice-grid"><button class="choice ${S.subtype==='baru'?'on':''}" data-a="subtype" data-v="baru"><b>Surat Kematian — Baru</b><small>Format UNTUK YANG BERSANGKUTAN.</small></button><button class="choice ${S.subtype==='lama'?'on':''}" data-a="subtype" data-v="lama"><b>Surat Kematian — Lama / Arsip</b><small>Format ARSIP DESA MILANGASRI.</small></button><button class="choice ${S.subtype==='keterangan'?'on':''}" data-a="subtype" data-v="keterangan"><b>Surat Keterangan Kematian</b><small>Format halaman Surat Keterangan Kematian dengan nomor 474.4.</small></button></div><small class="er">${E.subtype||''}</small></div>`;
   if(S.subtype==='keterangan') return choices+`<div class="fsec"><h2>Data Kematian</h2>${fld('deathCertHari','Hari','Contoh: Kamis Pahing','')}${fdate('deathCertTanggal','Tanggal Meninggal','')}${fld('deathCertPukul','Pukul','Contoh: 09.10 WIB','')}${fld('deathCertSebab','Disebabkan Karena','Contoh: Sakit','')}${fld('deathCertTempat','Tempat','Contoh: Di Rumah','')}</div>${signerFields()}`;
   return choices+`<div class="fsec"><h2>Data Kematian</h2>${fld('hari','Hari','Contoh: Kamis Pahing','')}${fld('jam','Jam / Pukul','Contoh: 06.35 WIB','')}${fdate('tanggalMeninggal','Tanggal Meninggal','')}${fld('tempat','Di','Contoh: Di Rumah','')}${fld('sebab','Disebabkan Karena','Contoh: Sakit','')}</div>${signerFields()}`;
 }
 if(S.type==='pindah') return `<div class="fsec"><h2>Data Kepindahan</h2>${fselect('alasanPindah','Alasan Pindah',['1 - Pekerjaan','2 - Pendidikan','3 - Keamanan','4 - Kesehatan','5 - Perumahan','6 - Keluarga','7 - Lainnya (Sebutkan)'],'Pilihan sesuai F-08/DAFDUK/2003.')}${fld('alamatTujuan','Alamat Tujuan Pindah','Contoh: Jl.Gajah Mada','Jalan / dusun tujuan. Dipakai di halaman 1 dan 2.')}${fld('tujuanRT','RT','003','')}${fld('tujuanRW','RW','001','')}${fld('tujuanDesa','Desa / Kelurahan','Kentangan','')}${fld('tujuanKec','Kecamatan','Sukomoro','')}${fld('tujuanKab','Kab / Kota','Magetan','')}${fld('tujuanProv','Provinsi','Jambi','')}${fld('tujuanKodePos','Kode Pos','5 digit angka','Opsional.')}${fld('tujuanTelp','Telepon','Nomor telepon','Opsional.')}${fselect('klasifikasi','Klasifikasi Pindah',['1 - Dalam satu Desa/Kelurahan','2 - Antar Desa/Kelurahan','3 - Antar Kecamatan','4 - Antar Kab/Kota','5 - Antar Provinsi'],'')}${fselect('jenisKepindahan','Jenis Kepindahan',['1 - Kep. Keluarga','2 - Ke. Keluarga dan Seluruh Angg. Keluarga','3 - Kep. Keluarga dan sbg Angg Keluarga','4 - Angg. Keluarga'],'')}${fselect('statusKKTidakPindah','Status Nomor KK Bagi Yang Tidak Pindah',['1 - Numpang KK','2 - Membuat KK Baru','3 - Tidak ada Angg Keluarga Yang Ditinggal','4 - Nomor KK Tetap'],'')}${fselect('statusKKPindah','Status Nomor KK Bagi Yang Pindah',['1 - Numpang KK','2 - Membuat KK Baru','3 - Nama Kep. Keluarga dan No KK Tetap'],'')}${fdate('rencanaTanggal','Rencana Tgl Pindah','')}</div><div class="fsec"><h2>Data Daerah Tujuan</h2><p class="hp">Bagian ini biasanya diisi oleh desa tujuan. Boleh dikosongkan agar tercetak kosong seperti template.</p>${fld('tujuanKK','Nomor Kartu Keluarga','16 digit angka','')}${fld('tujuanNamaKK','Nama Kepala Keluarga','Nama','')}${fld('tujuanNIKKK','NIK Kepala Keluarga','16 digit angka','')}${fselect('tujuanStatusKK','Status Nomor KK Bagi Yang Pindah',['1 - Numpang KK','2 - Membuat KK Baru','3 - Nama Kep. Keluarga dan No KK Tetap'],'')}${fdate('tujuanTanggalDatang','Tanggal Kedatangan','')}${fld('dtAlamat','Alamat','Alamat di daerah tujuan','')}${fld('dtRT','RT','Maks. 3 digit','')}${fld('dtRW','RW','Maks. 2 digit','')}${fld('dtDesa','Desa / Kelurahan','','')}${fld('dtKec','Kecamatan','','')}${fld('dtKab','Kab / Kota','','')}${fld('dtProv','Provinsi','','')}</div>${tujuanAnggotaInputs()}${pindahSigners()}`;
 if(S.type==='rekening') return `<div class="fsec"><h2>Data Surat</h2>${fld('rekeningKeterangan','Keterangan','Keterangan tambahan sebelum tanda tangan','Diisi manual sesuai kebutuhan surat.')}</div>${signerFields()}`;
 return '';
};
function stepBody(){const s=S.step;
 if(s==1)return`<div class="lcs">${LT.map(t=>lcard(t,S.type==t.id)).join('')}</div><p class="er" style="margin-top:8px">${E.type||''}</p>${nav(0)}`;
 if(s==2)return`<div class="pk single"><div class="fs">${numField()}</div><div class="card mini-info"><h2>Format penomoran</h2><p class="hp">Nomor urut diisi manual. Bagian lain mengikuti format pada template surat Desa Milangasri.</p><div class="bd">${fullNumber()}</div></div></div>${nav(1)}`;
 if(s==3){const top=['pindah','mat','bantuan','rekening'].includes(S.type)?dataFields():`<div class="fsec"><h2>Data Pemohon</h2>${fld('nama','Nama','Nama sesuai dokumen','')}</div>${dataFields()}<div class="fsec">${fld('alamat','Alamat','Alamat lengkap','')}</div>`;return `<div class="pk"><div class="fs">${top}</div>${peek()}</div>${nav(1)}`;}
 if(s==4)return`<div class="pk"><div class="fs">${suratFields()}<div class="fsec"><h2>Informasi Surat</h2><label class="fl"><span>Tanggal surat</span><input class="in" type="date" data-k="tgl" value="${S.tgl}"></label></div></div>${peek()}</div>${nav(1,'Lihat Preview')}`;
 if(s==5)return`<div class="pv"><div><div class="preview-heading"><div><h2>Preview Surat</h2><p class="hp">Periksa isi surat terlebih dahulu. Saat mencetak, pilih <b>Save as PDF</b> pada dialog printer untuk menyimpan file PDF.</p></div></div><div class="tool"><button class="b" data-a="z-" aria-label="Perkecil">−</button><b class="zz" id="zl">${Math.round(S.z*100)}%</b><button class="b" data-a="z+" aria-label="Perbesar">+</button><span class="sp"></span>${(S.type==='ket'||S.type==='mat')?`<button class="b p" data-a="savepdf">${I('dl',18)} Simpan &amp; Unduh PDF</button>`:`<button class="b p" data-a="print">${I('print',18)} Cetak / Simpan PDF</button>`}</div><div class="pwr"><div class="paper official-paper" id="pp" style="--z:${S.z}"><div class="pin" id="pin">${paperIn()}</div></div></div></div>
 <div class="side card"><h2>Informasi Dokumen</h2><dl><div><dt>Jenis surat</dt><dd>${tn(S.type)}</dd></div><div><dt>Format</dt><dd>${S.type==='mat'?(S.subtype==='keterangan'?'Surat Keterangan Kematian':S.subtype==='lama'?'Surat Kematian Lama / Arsip':'Surat Kematian Baru'):S.type==='pindah'?'2 halaman':'1 halaman'}</dd></div><div><dt>Nomor</dt><dd>${fullNumber()||'Belum diisi'}</dd></div><div><dt>Tanggal</dt><dd>${fd(S.tgl)}</dd></div></dl><button class="b p" data-a="save" id="sv">${I('save',18)} Simpan Surat</button><button class="b" data-a="prev">Kembali</button></div></div>`;
 const d=S.saved;return`<div class="succ"><svg class="big" viewBox="0 0 96 120" fill="none"><path d="M8 4h56l24 24v88H8z" fill="var(--el)" stroke="var(--em)" stroke-width="3"/><path d="M64 4v24h24" stroke="var(--em)" stroke-width="3"/><g class="stm"><circle cx="48" cy="72" r="27" fill="none" stroke="var(--am)" stroke-width="2" stroke-dasharray="3 4"/><circle cx="48" cy="72" r="20" fill="var(--em)"/><path d="M38 72l7 7 13-14" stroke="#fff" stroke-width="4" stroke-linecap="round" stroke-linejoin="round"/></g></svg><h1>Surat Berhasil Dibuat</h1><dl><dt>Jenis surat</dt><dd>${tn(d.type)}</dd><dt>Nomor surat</dt><dd>${esc(d.no)}</dd><dt>Nama pemohon</dt><dd>${esc(d.nama)}</dd><dt>Tanggal</dt><dd>${fd(d.tgl)}</dd></dl><p class="hp success-print-note">Preview tetap tersedia di bawah ini. Untuk file PDF, klik <b>Cetak / Simpan PDF</b> lalu pilih <b>Save as PDF</b>.</p><div class="success-preview"><div class="paper official-paper"><div class="pin">${archivePreview(d)}</div></div></div><div class="sa">${d.pdfUrl?`<a class="b p" href="${d.pdfUrl}?unduh=1">${I('dl',18)} Unduh PDF</a><a class="b" href="${d.pdfUrl}" target="_blank" rel="noopener">Buka PDF</a>`:`<button class="b p" data-a="print">${I('print',18)} Cetak / Simpan PDF</button>`}<button class="b" data-a="go" data-v="arsip">Lihat Arsip</button><button class="b" data-a="again">Buat Surat Baru</button></div></div>`;
}
const buat=()=>`<h1>Buat Surat</h1><p class="sub">Ikuti enam langkah sederhana berikut.</p>${stepper()}<div>${stepBody()}</div>`;
const spark=pts=>`<svg width="100%" height="28" viewBox="0 0 100 28" preserveAspectRatio="none"><polyline points="${pts}" fill="none" stroke="currentColor" stroke-width="2" stroke-linejoin="round" vector-effect="non-scaling-stroke"/></svg>`;
let MV=[],DV=[],MON=[];
const MON_NAMES=['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'],DC=['#5b5548','#d9922b','#0e9f6e','#4b6fa5','#b5533c','#cfc5aa'];
const monthSeries=()=>Array.from({length:6},(_,i)=>{const d=new Date(CURRENT_YEAR,nowLocal.getMonth()-5+i,1);return {year:d.getFullYear(),month:d.getMonth()+1,label:MON_NAMES[d.getMonth()]}});
function dash(){const series=monthSeries();MON.splice(0,MON.length,...series.map(x=>x.label));MV=series.map(x=>ARS.filter(a=>{const d=String(a.tgl||'').slice(0,7);return d===`${x.year}-${pad2(x.month)}`}).length);DV=LT.map(t=>ARS.filter(a=>a.type==t.id).length);
const T=ARS.length,BM=ARS.filter(a=>String(a.tgl||'').startsWith(`${CURRENT_YEAR}-${pad2(nowLocal.getMonth()+1)}`)).length,BH=ARS.filter(a=>a.tgl==TODAY).length,MX=Math.max(...MV,1),c=2*Math.PI*54;
let off=0;
const seg=DV.map((v,i)=>{const l=v/T*c,s=`<circle class="sg2" data-s="${i}" r="54" cx="70" cy="70" fill="none" stroke="${DC[i]}" stroke-width="22" stroke-dasharray="${l} ${c-l}" stroke-dashoffset="${-off}" transform="rotate(-90 70 70)"/>`;off+=l;return s}).join('');
return`<section class="hero"><svg class="lw" viewBox="0 0 600 160" preserveAspectRatio="none" fill="currentColor" aria-hidden="true"><path d="M0 160V110L120 70 200 100 330 10 390 40 450 0 540 80 600 60V160z" opacity=".06"/><path d="M0 160V135L140 105 260 130 380 60 470 120 600 90V160z" opacity=".1"/></svg><svg class="ct" viewBox="0 0 400 300" fill="none" stroke="currentColor">${CT()}</svg><div><span class="tag">${IM('mg',20)} Desa Milangasri · Kab. Magetan</span><h1>Selamat datang di DESARA, Perangkat Desa.</h1><p>Kelola administrasi surat Desa Milangasri dengan lebih terstruktur.</p><div class="row"><button class="b p" data-a="go" data-v="buat">${I('plus',18)} Buat Surat Baru</button><button class="b g" data-a="go" data-v="arsip">Lihat Arsip</button></div></div><div class="dcs" aria-hidden="true"><i></i><i></i><i></i></div></section>
<div class="stats">${[['Total Surat',T,'0,22 20,18 40,20 60,10 80,12 100,3'],['Surat Bulan Ini',BM,'0,20 25,14 50,18 75,8 100,6'],['Surat Hari Ini',BH,'0,14 25,20 50,10 75,16 100,8'],['Total Arsip',T,'0,24 30,20 60,14 100,4']].map(([l,n,p])=>`<div class="st"><span>${l}</span><b data-n="${n}">0</b>${spark(p)}</div>`).join('')}</div>
<div class="card" style="margin-bottom:16px"><h2>Buat surat cepat</h2><p class="hp">Pilih jenis surat, langsung isi data pemohon.</p><div class="chs">${LT.map(t=>`<button data-a="mk" data-id="${t.id}">${I(t.icon,16)} ${t.name}</button>`).join('')}</div></div><div class="g2"><div class="card"><h2>Aktivitas Surat</h2><p class="hp">Jumlah surat per bulan berdasarkan arsip yang tersedia.</p><svg viewBox="0 0 480 280" style="width:100%;margin-top:8px" role="img" aria-label="Grafik aktivitas surat">${MV.map((v,i)=>{const h=v/MX*190;return`<text x="${46+i*74}" y="${232-h}" text-anchor="middle" font-size="14" font-weight="700" fill="var(--ink)">${v}</text><rect class="bar${i==5?' on':''}" tabindex="0" data-c="${i}" style="animation-delay:${.3+i*.07}s" x="${22+i*74}" y="${240-h}" width="48" height="${h}" rx="9"/><text x="${46+i*74}" y="268" text-anchor="middle" font-size="13" fill="var(--mu)">${MON[i]}</text>`}).join('')}</svg><div class="chi" id="ci2">${MON[5]} ${series[5].year} · ${MV[5]} surat</div></div>
<div class="card"><h2>Distribusi Jenis Surat</h2><p class="hp">Distribusi berdasarkan arsip yang tersedia.</p><div style="display:flex;justify-content:center;position:relative;margin-top:8px"><svg width="150" viewBox="0 0 140 140" aria-hidden="true">${seg}</svg><div id="dc" style="position:absolute;inset:0;display:grid;place-content:center;text-align:center;font-weight:800;font-size:22px">${T}<small class="hp" style="font-weight:500">surat</small></div></div><div class="lgd">${LT.map((t,i)=>`<button data-d="${i}"><i style="background:${DC[i]}"></i>${t.name}<b>${DV[i]}</b></button>`).join('')}</div></div></div><div class="card" style="margin-top:16px"><h2>Dokumen Terbaru</h2><div style="margin-top:8px">${ARS.length?ARS.slice(0,4).map((a,i)=>{const typ=LT.find(t=>t.id==a.type)||LT[0];return `<button class="rc" data-a="doc" data-i="${i}"><span class="ic">${I(typ.icon,18)}</span><b>${tn(a.type)}<br><span class="hp" style="font-weight:500">${esc(a.nama)} · ${esc(a.no)}</span></b><span class="hp">${fd(a.tgl)}</span></button>`}).join(''):`<div class="emp-inline"><span class="ic">${I('doc',20)}</span><span class="hp">Belum ada surat tersimpan.</span></div>`}</div></div>`}
const jenis=()=>`<h1>Jenis Surat</h1><p class="sub">Pilih jenis surat untuk melihat kegunaan, contoh pengisian, lalu lanjutkan langsung ke formulir.</p><div class="lcs jn">${LT.map(t=>lcard(t,S.jsel==t.id,`<span class="bd w">${t.status}</span><div class="type-info"><div><b>Digunakan untuk</b><span>${t.purpose}</span></div><div><b>Contoh</b><span>${t.example}</span></div></div><span class="mo"><span class="b p" data-a="mk" data-id="${t.id}" role="button" tabindex="0">Buat Surat Ini</span></span>`)).join('')}</div>`;
const panduan=()=>`<h1>Panduan</h1><p class="sub">DESARA membantu perangkat Desa Milangasri mengisi, memeriksa, dan mengarsipkan surat berdasarkan template yang tersedia.</p>
<div class="about-desara card"><div class="about-logo">${LOGO(58)}</div><div><span class="bd">DESARA · Desa Surat dan Administrasi</span><h2>Administrasi surat lebih terstruktur</h2><p>DESARA dirancang sebagai antarmuka untuk membantu perangkat desa mengelola proses pengajuan surat dari pemilihan jenis layanan, pengisian data, pemeriksaan preview, sampai penyimpanan arsip. Data yang dimasukkan tetap mengikuti kebutuhan pada masing-masing template, sehingga setiap jenis surat tidak dipaksakan memakai field yang sama.</p><p class="hp"><b>Catatan:</b> template Mengajukan Bantuan dan Surat Permohonan Membuka Rekening telah disesuaikan dengan dokumen contoh yang diterima.</p></div></div>
<div class="gd">${[
 ['Pilih jenis surat','Pilih Surat Keterangan, Surat Kematian, Surat Pengantar Pindah, Mengajukan Bantuan, atau Surat Permohonan Membuka Rekening. Setiap layanan menampilkan formulir yang berbeda sesuai kebutuhan prosesnya.'],
 ['Isi penomoran','Masukkan nomor urut secara manual terlebih dahulu. Format nomor ditampilkan mengikuti layanan yang dipilih. Format setiap layanan mengikuti pola nomor pada dokumen contoh yang diberikan, sedangkan tahun otomatis mengikuti tahun berjalan.'],
 ['Isi data sesuai surat','Setiap jenis surat hanya menampilkan data yang memang diperlukan oleh templatenya. NIK dibatasi 16 digit angka, tanggal dapat dipilih melalui kalender, dan pilihan seperti jenis kelamin/status perkawinan menggunakan opsi yang tersedia.'],
 ['Lengkapi data surat','Keterangan dibuat opsional karena sifatnya uraian manual. Field lainnya wajib dilengkapi sebelum dapat melanjutkan ke tahap berikutnya. Contoh Keperluan disediakan agar pengisian lebih mudah.'],
 ['Periksa preview','Pastikan nama, NIK, nomor, kop, logo Kabupaten Magetan, judul, isi, tanggal, dan bagian tanda tangan sudah sesuai sebelum surat disimpan.'],
 ['Simpan dan cetak','Setelah preview sesuai, simpan surat untuk masuk ke Arsip Surat. Gunakan Print / Simpan PDF untuk menghasilkan dokumen yang siap dicetak.']
].map(([a,b],i)=>`<article class="card gd-step"><div class="gd-num">${i+1}</div><div class="gd-copy"><h2>${a}</h2><p>${b}</p></div></article>`).join('')}</div>`;
/* arsip */
const arsip=()=>`<h1>Arsip Surat</h1><p class="sub">Temukan dan kelola dokumen surat yang telah dibuat.</p><div class="srch">${I('search')}<input class="in" id="q" placeholder="Cari berdasarkan nama, NIK, atau nomor surat..." aria-label="Cari arsip" value="${esc(F.q)}"></div>
<div class="chs"><button aria-pressed="${!F.t}" data-a="ft" data-id="">Semua</button>${LT.map(t=>`<button aria-pressed="${F.t==t.id}" data-a="ft" data-id="${t.id}">${t.name}</button>`).join('')}<input class="in" type="date" id="fdt" aria-label="Tanggal" value="${F.d}"><button data-a="reset">Reset</button></div><p class="hp" id="cnt" style="margin-bottom:12px"></p><div id="al"></div>`;
function list(){const r=ARS.filter(a=>(!F.t||a.type==F.t)&&(!F.d||a.tgl==F.d)&&(!F.q||(a.nama+a.nik+a.no).toLowerCase().includes(F.q.toLowerCase())));$('#cnt').textContent=r.length+' dokumen ditemukan';
const ic=a=>`<span class="ic">${I(LT.find(t=>t.id==a.type).icon,18)}</span>`,bd=`<span class="bd">${I('check',12)} Selesai</span>`;
$('#al').innerHTML=r.length?`<div class="card" style="padding:12px"><table class="tb"><thead><tr><th>Nomor Surat</th><th>Pemohon</th><th>Jenis Surat</th><th>Tanggal</th><th>Status</th><th>Aksi</th></tr></thead><tbody>${r.map(a=>`<tr class="rw" tabindex="0" data-a="doc" data-i="${ARS.indexOf(a)}"><td><b>${esc(a.no)}</b></td><td>${esc(a.nama)}</td><td><div class="dt">${ic(a)}${tn(a.type)}</div></td><td>${fd(a.tgl)}</td><td>${bd}</td><td><span class="b" style="min-height:36px">Buka</span></td></tr>`).join('')}</tbody></table></div><div class="cds">${r.map(a=>`<button class="card" data-a="doc" data-i="${ARS.indexOf(a)}"><div class="dt">${ic(a)}<div><b>${esc(a.nama)}</b><br><span class="hp">${esc(a.no)}</span></div></div><span class="hp">${tn(a.type)} · ${fd(a.tgl)}</span>${bd}</button>`).join('')}</div>`
:`<div class="card emp"><svg class="ct" viewBox="0 0 400 300" fill="none" stroke="currentColor">${CT(7)}</svg>${I('doc',44)}<h2>Belum ada arsip surat</h2><p class="hp">Surat yang telah selesai dibuat akan tersimpan di sini.</p><button class="b p" data-a="go" data-v="buat">Buat Surat Baru</button></div>`}
/* router */
function go(v){view=v;$('#nav').innerHTML=NAV.map(([id,l,ic])=>`<button class="nv ${id==v?'on':''}" data-a="go" data-v="${id}" title="${l}" ${id==v?'aria-current="page"':''}>${I(ic)}<span class="lb">${l}</span></button>`).join('');
$('#view').innerHTML={dash,buat,arsip,jenis,panduan}[v]();$('#sd').classList.remove('open');scrollTo(0,0);
const vw=$('#view');vw.style.animation='none';vw.offsetWidth;vw.style.animation=v=='buat'&&S.dir?(S.dir>0?'slr':'sll')+' .28s':'';S.dir=0;
if(v=='arsip')list();if(v=='dash')document.querySelectorAll('[data-n]').forEach(e=>{const n=+e.dataset.n,t0=performance.now(),f=t=>{const p=Math.min(1,(t-t0)/800);e.textContent=Math.round(n*(1-Math.pow(1-p,3)));p<1&&requestAnimationFrame(f)};requestAnimationFrame(f)})}
function requiredForStep(){
 if(S.step===2) return ['nomorUrut'];
 if(S.step===3){
  if(S.type==='ket') return ['nama','nik','tempatLahir','tanggalLahir','pekerjaan','agama','statusKawin','jenisKelamin','alamat'];
  if(S.type==='bantuan') return ['nama','nik','bantuanTempatLahir','bantuanTanggalLahir','bantuanPekerjaan','bantuanAgama','bantuanStatusKawin','alamat'];
  if(S.type==='mat'){
   if(S.subtype==='keterangan') return ['deathCertPejabatNama','deathCertPejabatAlamat','deathCertPejabatJabatan','deathCertNama','deathCertNIK','deathCertUmur','deathCertAgama','deathCertAlamat'];
   return ['nama','jenisKelamin','alamat','umur'];
  }
  if(S.type==='pindah') return ['noKKAsal','nikKK','namaKK','asalDsn','asalRT','asalRW','asalDesa','asalKec','asalKab','asalProv','asalKodePos','jumlahPindah'];
  if(S.type==='rekening') return ['rekeningPemberiNama','rekeningPemberiJabatan','rekeningOrang1Nama','rekeningOrang1NIK','rekeningOrang1Alamat','rekeningOrang1Jabatan','rekeningOrang1Telp','rekeningOrang2Nama','rekeningOrang2NIK','rekeningOrang2Alamat','rekeningOrang2Jabatan','rekeningOrang2Telp'];
 }
 if(S.step===4){
  if(S.type==='ket') return ['judulKeterangan','keperluan','masaMulai','masaSelesai','tgl','pejabatJabatan','pejabatNama'];
  if(S.type==='bantuan') return ['bantuanSifat','bantuanPerihal','bantuanKepada','bantuanDaftarKebutuhan','bantuanPenutup','tgl','pejabatJabatan','pejabatNama'];
  if(S.type==='mat'){
   if(S.subtype==='keterangan') return ['subtype','deathCertHari','deathCertTanggal','deathCertPukul','deathCertSebab','deathCertTempat','tgl','pejabatJabatan','pejabatNama'];
   return ['subtype','hari','jam','tanggalMeninggal','tempat','sebab','tgl','pejabatJabatan','pejabatNama'];
  }
  if(S.type==='pindah') return ['alamatTujuan','tujuanRT','tujuanRW','tujuanDesa','tujuanKec','tujuanKab','tujuanProv','alasanPindah','klasifikasi','jenisKepindahan','statusKKTidakPindah','statusKKPindah','rencanaTanggal','tgl','pejabatJabatan','pejabatNama'];
  if(S.type==='rekening') return ['rekeningKeterangan','tgl','pejabatJabatan','pejabatNama'];
 }
 return [];
}
function next(){E={};
 if(S.step===1&&!S.type) E.type='Pilih salah satu jenis surat untuk melanjutkan.';
 const keys=requiredForStep();
 keys.forEach(k=>{if(k==='subtype'){if(!S.subtype)E[k]='Pilih salah satu format terlebih dahulu.';return} if(k==='bantuanDaftarLampiran'){if(!lampiranList().some(x=>String(x).trim()))E[k]='Isi minimal 1 dokumen lampiran.';return} if(k==='bantuanDaftarKebutuhan'){if(!kebutuhanList().some(x=>String(x).trim()))E[k]='Isi minimal 1 kebutuhan bantuan.';return} if(k==='nik'||k==='nikKK'||k==='noKKAsal'||k==='anggotaNik'||k==='tujuanNIKKK'||k==='tujuanKK'||k==='rekeningOrang1NIK'||k==='rekeningOrang2NIK'||k==='deathCertNIK'){if(!/^\d{16}$/.test(String(S[k]||'')))E[k]='Harus terdiri dari tepat 16 digit angka.';return} if(!String(S[k]??'').trim())E[k]='Wajib diisi.';});
 if(S.step===2 && S.type && S.nomorUrut){const candidate=fullNumber();if(ARS.some(a=>a.no===candidate)){E.nomorUrut='Nomor surat tersebut sudah ada di Arsip Surat.';}}
 if(S.step===4 && S.type==='bantuan' && !kebutuhanList().some(x=>String(x).trim())) E.bantuanDaftarKebutuhan='Isi minimal 1 kebutuhan bantuan.';
 if(S.step===4 && S.type==='bantuan' && !lampiranList().some(x=>String(x).trim())) E.bantuanDaftarLampiran='Isi minimal 1 dokumen lampiran.';
 if(S.type==='pindah'){
  if(S.step===3){
   const members=S.anggota||[];
   const active=members.filter(x=>x && (String(x.nama||'').trim()||String(x.nik||'').trim()||String(x.shdk||'').trim()));
   const jumlah=Number(S.jumlahPindah);
   if(!Number.isInteger(jumlah)||jumlah<1||jumlah>6) E.jumlahPindah='Isi jumlah 1–6 orang.';
   else if(active.length!==jumlah) E.anggota=`Jumlah anggota yang diisi (${active.length}) harus sama dengan jumlah yang pindah (${jumlah}).`;
   members.slice(0,Math.max(jumlah,active.length)).forEach((x,i)=>{
      if(!x || i>=Math.max(jumlah,active.length)) return;
      if(!String(x.nama||'').trim()) E[`anggotaNama_${i}`]='Wajib diisi.';
      if(!/^\d{16}$/.test(String(x.nik||''))) E[`anggotaNik_${i}`]='Harus terdiri dari tepat 16 digit angka.';
      if(!String(x.shdk||'').trim()) E[`anggotaShdk_${i}`]='Wajib diisi.';
    });
  }
  if(S.step===4){
   ['tujuanKK','tujuanNIKKK'].forEach(k=>{if(String(S[k]||'').trim()&&!/^\d{16}$/.test(String(S[k])))E[k]='Harus terdiri dari tepat 16 digit angka.'});
   const members=S.tujuanAnggota||[];
   const active=members.filter(x=>x && (String(x.nama||'').trim()||String(x.nik||'').trim()||String(x.shdk||'').trim()));
   members.forEach((x,i)=>{
      if(!x) return;
      const filled=String(x.nama||'').trim()||String(x.nik||'').trim()||String(x.shdk||'').trim();
      if(!filled) return;
      if(!/^\d{16}$/.test(String(x.nik||''))) E[`tujuanAnggotaNik_${i}`]='Harus terdiri dari tepat 16 digit angka.';
      if(!String(x.nama||'').trim()) E[`tujuanAnggotaNama_${i}`]='Wajib diisi.';
      if(!String(x.shdk||'').trim()) E[`tujuanAnggotaShdk_${i}`]='Wajib diisi.';
    });
  }
}

 if(S.type==='ket'&&S.masaMulai&&S.masaSelesai&&S.masaSelesai<S.masaMulai){E.masaSelesai='Tanggal selesai tidak boleh sebelum tanggal mulai.';}
 if(Object.keys(E).length){go('buat');setTimeout(()=>document.querySelector('.bad,[data-k="subtype"]')?.focus(),0);toast('Lengkapi data yang wajib diisi terlebih dahulu.');return}
 S.dir=1;S.step++;go('buat');
}
function archivePreview(a){
 const before=S;
 const snapshot=a.data||{};
 S={...S0(),...snapshot,no:a.no,nama:a.nama,nik:a.nik,tgl:a.tgl,type:a.type,subtype:a.subtype||snapshot.subtype||null,nomorUrut:a.nomorUrut||snapshot.nomorUrut||''};
 const html=paperIn();
 S=before;
 return html;
}
function doc(i){const a=ARS[i];
const preview=`<div class="archive-preview"><div class="paper official-paper archive-paper"><div class="pin">${archivePreview(a)}</div></div></div>`;$('#md').innerHTML=`<div class="card archive-modal" role="dialog" aria-label="Detail surat"><div class="archive-head"><div><h2>${tn(a.type)}</h2><span class="bd">${I('check',12)} ${a.st}</span></div><button class="b" data-a="close" aria-label="Tutup">×</button></div><dl class="archive-meta"><div><dt>Nomor</dt><dd>${esc(a.no)}</dd></div><div><dt>Pemohon</dt><dd>${esc(a.nama)}</dd></div><div><dt>NIK</dt><dd>${esc(a.nik)}</dd></div><div><dt>Tanggal</dt><dd>${fd(a.tgl)}</dd></div></dl><h3 class="archive-preview-title">Preview Surat</h3>${preview}<button class="b p archive-close" data-a="close">Tutup</button></div>`;$('#md').classList.add('on')}
const CMD=()=>[...NAV.map(([id,l,ic])=>({l:'Buka '+l,ic,f:()=>go(id)})),...LT.map(t=>({l:'Buat '+t.name,ic:t.icon,f:()=>ACT.mk({dataset:{id:t.id}})})),...ARS.map((a,i)=>({l:a.no+' · '+a.nama,ic:'arch',f:()=>doc(i)}))];
function printLetterOnly(){
 const source=document.querySelector('#view .official-paper');
 if(!source){toast('Preview surat belum tersedia.');return}
 const old=document.querySelector('#print-root');
 if(old)old.remove();
 const root=document.createElement('div');
 root.id='print-root';
 root.setAttribute('aria-hidden','true');
 const clone=source.cloneNode(true);
 clone.removeAttribute('id');
 root.appendChild(clone);
 document.body.appendChild(root);
 document.body.classList.add('printing-letter');
 const cleanup=()=>{document.body.classList.remove('printing-letter');root.remove();window.removeEventListener('afterprint',cleanup)};
 window.addEventListener('afterprint',cleanup,{once:true});
 requestAnimationFrame(()=>requestAnimationFrame(()=>window.print()));
 setTimeout(cleanup,30000);
}
function cpl(){const q=$('#cq').value.toLowerCase();$('#cr').innerHTML=CMD().map((c,i)=>({c,i})).filter(x=>x.c.l.toLowerCase().includes(q)).slice(0,8).map(x=>`<button class="rc" data-a="cmd" data-i="${x.i}">${I(x.c.ic,18)}<b>${esc(x.c.l)}</b></button>`).join('')||'<p class="hp" style="padding:12px">Tidak ada hasil.</p>'}
const ACT={go:e=>go(e.dataset.v),subtype(e){S.subtype=e.dataset.v;E={};go('buat')},pick(e){if(e.closest('.jn')){S.jsel=S.jsel==e.dataset.id?null:e.dataset.id;return go('jenis')}S.type=e.dataset.id;E={};go('buat')},mk(e){S.type=e.dataset.id;S.subtype=null;S.step=2;go('buat')},next,prev(){S.dir=-1;S.step--;E={};go('buat')},'z+'(){zoom(.2)},'z-'(){zoom(-.2)},print:()=>printLetterOnly(),dl:()=>printLetterOnly(),
lampAdd(){const a=lampiranList();a.push('');S.bantuanDaftarLampiran=a;go('buat')},
kebutuhanAdd(){const a=kebutuhanList();a.push('');S.bantuanDaftarKebutuhan=a;go('buat')},
kebutuhanRemove(e){const a=kebutuhanList();const i=+e.dataset.i;if(a.length>1){a.splice(i,1);S.bantuanDaftarKebutuhan=a;go('buat')}},
anggotaAdd(){const cur=S.anggota?.length?S.anggota:[{nama:'',nik:'',shdk:''}];if(cur.length>=6)return toast('Maksimal 6 anggota.');S.anggota=[...cur,{nama:'',nik:'',shdk:''}];S.jumlahPindah=String(S.anggota.length);E={};go('buat')},
anggotaRemove(e){const cur=[...(S.anggota||[])];while(cur.length<Math.max(1,S.anggota?.length||1))cur.push({nama:'',nik:'',shdk:''});if(cur.length<2)return;cur.splice(+e.dataset.i,1);S.anggota=cur;S.jumlahPindah=String(cur.length);E={};go('buat')},
tujuanAnggotaRemove(e){const cur=[...(S.tujuanAnggota||[])];if(cur.length<2)return;cur.splice(+e.dataset.i,1);S.tujuanAnggota=cur;E={};go('buat')},
tujuanAnggotaAdd(){const cur=S.tujuanAnggota?.length?S.tujuanAnggota:[{nama:'',nik:'',shdk:''}];if(cur.length>=5)return toast('Maksimal 5 anggota.');S.tujuanAnggota=[...cur,{nama:'',nik:'',shdk:''}];E={};go('buat')},lampRemove(e){const a=lampiranList();const i=+e.dataset.i;if(a.length>1){a.splice(i,1);S.bantuanDaftarLampiran=a;go('buat')}},save(e){e.disabled=true;e.textContent='Menyimpan…';const local=()=>{const a={no:fullNumber(),nama:S.nama||S.deathCertNama||S.namaKK||S.rekeningPemberiNama||S.rekeningOrang1Nama,nik:S.nik||S.deathCertNIK||S.nikKK||S.rekeningOrang1NIK,type:S.type,tgl:S.tgl,st:'Selesai',subtype:S.subtype||null,nomorUrut:S.nomorUrut,data:JSON.parse(JSON.stringify(S))};return a};const done=(a,msg)=>{ARS.unshift(a);persistArchive();S.saved=a;S.step=6;go('buat');toast(msg)};const mat=S.type==='mat',kp=()=>({subtype:S.subtype,nomorUrut:S.nomorUrut,tanggalSurat:S.tgl,ttdTempatTanggal:S.ttdTempatTanggal,pejabatJabatan:S.pejabatJabatan,pejabatNama:S.pejabatNama,ttd2Label:S.ttd2Label,ttd2Jabatan:S.ttd2Jabatan,ttd2Nama:S.ttd2Nama,...(S.subtype==='keterangan'?{deathCertPejabatNama:S.deathCertPejabatNama,deathCertPejabatAlamat:S.deathCertPejabatAlamat,deathCertPejabatJabatan:S.deathCertPejabatJabatan,deathCertNama:S.deathCertNama,deathCertNIK:S.deathCertNIK,deathCertUmur:S.deathCertUmur,deathCertAgama:S.deathCertAgama,deathCertAlamat:S.deathCertAlamat,deathCertHari:S.deathCertHari,deathCertTanggal:S.deathCertTanggal,deathCertPukul:S.deathCertPukul,deathCertSebab:S.deathCertSebab,deathCertTempat:S.deathCertTempat}:{nama:S.nama,jenisKelamin:S.jenisKelamin,alamat:S.alamat,umur:S.umur,hari:S.hari,jam:S.jam,tanggalMeninggal:S.tanggalMeninggal,tempat:S.tempat,sebab:S.sebab})});if(S.type==='ket'||mat){fetch(mat?'/api/surat/kematian':'/api/surat/keterangan',{method:'POST',headers:{'Content-Type':'application/json','Accept':'application/json'},body:JSON.stringify(mat?kp():{nama:S.nama,nik:S.nik,tempatLahir:S.tempatLahir,tanggalLahir:S.tanggalLahir,pekerjaan:S.pekerjaan,agama:S.agama,statusKawin:S.statusKawin,jenisKelamin:S.jenisKelamin,alamat:S.alamat,isiKeterangan:S.isiKeterangan,keperluan:S.keperluan,masaMulai:S.masaMulai,masaSelesai:S.masaSelesai,judulKeterangan:S.judulKeterangan,nomorUrut:S.nomorUrut,tanggalSurat:S.tgl,ttdTempatTanggal:S.ttdTempatTanggal,pejabatJabatan:S.pejabatJabatan,pejabatNama:S.pejabatNama})}).then(r=>r.json().then(j=>({ok:r.ok,j}))).then(({ok,j})=>{if(!ok||!j.success){S.autoPdf=false;e.disabled=false;e.textContent='Simpan Surat';toast((j.errors?Object.values(j.errors)[0][0]:j.message)||'Gagal menyimpan surat ke server.');return}const a=local();a.no=j.nomor_surat;a.pdfUrl=j.pdf_url;a.serverId=j.id;const auto=S.autoPdf;S.autoPdf=false;done(a,'Surat berhasil disimpan ke database.');if(auto){const l=document.createElement('a');l.href=j.pdf_url+'?unduh=1';document.body.append(l);l.click();l.remove()}}).catch(()=>{S.autoPdf=false;e.disabled=false;e.textContent='Simpan Surat';toast('Tidak dapat terhubung ke server. Pastikan php artisan serve berjalan.')});}else{setTimeout(()=>done(local(),'Surat berhasil disimpan ke Arsip Surat.'),500)}},
savepdf(e){S.autoPdf=true;ACT.save(e)},again(){S=S0();go('buat')},ft(e){F.t=e.dataset.id;go('arsip')},reset(){F={q:'',t:'',d:''};go('arsip')},doc:e=>doc(+e.dataset.i),close:()=>document.querySelectorAll('.md').forEach(m=>m.classList.remove('on')),cp(){$('#cp').innerHTML='<div class="card cpc"><input class="in" id="cq" placeholder="Ketik perintah atau nama pemohon…" aria-label="Cari cepat"><div id="cr"></div></div>';$('#cp').classList.add('on');cpl();$('#cq').focus()},cmd(e){CMD()[+e.dataset.i].f();ACT.close()},th(){const h=document.documentElement,d=h.dataset.theme?h.dataset.theme=='dark':matchMedia('(prefers-color-scheme:dark)').matches;h.dataset.theme=d?'light':'dark'},
col(){$('#app').classList.toggle('col')},dr(){$('#sd').classList.toggle('open')},tg(e){const p=$('#lp'),h=p.type=='password';p.type=h?'text':'password';e.innerHTML=I(h?'off':'eye')},
out(){$('#app').classList.add('hid');$('#lg').classList.remove('hid');S=S0()}};
function zoom(d){S.z=Math.min(1.6,Math.max(.6,+(S.z+d).toFixed(1)));$('#pp').style.setProperty('--z',S.z);$('#zl').textContent=Math.round(S.z*100)+'%'}
document.addEventListener('click',e=>{if(e.target.classList.contains('md'))return ACT.close();
const c=e.target.closest('[data-c]');if(c){document.querySelectorAll('.bar').forEach(b=>b.classList.toggle('on',b==c));const series=monthSeries();const i=+c.dataset.c;$('#ci2').textContent=`${MON[i]} ${series[i].year} · ${MV[i]} surat`;return}
const d=e.target.closest('[data-d]');if(d){const i=+d.dataset.d;document.querySelectorAll('.sg2').forEach(s=>s.classList.toggle('hl',s.dataset.s==i));d.closest('.card').classList.add('dn2');document.querySelectorAll('.lgd button').forEach(b=>b.classList.toggle('on',b==d));$('#dc').innerHTML=`${DV[i]}<small class="hp" style="font-weight:500">${LT[i].name}</small>`;return}
const a=e.target.closest('[data-a]');if(a&&ACT[a.dataset.a]){e.stopPropagation();ACT[a.dataset.a](a)}});
document.addEventListener('keydown',e=>{if((e.ctrlKey||e.metaKey)&&e.key.toLowerCase()=='k'&&!$('#app').classList.contains('hid')){e.preventDefault();return ACT.cp()}if(e.target.id=='cq'){if(e.key=='Enter')$('#cr .rc')?.click();if(e.key=='ArrowDown'){e.preventDefault();$('#cr .rc')?.focus()}}if(e.target.matches('#cr .rc')&&/Arrow/.test(e.key)){e.preventDefault();(e.key=='ArrowDown'?e.target.nextElementSibling:e.target.previousElementSibling||$('#cq'))?.focus()}if(e.key=='Escape')ACT.close();if(e.key=='Enter'&&e.target.matches('.rw,.bar,[data-a=mk]'))e.target.click()});
document.addEventListener('mouseover',e=>{const c=e.target.closest('.bar,[data-d]');if(c)c.dispatchEvent(new MouseEvent('click',{bubbles:true}))});
document.addEventListener('focusin',e=>{if(e.target.matches('.bar'))e.target.click()});
document.addEventListener('input',e=>{const t=e.target;if(t.id=='cq')return cpl();if(t.id=='q'){F.q=t.value;return list()}if(t.id=='fdt'){F.d=t.value;return list()}
if(t.dataset.bk!==undefined){const a=kebutuhanList();a[+t.dataset.bk]=t.value;S.bantuanDaftarKebutuhan=a;return}
if(t.dataset.bl!==undefined){const a=lampiranList();a[+t.dataset.bl]=t.value;S.bantuanDaftarLampiran=a;return}
if(t.dataset.k&&/^anggota(Nama|Nik|Shdk)_\d+$/.test(t.dataset.k)){const m=t.dataset.k.match(/^anggota(Nama|Nik|Shdk)_(\d+)$/);const a=[...(S.anggota||[])];const i=+m[2];a[i]=a[i]||{};a[i][m[1]==='Nama'?'nama':m[1]==='Nik'?'nik':'shdk']=t.value;S.anggota=a;if($('#pin'))$('#pin').innerHTML=paperIn();return}
if(t.dataset.k&&/^tujuanAnggota(Nik|Nama|Shdk)_\d+$/.test(t.dataset.k)){const m=t.dataset.k.match(/^tujuanAnggota(Nik|Nama|Shdk)_(\d+)$/);const a=[...(S.tujuanAnggota||[])];const i=+m[2];a[i]=a[i]||{};a[i][m[1].toLowerCase()==='nik'?'nik':m[1].toLowerCase()==='nama'?'nama':'shdk']=t.value;S.tujuanAnggota=a;if($('#pin'))$('#pin').innerHTML=paperIn();return}
const k=t.dataset.k;if(!k)return;if(k==='nomorUrut'||k==='nik'||k==='nikKK'||k==='noKKAsal'||k==='anggotaNik'||k==='tujuanNIKKK'||k==='tujuanKK'||k==='rekeningOrang1NIK'||k==='rekeningOrang2NIK'||k==='deathCertNIK')t.value=t.value.replace(/\D/g,'').slice(0,16);S[k]=t.value;if(k==='masaMulai'){const end=document.querySelector('[data-k=masaSelesai]');if(end)end.min=t.value;}if(k==='masaMulai'||k==='masaSelesai')S.masaBerlaku=S.masaMulai&&S.masaSelesai?`${fmtDMY(S.masaMulai)} s/d ${fmtDMY(S.masaSelesai)}`:'';
if(V[k]){const ok=V[k](t.value);t.classList.toggle('ok',ok);t.classList.toggle('bad',!ok&&!!t.value);
const er=t.parentElement.querySelector('.er');if(er)er.textContent=!ok&&t.value?MSG[k]:''}
if($('#pin'))$('#pin').innerHTML=paperIn()});
/* login */
$('#lct').innerHTML=CT(10);$('#lgm').innerHTML=LOGO(48);$('#am').innerHTML=LOGO(42);$('#fm').innerHTML=LOGO(34);$('#tm').innerHTML=LOGO(28);document.querySelectorAll('[data-lg]').forEach(i=>i.src=LG[i.dataset.lg]);$('#dtx').textContent=new Date(TODAY).toLocaleDateString('id-ID',{weekday:'long',day:'numeric',month:'long',year:'numeric'});$('#th').innerHTML=I('moon');$('#cs').innerHTML=I('search',16);$('#ci').innerHTML=I('l');$('#oi').innerHTML=I('out');$('#mi').innerHTML=I('menu',24);document.querySelector('.eye').innerHTML=I('eye');
const LOGIN_USER='desamilangasri';
const LOGIN_PASS='Desara@2026';
$('#lf').addEventListener('submit',e=>{e.preventDefault();
const u=$('#lu'),p=$('#lp'),uv=u.value.trim(),pv=p.value;
const userOk=uv===LOGIN_USER,passOk=pv===LOGIN_PASS;$('#eu').textContent=userOk?'':'Username tidak sesuai.';$('#ep').textContent=passOk?'':'Password tidak sesuai.';u.classList.toggle('bad',!userOk);p.classList.toggle('bad',!passOk);if(!userOk||!passOk)return;
const b=$('#lb');b.disabled=true;b.textContent='Memproses…';setTimeout(()=>{const o=document.createElement('div');o.className='ok-ov';o.innerHTML='<div>'+I('check',44)+'<br>Berhasil masuk</div>';document.body.append(o);setTimeout(()=>{$('#lg').classList.add('hid');$('#app').classList.remove('hid');b.disabled=false;b.textContent='Masuk';p.value='';S=S0();go('dash');o.remove()},700)},500)});
document.addEventListener('pointermove',e=>{const h=e.target.closest?.('.hero');if(h){const b=h.getBoundingClientRect(),d=h.querySelector('.dcs');d.style.setProperty('--ry',((e.clientX-b.left)/b.width-.5)*16+'deg');d.style.setProperty('--rx',-((e.clientY-b.top)/b.height-.5)*12+'deg')}
const c=e.target.closest?.('.card,.st,.lc');if(c){const b=c.getBoundingClientRect();c.style.setProperty('--mx',e.clientX-b.left+'px');c.style.setProperty('--my',e.clientY-b.top+'px')}});
$('#lg').addEventListener('pointermove',e=>{const x=e.clientX/innerWidth-.5,y=e.clientY/innerHeight-.5;$('#lct').style.transform=`translate(${x*-28}px,${y*-18}px)`;$('.mt').style.transform=`translate(${x*14}px,0)`});
