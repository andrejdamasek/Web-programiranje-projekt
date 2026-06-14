USE travnjak_centar;

-- =============================================
-- CATEGORIES (idempotentno)
-- =============================================
INSERT INTO categories (name, slug) VALUES
  ('Kosilice',    'kosilice'),
  ('Trimeri',     'trimeri'),
  ('Sjeme trave', 'sjeme-trave')
ON DUPLICATE KEY UPDATE
  name = VALUES(name);

-- =============================================
-- KOSILICE  (category_id = 1)
-- =============================================
INSERT INTO products
  (category_id, name, brand, short_description, description, price, stock,
   power_type, blade_type, cutting_width_cm, basket_capacity_l, weight_kg, image_url, featured)
VALUES
(
  1,
  'Akumulatorska kosilica MAKITA DLM382CM2 SET',
  'Makita',
  'Akumulatorska kosilica s 3 funkcije u 1 uređaju i košarom od 40 L.',
  'Prednosti MAKITA DLM382CM2 SETA: 3 funkcije u 1 uređaju: košnja, sakupljanje, mogućnost malčiranja; način niske buke; indikator za punu košaru trave; indikator napunjenosti akumulatora; posebna izvedba omogućuje jednostavno spremanje, održavanje i čišćenje u uspravnom položaju; izdržljivi gumirani kotači s kugličnim ležajem na osovinama omogućuju dobru pokretljivost.',
  672.90, 5, 'akumulatorski', 'rotary', 38.00, 40.00, 24.00,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/75713/374870.jpg.webp?t=1693453081',
  1
),
(
  1,
  'Akumulatorska kosilica Einhell GE-CM 18/33 Li (1x4,0Ah)',
  'Einhell',
  'Akumulatorska kosilica s motorom bez četkica, baterijom 18V/4Ah i košarom od 45 L za površine do 200 m².',
  'Einhell GE-CM 18/33 Li (1x4,0Ah) akumulatorska kosilica je član fleksibilne i snažne serije Power X-Change i preporučuje se za površine do 200 m². Akumulatorska kosilica radi s punjivom baterijom od 18 V od 4,0 Ah serije sustava PXC koja je jednostavna za korištenje. Punjiva baterija i punjač uključeni su u opseg isporuke. Specifikacije: baterija 18V (1x) | 4000 mAh | Li-Ion; širina reza 33 cm; centralno podešavanje visine košnje u 6 postavki (25-75 mm); kapacitet košare 45 L; promjer prednjih kotača 150 mm; promjer stražnjih kotača 220 mm. Težina proizvoda: 13,05 kg.',
  209.90, 8, 'akumulatorski', 'rotary', 33.00, 45.00, 13.05,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/55118/347623_7.jpg.webp?t=1742824112',
  0
),
(
  1,
  'Akumulatorska kosilica Einhell GE-CM 36/37 LI-SOLO',
  'Einhell',
  'Bežična kosilica s Power X-Change baterijama, širinom košnje 37 cm i košarom od 45 L (bez baterije i punjača).',
  'Njega travnjaka je laka uz Einhell akumulatorsku kosilicu GE-CM 36/37 Li-Solo. Snažan bežični rad omogućen je zahvaljujući dokazanim snažnim Power X-Change baterijama. Podešavanje visine košnje u šest razina od 25 do 75 mm i širina košnje do 37 cm daju sve što je potrebno da travnjak izgleda savršeno. Integrirani češalj za travu omogućuje košenje trave do rubova. Ergonomski oblikovane ručke osiguravaju neumorni rad, a duga ručka podesiva po visini može se individualno prilagoditi svakom korisniku. Kosilica je čvrsta i ima laganu dugu ručku od aluminija. Integrirana ručka za nošenje čini kosilicu lakom za transport. Košara za travu ima kapacitet od 45 litara s indikatorom napunjenosti. Broj okretaja: 3400 min⁻¹. NAPOMENA: Baterija i punjač nisu uključeni u isporuku.',
  249.90, 6, 'akumulatorski', 'rotary', 37.00, 45.00, 14.70,
  'https://cdn.pevex.hr/Documents/image_style/product_list_item/Documents/Products/62722/359213_1_1.jpg.jpg.webp?t=1779343907',
  0
),
(
  1,
  'Motorna kosilica Prorun samohodna TLM 51 SM',
  'Prorun',
  'Snažna samohodna motorna kosilica s motorom 2,7 kW, 4u1 funkcijama i čeličnim kućištem.',
  'Motorna kosilica Prorun samohodna TLM 51 SM nudi izvanrednu kombinaciju snage i funkcionalnosti za učinkovito održavanje travnjaka. S motorom zapremnine 166 cm³ koji razvija snagu od 2,7 kW (3,67 KS) pri 2900 okretaja u minuti, kosilica je spremna za bilo koji zadatak. Kapacitet spremnika goriva: 1 litra; kapacitet spremnika ulja: 0,5 litara. Čelično kućište nudi izdržljivost i dugovječnost. 4u1 mogućnosti: skupljanje u košaru, malčiranje, stražnje izbacivanje i bočno izbacivanje. Radna širina: 51 cm. Centralno podešavanje visine košnje s različitim opcijama prilagodbe. Kapacitet košare: 65 L. Jamstvo: 24 mj.',
  389.90, 5, 'benzinski', 'rotary', 51.00, 65.00, 33.30,
  'https://cdn.pevex.hr/Documents/image_style/product_list_item/Documents/Products/88866/393572.jpg.webp?t=1737021111',
  0
),
(
  1,
  'Akumulatorska kosilica Einhell GP-CM 36/41 LI SOLO',
  'Einhell',
  'Profesionalna akumulatorska kosilica s Brushless motorom, košarom od 50 L i košnjom do ruba travnjaka.',
  'Einhell GP-CM 36/41 Li-Solo akumulatorska kosilica s kotačima prilagođenim travnjaku ima 6-stupanjsko centralno podešavanje visine rezanja od 25-75 mm. Uređaj pokreće Einhell PurePOWER Brushless motor bez četkica za više snage i duže vrijeme rada. Nakon online registracije, na motor vrijedi 10 godina jamstva. Integrirani češalj za travnjak omogućuje košnju blizu ruba. Stabilna i lagana aluminijska ručka za upravljanje je podesiva po visini i sklopiva, a ergonomski oblikovano područje ručke osigurava rad bez umora. Integrirana ručka za nošenje osigurava jednostavan transport. Košara za sakupljanje trave od 50 L opremljena je indikatorom razine napunjenosti. Napon: 2x18 V; širina reza: 41 cm; promjer prednjih kotača: 150 mm; promjer stražnjih kotača: 220 mm; težina: 15,2 kg. Za rad su potrebne 2x18V PXC baterije - isporuka bez baterije i punjača.',
  319.90, 4, 'akumulatorski', 'rotary', 41.00, 50.00, 15.20,
  'https://cdn.pevex.hr/Documents/image_style/product_list_item/Documents/Products/85662/388279.jpg.webp?t=1718711559',
  1
),
(
  1,
  'Motorna kosilica AL-KO samohodna Highline 51.9 SP-H',
  'AL-KO',
  'Samohodna motorna kosilica s Honda motorom i košarom od 70 L.',
  'Benzinska kosilica Highline 51.9 SP-H, motor Honda GCV 170, radni zahvat 51 cm, centralno 7-struko podešavanje visine košnje, koš 70 litara, funkcije 4 u 1: sakupljanje, bočno izbacivanje, stražnje izbacivanje i malčiranje.',
  729.90, 5, 'benzinski', 'rotary', 51.00, 70.00, 41.20,
  'https://cdn.pevex.hr/Documents/image_style/product_list_item/Documents/Products/36993/321122_2.png.webp?t=1763125459',
  1
),
(
  1,
  'Motorna kosilica AL-KO EASY 5.10 SP-S samohodna',
  'AL-KO',
  'Samohodna motorna kosilica s motorom 2,4 kW, 4u1 funkcionalnošću i platnenom košarom od 60 L.',
  'Uživaj u besprijekornom košenju s motornom kosilicom AL-KO EASY 5.10 SP-S SAMOHODNA. Ova samohodna kosilica s AL-KO Tech 140 motorom obujma 160 ccm i nominalnom snagom od 2,4 kW pruža iznimne performanse pri 2850 okretaja u minuti. Radna širina od 51 cm i 4-u-1 funkcionalnost (stražnje izbacivanje, sakupljanje u koš, malčiranje i bočno izbacivanje) čine je svestranom. Centralno podešavanje visine košnje u 7 stupnjeva, visina košnje od ca. 2,5-7,5 cm. Brzina: ca. 3,5 km/h; kotači (p/s) 200/250 mm s kugličnim ležajevima. Čelično kućište, platneni koš od 60 litara. Pogon na stražnjim kotačima s jednom brzinom.',
  499.90, 5, 'benzinski', 'rotary', 51.00, 60.00, 31.80,
  'https://cdn.pevex.hr/Documents/image_style/product_list_item/Documents/Products/43420/330638_kosilica_elektri_na_al-ko_easy_5.10_sp-s_samohodna_2.jpg.jpg.webp?t=1774349175',
  0
),
(
  1,
  'Motorna kosilica Smartool S463VHY-T6 samohodna',
  'Smartool',
  'Samohodna motorna kosilica s motorom 2,6 kW, čeličnim kućištem i košarom od 50 L.',
  'Motorna kosilica Smartool S463VHY-T6 samohodna je kosilica s velikom košarom za sakupljanje. Stroj ima snažan motor od 2,6 kW, koji osigurava da održava dobre brzine i može se nositi s većinom terena. Značajke: način vožnje samohodna; obujam motora 146 cm³; snaga 2,6 kW; kapacitet spremnika goriva 0,8 L; kapacitet spremnika ulja 0,5 L; čelično kućište; širina rezanja 45,7 cm; centralno podešavanje visine košnje u 7 položaja (25-70 mm); kotači 17,78 cm (prednji) / 25,40 cm (stražnji) s kugličnim ležajem; 4u1: prikupljanje, malčiranje, bočno i stražnje izbacivanje; volumen košare 50 L; priključak za vodu. Težina: 25,40 kg. Jamstvo: 24 mj.',
  259.90, 8, 'benzinski', 'rotary', 45.70, 50.00, 25.40,
  'https://cdn.pevex.hr/Documents/image_style/product_list_item/Documents/Products/67092/363488.jpg.webp?t=1709302446',
  0
)
ON DUPLICATE KEY UPDATE
  price        = VALUES(price),
  stock        = VALUES(stock),
  description  = VALUES(description),
  image_url    = VALUES(image_url),
  featured     = VALUES(featured);

