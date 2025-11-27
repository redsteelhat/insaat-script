# İnşaat / Mimarlık Proje Yönetim Scripti - Kapsamlı Dokümantasyon (Construction360)

## 📋 İçindekiler

1. [Genel Bakış](#genel-bakış)  
2. [Teknoloji Stack](#teknoloji-stack)  
3. [Proje Yapısı](#proje-yapısı)  
4. [Kullanıcı Rolleri ve Yetkiler](#kullanıcı-rolleri-ve-yetkiler)  
5. [Public Site Modülleri](#public-site-modülleri)  
6. [Admin Panel Modülleri](#admin-panel-modülleri)  
7. [Saha / Şantiye Panel Modülleri (Mobile)](#saha--şantiye-panel-modülleri-mobile)  
8. [Müşteri Portalı (Opsiyonel)](#müşteri-portalı-opsiyonel)  
9. [Veritabanı Yapısı](#veritabanı-yapısı)  
10. [İş Akışları](#iş-akışları)  
11. [API ve Route Yapısı](#api-ve-route-yapısı)  
12. [Güvenlik Özellikleri](#güvenlik-özellikleri)  
13. [Özelleştirme Seçenekleri](#özelleştirme-seçenekleri)  
14. [Raporlama ve İstatistikler](#raporlama-ve-istatistikler)  
15. [Durum Yönetimi](#durum-yönetimi)  
16. [Mobil Uyumluluk](#mobil-uyumluluk)  
17. [Arama / Filtre / Export-Import](#arama--filtre--export-import)  
18. [Kod Standartları](#kod-standartları)  
19. [Bakım & Operasyon](#bakım--operasyon)  
20. [Roadmap](#roadmap)  

---

## 🎯 Genel Bakış

Bu proje, **inşaat firmaları** ve **mimarlık ofisleri** için geliştirilmiş uçtan uca bir yönetim sistemidir. Sistem, hem müşterilere yönelik kurumsal web sitesi (portfolio + hizmetler + teklif talebi) hem de firma içi operasyonel yönetim paneli (CRM, teklif, proje, şantiye, satınalma, finans, raporlama) içerir.

### Temel İş Hedefleri
- Lead (talep) → keşif → teklif → sözleşme → proje → şantiye yürütümü → hakediş/fatura → teslim süreçlerinin tek sistemde yönetilmesi
- Malzeme/satınalma ve bütçe kontrolü ile kârlılığın ölçülebilir hale getirilmesi
- Şantiye sahasından (mobil) **anlık fotoğraf + günlük rapor + ilerleme** verisi toplanması
- Müşteri iletişimi ve doküman servisinin standartlaştırılması (RFQ/teklif, revizyon, değişiklik emri)

### Temel Özellikler
- ✅ Kurumsal web sitesi (Public Site) + SEO
- ✅ Lead / Teklif Talebi (RFQ) ve CRM pipeline
- ✅ Teklif / Keşif / Metraj / BOQ (Bill of Quantities) yönetimi
- ✅ Sözleşme & Değişiklik Emri (Variation / Change Order) süreçleri
- ✅ Proje yönetimi (fazlar, iş kalemleri, görevler, takvim, teslimatlar)
- ✅ Şantiye günlük raporları, ilerleme yüzdesi, fotoğraf arşivi
- ✅ Satınalma (tedarikçi, teklif toplama, satınalma siparişi)
- ✅ Malzeme stok & şantiye tüketim hareketleri
- ✅ Finans (bütçe, gerçekleşen maliyet, hakediş, fatura, tahsilat/tediye)
- ✅ Raporlama (kârlılık, bütçe sapması, nakit akışı, pipeline)
- ✅ Mail/SMS bildirimleri, duyuru ve içerik yönetimi

---

## 🛠 Teknoloji Stack

### Backend
- **Framework:** Laravel 10
- **PHP:** 8.1+
- **Database:** MySQL / MariaDB
- **ORM:** Eloquent
- **Authentication:** Laravel Breeze (Blade Stack)

### Frontend
- **CSS Framework:** TailwindCSS
- **JavaScript:** Alpine.js (hafif interaktivite)
- **Charts:** Chart.js 4.x
- **Icons:** Heroicons (SVG)
- **Rich Text Editor:** Quill.js (teklif, not, blog, sözleşme şablonları)

### Diğer Teknolojiler
- **File Storage:** Laravel Storage (Public + Private disk)
- **Mail:** Laravel Mailables
- **Validation:** Form Request Classes
- **Middleware:** Custom Role-based Middleware
- **PDF:** Dompdf veya Snappy (WKHTMLTOPDF) (teklif, hakediş, fatura export)

---

## 📁 Proje Yapısı

```
construction360/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # Admin panel controller'ları
│   │   │   ├── Public/         # Public site controller'ları
│   │   │   ├── Site/           # Saha/Şantiye panel controller'ları
│   │   │   └── Client/         # Müşteri portal controller'ları (opsiyonel)
│   │   ├── Middleware/
│   │   │   └── RoleMiddleware.php
│   │   └── Requests/           # FormRequest sınıfları
│   ├── Models/                 # Eloquent modelleri
│   ├── Services/               # Domain servisleri (Teklif hesap, bütçe, stok vb.)
│   └── Policies/               # Yetkilendirme (opsiyonel)
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   └── views/
│       ├── layouts/
│       │   ├── admin.blade.php
│       │   ├── public.blade.php
│       │   ├── site.blade.php
│       │   └── client.blade.php
│       ├── admin/
│       ├── public/
│       ├── site/
│       └── client/
├── routes/
│   ├── web.php                 # Public + Panel route'ları
│   └── auth.php                # Breeze auth routes
└── storage/
    ├── app/public              # Public dosyalar (portfolio görselleri vb.)
    └── app/private             # Sözleşme, çizim, keşif dokümanları
```

---

## 👥 Kullanıcı Rolleri ve Yetkiler

Aşağıdaki roller, inşaat/mimarlık operasyonlarının gerçek iş bölümlerini destekleyecek şekilde tasarlanmıştır.

### 1) Anonim Ziyaretçi (Public)
**Erişim:** Public site

- ✅ Hizmetler, portföy, blog, ekip, referanslar
- ✅ “Teklif Al / Keşif Talep Et” formu
- ✅ İletişim formu

---

### 2) Lead / Müşteri Adayı (Prospect)
**Erişim:** Lead takip (opsiyonel “tek link ile teklif görüntüleme”)

- ✅ Talep numarası + telefon/e-posta ile talebini görüntüleme
- ✅ Keşif randevusu bilgisi ve teklif dokümanı görüntüleme
- ✅ Onay/ret, revizyon isteği (opsiyonel)

---

### 3) Müşteri (Client)
**Rol:** `client`
**Erişim:** Müşteri portalı (opsiyonel)

- ✅ Proje takvimi, ilerleme raporları (okuma)
- ✅ Paylaşılan dokümanlar (çizim PDF, sözleşme, hakediş)
- ✅ Toplantı/randevu kayıtları
- ✅ Fatura/hakediş görüntüleme, ödeme durumları
- ✅ İletişim mesajları (ticket)

---

### 4) Saha Personeli / Şantiye Şefi (Site Supervisor)
**Rol:** `site_supervisor`
**Erişim:** Saha paneli (`/saha`)

- ✅ Kendisine atanan projeleri görme
- ✅ Günlük şantiye raporu oluşturma (işçilik, ekipman, hava, fotoğraf, özet)
- ✅ İlerleme yüzdesi ve iş kalemi bazlı gerçekleşen metre/adet girişi
- ✅ Açık konular (issue/punch list) oluşturma ve kapatma
- ✅ Malzeme talebi oluşturma (depo/satınalma onaylı)
- ✅ Kalite kontrol checklist ve tutanak yükleme

---

### 5) Proje Yöneticisi (Project Manager)
**Rol:** `project_manager`
**Erişim:** Admin panel

- ✅ Lead → teklif → sözleşme → proje akışını yönetme
- ✅ Proje fazları, görevler, sorumlular, teslimatlar
- ✅ Şantiye raporlarını onaylama
- ✅ Değişiklik emirleri ve revizyon yönetimi
- ✅ Müşteri toplantıları ve aksiyon takibi

---

### 6) Mimarlık / Mühendislik (Architect/Engineer)
**Rol:** `designer`
**Erişim:** Admin panel

- ✅ Tasarım teslimatları, çizim versiyonları, RFI ve revizyonlar
- ✅ Metraj/BOQ satırları (birim fiyat, açıklama)
- ✅ Teknik doküman arşivi
- ✅ İzin/ruhsat checklist

---

### 7) Satınalma (Procurement)
**Rol:** `procurement`
**Erişim:** Admin panel

- ✅ Tedarikçi yönetimi
- ✅ Teklif toplama (RFQ) → karşılaştırma → satınalma siparişi (PO)
- ✅ Depo/şantiye sevkiyat planlama
- ✅ Malzeme fiyat geçmişi

---

### 8) Muhasebe / Finans (Finance)
**Rol:** `finance`
**Erişim:** Admin panel

- ✅ Bütçe ve gerçekleşen maliyet kayıtları
- ✅ Hakediş/fatura oluşturma
- ✅ Tahsilat/tediye yönetimi
- ✅ Kasa/banka hareketleri (opsiyonel entegrasyon alanı)
- ✅ Nakit akışı raporları

---

### 9) Yönetici (Admin)
**Rol:** `admin`
**Erişim:** Tüm modüller

- ✅ Kullanıcı/rol yönetimi, sistem ayarları, yedekleme, log, şablon yönetimi
- ✅ Çoklu şube / çoklu firma (SaaS) opsiyonu için tenant yönetimi

---

## 🌐 Public Site Modülleri

### 1) Ana Sayfa (`/`)
**Controller:** `Public\HomeController@index`

**Bölümler:**
1. **Hero Slider**: Proje fotoğrafları + CTA (“Teklif Al”, “Portföyü Gör”)
2. **Hizmet Kategorileri**: Mimari Proje, İç Mimari, İnşaat Taahhüt, Tadilat, Anahtar Teslim
3. **Öne Çıkan Projeler**: Portföyden seçilmiş 6 proje (filtre: konut/ticari/endüstriyel)
4. **Süreç Anlatımı**: Keşif → Tasarım → İmalat → Teslim (4 adım, ikonlu)
5. **Referanslar / Müşteri Yorumları**
6. **Blog Önizleme**
7. **Footer**: İletişim, çalışma saatleri, sosyal medya

---

### 2) Hizmetler (`/hizmetler`)
**Controller:** `Public\ServiceController`

- Liste: `/hizmetler`
- Detay: `/hizmetler/{slug}`
- Her hizmet için SEO alanları (meta title/description)
- “Teklif Al” CTA

---

### 3) Portföy / Projeler (`/projeler`)
**Controller:** `Public\PortfolioController`

- Liste: `/projeler` (filtre: proje türü, yıl, lokasyon)
- Detay: `/projeler/{slug}`
- Galeri, proje bilgileri (m², yıl, lokasyon, süre, hizmet tipi)
- “Benzer Projeler” widget

---

### 4) Keşif / Teklif Talebi Formu (`/teklif-al`)
**Controller:** `Public\LeadController`

**Form Alanları (örnek):**
- Ad Soyad / Firma (zorunlu)
- Telefon (zorunlu, TR format)
- E-posta (opsiyonel)
- Proje türü (Konut/Ticari/Endüstriyel/Tadilat)
- Lokasyon (il/ilçe)
- Yaklaşık m² / oda sayısı / kat sayısı
- Mevcut durum (arsa var / proje var / kaba var / tadilat)
- İstenen hizmet (mimari proje, iç mimari, taahhüt, anahtar teslim)
- Bütçe aralığı (opsiyonel)
- İstenen tarih (keşif)
- Mesaj / ihtiyaç detayı
- KVKK onayı (zorunlu)
- Anti-spam: Matematik sorusu

**İş Akışı:**
1. Form gönderimi → Validation
2. `Lead` kaydı (`status = "new"`) + talep numarası
3. Otomatik e-posta (opsiyonel) + SMS (opsiyonel)
4. Admin panelde pipeline’a düşer

---

### 5) Blog (`/blog`) ve İletişim (`/iletisim`)
- Blog liste + detay (Quill içerik)
- İletişim formu + Google Maps iframe + WhatsApp CTA

---

## 🎛 Admin Panel Modülleri

### 1) Dashboard (`/admin`)
**Controller:** `Admin\DashboardController@index`

**KPI Örnekleri:**
- Pipeline: Yeni lead / aktif teklif / kazanılan / kaybedilen
- Aktif Projeler: devam eden / geciken / tamamlanan
- Nakit: bu ay tahsilat / bu ay ödeme / kalan alacak-borç
- Bütçe: bütçe vs gerçekleşen (sapma)

**Grafikler:**
- Aylık lead ve teklif sayısı
- Aylık ciro ve maliyet
- Proje durum dağılımı (pie)
- En çok tüketilen malzeme (bar)

Filtre: Bugün / Bu Hafta / Bu Ay / Tarih aralığı

---

### 2) Lead & CRM Yönetimi

#### 2.1) Lead Listesi (`/admin/leads`)
**Controller:** `Admin\LeadController@index`

- Filtreler: durum, tarih, lokasyon, proje türü, kaynak (web/telefon/referral)
- Arama: isim, telefon, talep no
- Aksiyonlar: görüntüle, durum değiştir, keşif planla, teklife dönüştür

#### 2.2) Keşif (Site Visit) Planlama
- Randevu oluşur (takvim)
- Atanan kişi: PM / mimar / saha
- İlgili dokümanlar: plan, foto, arsa bilgisi
- Keşif sonucu notları → Teklif hazırlığının input’u

---

### 3) Teklif / Metraj / BOQ Modülü

#### 3.1) Teklif Listesi (`/admin/quotes`)
**Controller:** `Admin\QuoteController@index`

- Teklif no, müşteri, toplam, KDV, iskonto, durum (draft/sent/approved/rejected)
- PDF export + e-posta ile gönderim
- Revizyon yönetimi (v1, v2, v3)
- Onay akışı (iç onay → müşteri onayı) (opsiyonel)

#### 3.2) BOQ Satırları (Birim Fiyat Analizi)
- İş kalemi: kod, açıklama, birim (m², m³, adet), miktar
- Birim fiyat = malzeme + işçilik + genel gider + kâr
- Otomatik toplamlar: ara toplam, KDV, iskonto
- Şablon: benzer proje kalemlerinden kopyalama

---

### 4) Sözleşme & Değişiklik Emri

#### 4.1) Sözleşmeler (`/admin/contracts`)
- Sözleşme şablonları (rich text) + değişkenler: müşteri adı, proje adı, bedel, termin vb.
- Versiyonlama + imzalı PDF yükleme
- Teminat/avans bilgileri

#### 4.2) Change Order (`/admin/change-orders`)
- Talep kaynağı: müşteri / saha / proje
- Etki analizi: maliyet + süre
- Onay: PM → müşteri
- Onay sonrası bütçeye ve planlamaya otomatik yansıma

---

### 5) Proje Yönetimi

#### 5.1) Projeler (`/admin/projects`)
**Controller:** `Admin\ProjectController`

**Proje Alanları (özet):**
- Proje kodu, adı, müşteri, lokasyon, tür, başlangıç/bitiriş hedefi
- Sözleşme bedeli, KDV, ödeme planı
- Proje sorumluları (PM, tasarım, saha, procurement, finance)
- Dokümanlar: çizimler, keşif, ruhsat, tutanaklar

#### 5.2) Fazlar & Görevler (`/admin/projects/{id}/tasks`)
- Fazlar: Tasarım, Ruhsat, Kaba, İnce, Mekanik/Elektrik, Peyzaj, Teslim
- Görevler: atanan kişi, deadline, bağımlılıklar (opsiyonel)
- Basit Gantt görünümü (opsiyonel)
- Checklists (kalite, teslim) (opsiyonel)

---

### 6) Şantiye / Saha Operasyonları (Admin Perspektifi)

#### 6.1) Günlük Raporlar (`/admin/site-reports`)
- Proje bazlı filtre
- Rapor detayları, fotoğraf galerisi, onay/ret
- Hava koşulları, ekip sayısı, yapılan işler, engeller

#### 6.2) Punch List / Issues (`/admin/issues`)
- Kategoriler: kalite, iş güvenliği, tedarik, tasarım, müşteri
- SLA ve sorumlu atama
- Kanban görünümü (opsiyonel)

---

### 7) Satınalma & Tedarik

#### 7.1) Tedarikçiler (`/admin/vendors`)
- Firma bilgileri, vergi, iletişim, lokasyon
- Kategoriler: beton, demir, seramik, elektrik, mekanik vb.
- Fiyat listeleri (opsiyonel)

#### 7.2) RFQ ve PO (`/admin/procurements`, `/admin/purchase-orders`)
- Malzeme talepleri (şantiyeden / planlı)
- Teklif toplama (vendor bazlı) + karşılaştırma tablosu
- Satınalma siparişi (PO): teslim tarihi, sevk adresi, kalemler
- Teslimat onayı + irsaliye/fatura yükleme

---

### 8) Malzeme / Stok / Depo

#### 8.1) Malzemeler (`/admin/materials`)
- Malzeme kartı: kod, ad, birim, min stok, kategori
- Birim maliyet (son alış, ortalama, FIFO opsiyonel)

#### 8.2) Stok Hareketleri (`/admin/material-transactions`)
- Giriş: satınalma, iade
- Çıkış: şantiye tüketim, fire
- Şantiye bazlı alt depo (site warehouse) (opsiyonel)
- Düşük stok uyarısı

---

### 9) Finans: Bütçe / Hakediş / Fatura / Ödeme

#### 9.1) Bütçe (`/admin/budgets`)
- Proje bütçesi: BOQ satırlarından üretilebilir
- Gerçekleşen maliyet: satınalma + işçilik + taşeron + genel gider
- Sapma analizi (variance)

#### 9.2) Hakediş (`/admin/progress-billings`)
- Dönemsel hakediş: yüzde, miktar, iş kalemi gerçekleşen
- PDF export + müşteri gönderimi
- Onaya göre fatura kesimi (opsiyonel)

#### 9.3) Faturalar & Tahsilat (`/admin/invoices`, `/admin/collections`)
- Fatura durumu: taslak, kesildi, gönderildi, ödendi, gecikti
- Tahsilat planı: vade, kısmi ödeme, ödeme yöntemi
- Alacak yaşlandırma raporu

#### 9.4) Ödemeler & Tediye (`/admin/payments`)
- Tedarikçi ödemeleri, taşeron ödemeleri
- Nakit akışı görünümü (opsiyonel)

---

### 10) Kullanıcılar, İçerik, Log ve Sistem

- Kullanıcı/rol yönetimi
- Blog, slider, referans, portföy içerik yönetimi
- Aktivite logları
- Yedekleme
- Site ayarları (logo, renk, SEO, sosyal medya, mail/sms)

---

## 🔧 Saha / Şantiye Panel Modülleri (Mobile)

### 1) Dashboard (`/saha`)
**Controller:** `Site\DashboardController@index`

- Bugünkü yapılacaklar
- Atanmış projeler
- Açık issue listesi
- Hızlı aksiyon: “Günlük Rapor”, “Fotoğraf”, “Malzeme Talebi”, “Issue Aç”

### 2) Günlük Şantiye Raporu (`/saha/projects/{id}/daily-report`)
**Controller:** `Site\DailyReportController`

**Alanlar:**
- Tarih, hava, çalışma saati aralığı
- Ekip: taşeron/ekip sayısı, çalışma alanları
- Yapılan iş kalemleri (seçim + miktar)
- Kullanılan malzeme (seçim + miktar)
- Fotoğraf/video yükleme
- Risk/engel notu (ör: teslimat gecikmesi)
- İSG (iş güvenliği) checklist

### 3) İlerleme Girişi (`/saha/projects/{id}/progress`)
- Faz/iş kalemi bazlı gerçekleşme
- Basit % tamamlanma + açıklama
- Admin panelde bütçe ve hakedişle ilişkilendirilebilir

### 4) Malzeme Talebi (`/saha/material-requests`)
- Malzeme, miktar, ihtiyaç tarihi
- Onay akışı: saha → procurement → finance (opsiyonel)
- Onay sonrası PO sürecine düşer

### 5) Issues / Punch List (`/saha/issues`)
- Fotoğraf + konum/detay
- Sorumlu atama (PM / tasarım / procurement)
- Durum: açık, işlemde, kapalı
- SLA/son tarih

---

## 🤝 Müşteri Portalı (Opsiyonel)

### Amaç
Müşteriye “tek ekran” üzerinden **ilerleme**, **doküman**, **hakediş/fatura** görünürlüğü sağlar; iletişim maliyetini düşürür, proje memnuniyetini yükseltir.

### Modüller
- Proje özeti (takvim, ilerleme, milestone)
- Dokümanlar (paylaşılan klasör)
- Hakediş/fatura listesi + ödeme durumu
- Toplantı notları + aksiyon listesi
- Mesajlaşma / ticket (opsiyonel)

---

## 💾 Veritabanı Yapısı

### Tablolar (Öneri 45+ Migration)

#### 1) Kullanıcı ve Yetkilendirme
- `users` (role, title, phone, profile_photo)
- `password_reset_tokens`
- `personal_access_tokens` (opsiyonel: API)

#### 2) CRM / Lead
- `leads` (talep no, kaynak, proje türü, lokasyon, ihtiyaç özeti, status)
- `lead_activities` (arama, mail, toplantı, not)
- `meetings` (keşif ve toplantılar)

#### 3) Teklif / BOQ
- `quotes` (lead_id, version, totals, status, sent_at)
- `quote_items` (code, description, unit, qty, unit_price, totals)
- `quote_templates` (kalem şablonları)

#### 4) Sözleşme / Change Order
- `contracts` (project_id, version, amount, terms, signed_file)
- `change_orders` (project_id, source, impact_cost, impact_days, status)
- `change_order_items` (kalemler)

#### 5) Proje Yönetimi
- `projects` (code, client_id, site_id, status, start/end, contract_amount)
- `project_phases`
- `project_tasks`
- `project_users` (pivot: proje-sorumlu)
- `milestones`

#### 6) Şantiye / Saha Verisi
- `daily_site_reports`
- `site_report_photos`
- `project_progress_entries` (faz/kalem bazlı)
- `issues` (punch list)
- `issue_comments`
- `inspections` (kalite/İSG)
- `checklists` & `checklist_items` (opsiyonel)

#### 7) Lokasyon / Şantiye
- `sites` (adres, koordinat, il/ilçe)
- `regions` (il/ilçe hiyerarşisi) (opsiyonel)

#### 8) Tedarik / Satınalma
- `vendors`
- `vendor_categories`
- `rfqs` (vendor bazlı teklif toplama)
- `rfq_items`
- `purchase_orders`
- `purchase_order_items`
- `deliveries` (teslimat / irsaliye)

#### 9) Malzeme / Stok
- `materials`
- `material_categories`
- `warehouses` (ana depo + şantiye depoları)
- `material_stocks` (warehouse/material)
- `material_transactions` (giriş/çıkış)
- `material_requests` (saha talebi)
- `material_request_items`

#### 10) Finans
- `budgets`
- `budget_lines`
- `cost_entries` (gerçekleşen maliyetler)
- `invoices`
- `invoice_items`
- `collections` (tahsilatlar)
- `payments` (ödemeler)
- `cash_accounts` (kasa/banka) (opsiyonel)

#### 11) İçerik Yönetimi (Public Site)
- `content_services`
- `portfolio_projects`
- `portfolio_photos`
- `blog_posts`
- `slider_items`
- `testimonials`

#### 12) Sistem
- `site_settings` (key-value)
- `activity_logs`
- `backups`
- `notifications` (opsiyonel)

---

## 🔄 İş Akışları

### 1) Lead → Teklif → Sözleşme → Proje Akışı

```
1. Ziyaretçi → Public Site → Teklif Talep Formu
   ↓
2. Lead oluşur (status="new")
   ↓
3. Keşif planlanır (meeting/site_visit)
   ↓
4. Teklif hazırlanır (BOQ + fiyat)
   ↓
5. Teklif PDF gönderilir (status="sent")
   ↓
6. Müşteri onayı → status="approved"
   ↓
7. Sözleşme oluşturulur + imzalı doküman yüklenir
   ↓
8. Proje oluşturulur (status="planned")
   ↓
9. Planlama: fazlar/görevler → şantiye başlar (status="in_progress")
   ↓
10. Günlük raporlar + ilerleme girişleri
   ↓
11. Hakediş → fatura → tahsilat
   ↓
12. Teslim & kapanış (status="completed" / "handed_over")
```

### 2) Satınalma (Procurement) Akışı

```
1. Şantiye malzeme talebi (material_request)
   ↓
2. Procurement: RFQ açar → vendor teklifleri toplanır
   ↓
3. Karşılaştırma + onay
   ↓
4. PO oluşturulur (purchase_order)
   ↓
5. Teslimat/irsaliye → depo girişi
   ↓
6. Fatura → ödeme planı → tediye
```

### 3) Change Order Akışı

```
1. Değişiklik talebi (müşteri/saha)
   ↓
2. Etki analizi (maliyet + süre)
   ↓
3. PM iç onay → müşteri onayı
   ↓
4. Bütçe + plan + sözleşme ek protokol güncellenir
   ↓
5. Uygulama ve raporlama
```

### 4) Issue / Punch List Akışı

```
1. Saha issue açar (foto + açıklama)
   ↓
2. Sorumlu atanır + SLA set edilir
   ↓
3. İşlemde → doğrulama
   ↓
4. Kapatma + kapanış notu (kanıt foto)
```

---

## 🛣 API ve Route Yapısı

### Public Routes (`/`)
- `GET /` - Ana sayfa
- `GET /hizmetler` - Hizmet listesi
- `GET /hizmetler/{slug}` - Hizmet detay
- `GET /projeler` - Portföy
- `GET /projeler/{slug}` - Proje detay
- `GET /teklif-al` - Teklif talep formu
- `POST /teklif-al` - Lead oluşturma
- `GET /blog` - Blog listesi
- `GET /blog/{slug}` - Blog detay
- `GET /iletisim` - İletişim
- `POST /iletisim` - İletişim formu

### Admin Routes (`/admin`)
- `GET /admin` - Dashboard
- `resources`: `leads`, `quotes`, `projects`, `contracts`, `change-orders`
- `resources`: `vendors`, `materials`, `warehouses`, `purchase-orders`
- `resources`: `budgets`, `invoices`, `collections`, `payments`
- `resources`: `daily-site-reports`, `issues`, `inspections`
- `resources`: `content-services`, `portfolio-projects`, `blog-posts`, `sliders`, `testimonials`
- `resources`: `users`, `activity-logs`, `backups`, `settings`

### Saha Routes (`/saha`)
- `GET /saha` - Dashboard
- `GET /saha/projects` - Atanan projeler
- `GET /saha/projects/{id}/daily-report` - Günlük rapor formu
- `POST /saha/projects/{id}/daily-report` - Günlük rapor kaydı
- `POST /saha/projects/{id}/progress` - İlerleme girişi
- `resources`: `material-requests`, `issues`

### Client Portal (`/musteri`) (opsiyonel)
- `GET /musteri` - Proje özeti
- `GET /musteri/projects/{id}` - Proje detay
- `GET /musteri/documents` - Dokümanlar
- `GET /musteri/invoices` - Faturalar/hakediş

### Auth Routes
- `/login`, `/register`, `/forgot-password` (Breeze)

---

## 🔒 Güvenlik Özellikleri

- Authentication: Breeze + e-posta doğrulama
- Authorization: role middleware + (opsiyonel) policy/gate
- CSRF, XSS, SQLi korumaları (Laravel default + best practice)
- Dosya güvenliği: mime/type, size limiti, private disk, erişim kontrolü
- Audit: activity logs (kim, neyi, ne zaman yaptı)
- Anti-spam: public formlarda matematik + honeypot (opsiyonel)
- Rate limiting: public form endpoint’lerinde (opsiyonel)

---

## 🎨 Özelleştirme Seçenekleri

### 1) Kurumsal Kimlik
- Primary/secondary/accent renkler (CSS variables)
- Logo / favicon / kapak görsel
- Tipografi ölçeği

### 2) SEO & Analytics
- Global meta title/description + sayfa bazlı override
- Google Analytics / Tag Manager alanı

### 3) Doküman Şablonları
- Teklif şablonları
- Sözleşme şablonları
- Hakediş şablonları
- E-posta metin şablonları

### 4) Numaralandırma
- Lead no, teklif no, proje kodu, PO no, fatura no formatları (prefix + yıl + sıra)

### 5) Para Birimi & Vergi
- KDV oranı, para birimi (TRY/USD/EUR)
- Yuvarlama kuralları

---

## 📊 Raporlama ve İstatistikler

### CRM / Satış
- Pipeline raporu (aşama bazlı adet/tutar)
- Win-rate, ortalama kapanış süresi
- Kaynak bazlı lead performansı

### Proje
- Proje ilerleme (%), gecikme trendi
- Açık issue sayısı ve SLA ihlalleri
- Faz bazlı tamamlanma

### Finans
- Bütçe vs gerçekleşen (variance)
- Proje kârlılığı (brüt kâr, net kâr) (opsiyonel)
- Nakit akışı (tahsilat vs ödeme)
- Alacak yaşlandırma

### Satınalma / Malzeme
- En çok alım yapılan tedarikçiler
- Malzeme tüketim trendi
- Stok devir hızı, düşük stok listesi

---

## 🔄 Durum Yönetimi

### Lead Status
- `new` → `contacted` → `site_visit_planned` → `quoted` → `won` / `lost`

### Quote Status
- `draft` → `sent` → `approved` / `rejected` → `contracted`

### Project Status
- `planned` → `in_progress` → `on_hold` → `completed` → `handed_over`

### Issue Status
- `open` → `in_progress` → `resolved` → `closed`

### Procurement Status
- RFQ: `open` → `closed`
- PO: `draft` → `sent` → `delivered` → `invoiced` → `paid`

---

## 📱 Mobil Uyumluluk

- Mobile first tasarım (saha paneli kritik)
- Breakpoints: <768 / 768-1024 / >1024
- Fotoğraf yükleme, offline-friendly UX (opsiyonel: PWA)

---

## 🔍 Arama / Filtre / Export-Import

### Arama ve Filtreleme
- Liste ekranlarında: durum, tarih, lokasyon, proje, sorumlu, vendor, kategori bazlı filtre
- Tablo kolonlarında sıralama + pagination (10/25/50/100)

### Export
- CSV: leads, quotes, projects, budgets, invoices, materials, vendors, issues, site reports

### Import
- CSV: materials, vendors, clients (opsiyonel)

---

## 📝 Kod Standartları

- Laravel conventions + PSR-12
- Laravel Pint ile formatlama
- Controller’lar resourceful
- Transaction kullanım standardı (özellikle finans ve stok hareketlerinde)
- Domain servisleri: teklif hesaplama, stok düşüm, bütçe güncelleme

---

## 🔧 Bakım & Operasyon

### Günlük
- Log kontrolü, hata izleme
- Şantiye raporlarının onayı
- Kritik issue takibi

### Haftalık
- Veritabanı yedeği
- Cache temizliği
- Depo stok mutabakatı (opsiyonel)

### Aylık
- Versiyon güncellemeleri
- Performans optimizasyonu
- Eski log/temporary dosya temizlikleri

---

## 📈 Roadmap

### Kısa Vadeli
- [ ] PDF export standartları (teklif/hakediş/fatura) ve şablon editörü
- [ ] Material request → RFQ → PO otomasyonu (tam akış)
- [ ] Müşteri portalı MVP

### Orta Vadeli
- [ ] Gantt + bağımlılık yönetimi
- [ ] PWA saha paneli (offline cache)
- [ ] Çoklu şantiye depo (warehouse) ve transfer işlemleri

### Uzun Vadeli
- [ ] BIM/IFC viewer entegrasyonu (web)
- [ ] E-fatura/ERP entegrasyonları
- [ ] Çoklu firma/tenant (SaaS)
- [ ] Mobil uygulama (React Native)

---

## ✅ Son Güncelleme

**Tarih:** 27 Kasım 2025  
**Versiyon:** 1.0.0  
**Durum:** Production Ready (MVP kapsamı)