-- =============================================
-- TRIMERI  (category_id = 2)
-- =============================================
INSERT INTO products
  (category_id, name, brand, short_description, description, price, stock,
   power_type, blade_type, cutting_width_cm, basket_capacity_l, weight_kg, image_url, featured)
VALUES
(
  2,
  'Akumulatorski trimer Einhell GE-CT 18 Li Solo',
  'Einhell',
  'Lagani akumulatorski trimer s plastičnim nožićima, širinom reza 24 cm i PXC baterijom 18V (bez baterije).',
  'Einhell GE-CT 18 Li Solo je snažan i praktičan akumulatorski trimer za travu iz Power X-Change obitelji, idealan za održavanje travnjaka na mjestima nedostupnim klasičnoj kosilici. Podesiva glava motora i teleskopska aluminijska ručka omogućuju precizno podešavanje prema korisniku. Isporuka uključuje 20 komada plastičnih nožića. Tip baterije: 18V PXC Li-Ion; broj okretaja: 8500 rpm; širina reza: 24 cm; neto težina: 1,75 kg. NAPOMENA: Baterija i punjač nisu uključeni u isporuku.',
  69.95, 10, 'akumulatorski', 'nit', 24.00, NULL, 1.75,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/37047/321221_1_1.jpg.jpg.webp?t=1779344528',
  0
),
(
  2,
  'Akumulatorski trimer Einhell GC-CT 18/24 Li P-solo',
  'Einhell',
  'Kompaktni akumulatorski trimer s PXC baterijom 18V, 8500 rpm i 20 plastičnih noževa (bez baterije i punjača).',
  'Einhell GC-CT 18/24 Li P-solo je izvrstan alat za održavanje urednosti travnjaka. Posebno je koristan kod košnje uz rubove ograde ili na drugim mjestima gdje je pristup kosilici otežan. Baterija: 18 V PXC; broj okretaja nož: 8500 o/min; širina rezanja s nožem: 24 cm; broj baterija: 1 kom; težina: 1,57 kg; uključeno 20 plastičnih noževa. Proizvod se isporučuje bez baterije i punjača.',
  39.95, 8, 'akumulatorski', 'nož', 24.00, NULL, 1.57,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/54392/346705.png.png.webp?t=1779345785',
  0
),
(
  2,
  'Akumulatorski trimer Makita DUR192LZ',
  'Makita',
  'Akumulatorski trimer 18V s D-ručkom, širinom reza 30 cm i 0,46 kW snage (bez baterije i punjača).',
  'Makita DUR192LZ akumulatorski trimer idealan je za održavanje travnjaka, rubova i teško dostupnih mjesta. Napon akumulatora: 18 V; vrsta akumulatora: Li-ion; oblik ručke: D-tip; broj okretaja (slobodni hod): 0-4.500 / 6.000 min⁻¹; širina rezanja: 30 cm; snaga: 0,46 kW; masa: 3 kg. Baterija i punjač nisu uključeni u isporuku.',
  173.99, 6, 'akumulatorski', 'nit', 30.00, NULL, 3.00,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/95134/400750.jpg.webp?t=1743764971',
  1
),
(
  2,
  'Akumulatorski trimer Einhell Agillo 36/255 BL solo',
  'Einhell',
  'Profesionalni akumulatorski trimer s Brushless motorom 2x18V, 6300 rpm i mogućnošću montiranja noža (bez baterije).',
  'Einhell Agillo 36/255 BL akumulatorski trimer pokreće snažan motor bez četkica koji je ekonomičniji i dugotrajniji od motora s četkicama. Tip baterije: 2 x 18 V PXC; broj okretaja: 6300 rpm; širina reza: 25,5 cm; neto težina: 6,09 kg; mogućnost montiranja noža ili glave s niti. Isporuka bez baterije i punjača.',
  208.00, 5, 'akumulatorski', 'nit/nož', 25.50, NULL, 6.09,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/55119/347624.jpg.jpg.webp?t=1779344945',
  1
),
(
  2,
  'Akumulatorski trimer Bosch EasyGrassCut 18-230',
  'Bosch',
  'Kompaktni akumulatorski trimer 18V s poluautomatskim kalemom i promjerom košnje 23 cm za rubove travnjaka.',
  'Bosch EasyGrassCut 18-230 akumulatorski trimer idealan je za precizno šišanje rubova travnjaka. Kompaktna veličina i ravnomjerna raspodjela težine omogućuju udobno rukovanje. Tip akumulatora: Li-Ion 2 Ah 18 V; promjer kruga košenja: 23 cm; sustav rezanja: poluautomatski; debljina niti: 1,6 mm; duljina niti: 4 m; težina: 2,2 kg.',
  125.00, 7, 'akumulatorski', 'nit', 23.00, NULL, 2.20,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/62161/358490.png.webp?t=1713794023',
  0
),
(
  2,
  'Motorni trimer Smartool PRO PNBC520-3',
  'Smartool',
  'Motorni 2-taktni trimer s radnom širinom niti 42 cm, nožem 25,5 cm i spremnikom goriva 1,2 L.',
  'Smartool PRO PNBC520-3 motorni trimer opremljen je snažnim 2-taktnim motorom koji pruža pouzdanu snagu za košenje trave i korova. Motor: 2-taktni, zračno hlađenje; volumen: 51,7 cm³; snaga: 1,45 kW; dvostruka ručka; dvostruki remen za nošenje; trokraki nož i glava s niti; radna širina (nit): 42 cm; promjer niti: 2,5 mm, duljina: 3 m; radna širina (nož): 25,5 cm; spremnik goriva: 1,2 L.',
  149.99, 6, 'benzinski', 'nit/nož', 42.00, NULL, 8.40,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/51219/341773.jpg.webp?t=1704467406',
  0
),
(
  2,
  'Motorni trimer AL-KO BC 500 B',
  'AL-KO',
  'Snažni motorni trimer s 2-taktnim motorom 50,8 ccm, snagom 1,9 kW i radnom širinom niti 41 cm.',
  'Lak i praktičan motorni trimer AL-KO BC 500 B za laganu do srednju košnju trave i korova. Jednostavno rukovanje zahvaljujući ugodnom mekanom hvatu, maloj težini i bike ručki. Ravna cijev s robusnim kutnim prijenosom i podesivom ručkom. Motor: 2-taktni 50,8 ccm; snaga: 1,9 kW; radna širina niti: 41 cm; radna širina noža: 25 cm; volumen spremnika: 0,75 L; težina ca. 7,50 kg. Uključuje pojas za nošenje, reznu glavu i trokraki nož.',
  199.00, 5, 'benzinski', 'nit/nož', 41.00, NULL, 7.50,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/43422/330640-3.jpg.webp?t=1693454949',
  0
),
(
  2,
  'Motorni trimer Prorun TBC 952 D',
  'Prorun',
  'Snažni motorni trimer s radnom širinom 43 cm, snagom 1500 W i spremnikom goriva 0,85 L.',
  'Motorni trimer Prorun TBC 952 D idealan je za urednost vrtnih površina, rubova i teže dostupnih mjesta. Kapacitet spremnika: 0,85 L; radna širina: 43 cm; snaga: 1500 W. Prorun je specijaliziran proizvođač vanjske motorne opreme za benzinske i baterijske trimere i kosilice. Jamstvo: 24 mj.',
  179.99, 4, 'benzinski', 'nit/nož', 43.00, NULL, 8.23,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/88867/393573.jpg.webp?t=1737725276',
  0
),
(
  2,
  'Motorni trimer Smartool TMM415-4 4U1',
  'Smartool',
  'Višefunkcionalni motorni trimer 4u1 s prilagodbom za razne načine rada i radnom širinom 41,5 cm.',
  'Motorni trimer Smartool TMM415-4 4U1 nudi iznimnu svestranost zahvaljujući 4-u-1 funkcionalnosti koja se prilagođava različitim potrebama u vrtu. Idealan za košnju trave, korova i uređivanje rubova travnjaka u različitim uvjetima. Radna širina: 41,5 cm. Jamstvo: 24 mj.',
  209.90, 5, 'benzinski', 'nit/nož', 41.50, NULL, 13.00,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/88473/392667.jpg.webp?t=1737704222',
  0
)
ON DUPLICATE KEY UPDATE
  price        = VALUES(price),
  stock        = VALUES(stock),
  description  = VALUES(description),
  image_url    = VALUES(image_url),
  featured     = VALUES(featured);

-- =============================================
-- SJEME TRAVE  (category_id = 3)
-- =============================================
INSERT INTO products
  (category_id, name, brand, short_description, description, price, stock,
   power_type, blade_type, cutting_width_cm, basket_capacity_l, weight_kg, image_url, featured)
VALUES
(
  3,
  'Sjeme trave Johnson DLF travna smjesa univerzalna 1 kg',
  'Johnson',
  'Univerzalna travna smjesa za sve tipove tla i namjene, od ukrasnih vrtova do zelenih površina. 1 kg.',
  'Johnson DLF univerzalna travna smjesa idealna je za sve tipove tla i namjene, od ukrasnih vrtova do funkcionalnih zelenih površina. Prikladna za sunčana i djelomično sjenčana mjesta. Tehničke karakteristike: težina 1,00 kg; robna marka Johnson; visina artikla 29 cm; širina artikla 7 cm; duljina artikla 19 cm. Preporučena sjetva: rano proljeće ili jesen. Pokrivenost: do 35 m² po kg.',
  6.99, 50, NULL, NULL, NULL, NULL, 1.00,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/88346/392455.jpg.webp?t=1724138046',
  0
),
(
  3,
  'Sjeme trave Johnson DLF travna smjesa brzo rastuća 1 kg',
  'Johnson',
  'Brzo rastuća travna smjesa za brzo ozelenjavanje i popravak oštećenih površina travnjaka. 1 kg.',
  'Johnson DLF brzo rastuća travna smjesa osmišljena je za brzo ozelenjavanje novih površina i popravak oštećenih dijelova travnjaka. Klija u kratkom roku i pruža gustu, otpornu travnu podlogu. Prikladna za sunčane i djelomično sjenovite površine. Težina: 1 kg. Preporučena sjetva: rano proljeće ili jesen.',
  6.99, 40, NULL, NULL, NULL, NULL, 1.00,
  'https://cdn.pevex.hr/Documents/image_style/product_list_item/Documents/Products/88347/392456.jpg.webp?t=1724138273',
  0
),
(
  3,
  'Sjeme trave Johnson DLF travna smjesa sport 1 kg',
  'Johnson',
  'Sportska travna smjesa otporna na habanje, finog izgleda, idealna za sportske terene i igrališta. 1 kg.',
  'Johnsons sportska trava je otporna na habanje i finog izgleda. Idealna za sportske terene i dječja igrališta. Brzo se uspostavlja i tolerantna je na sjenu i sušu. Tehničke karakteristike: težina 1 kg; robna marka Johnson DLF. Preporučena sjetva: proljeće ili kasno ljeto / jesen.',
  6.99, 35, NULL, NULL, NULL, NULL, 1.00,
  'https://cdn.pevex.hr/Documents/image_style/product_list_item/Documents/Products/88348/392457_4.jpg.webp?t=1724137878',
  0
),
(
  3,
  'Sjeme trave GreenGarden univerzalna 5 kg',
  'GreenGarden',
  'Univerzalna travna smjesa za sve površine, pakiranje 5 kg za veće travnjake.',
  'GreenGarden univerzalna travna smjesa osmišljena je za sve vrste travnjaka i površina. Idealna za nove travnjake i obnovu postojećih. Prikladna za sunčana i poluzasjenjena mjesta. Pakiranje od 5 kg pokriva veće površine. Prikladna za dosijavanje i osnivanje novog travnjaka.',
  29.99, 20, NULL, NULL, NULL, NULL, 5.00,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/103672/smjesa_trave_5_kg.jpg.webp.webp?t=1779086056',
  1
),
(
  3,
  'Sjeme trave Robustika univerzalna 1 kg',
  'Robustika',
  'Univerzalna travna mješavina za sva područja sa sunčanim i sjenovitim dijelovima. Sjetva rano proljeće i jesen.',
  'Robustika je univerzalna travna mješavina za sva područja sa sunčanim i sjenovitim dijelovima. Prilagođena je različitim klimatskim uvjetima i vrstama tla. Preporučeno je sijati na temperaturi tla iznad 8°C. Optimalno je sijati u rano proljeće i jesen. Daje gustu i otpornu travnu podlogu prikladnu za rekreativne i ukrasne travnjake. Težina: 1 kg.',
  0.00, 30, NULL, NULL, NULL, NULL, 1.00,
  'https://cdn.pevex.hr/Documents/image_style/product_image/Documents/Products/17241/251229.jpg.jpg.webp?t=1774349703',
  0
)
ON DUPLICATE KEY UPDATE
  price        = VALUES(price),
  stock        = VALUES(stock),
  description  = VALUES(description),
  image_url    = VALUES(image_url),
  featured     = VALUES(featured);