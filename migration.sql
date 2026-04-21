CREATE DATABASE quiz;
USE quiz;

CREATE TABLE topics (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE questions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    topic_id INT,
    text TEXT NOT NULL,
    FOREIGN KEY (topic_id) REFERENCES topics(id) ON DELETE CASCADE
);

CREATE TABLE answers (
    id INT AUTO_INCREMENT PRIMARY KEY,
    question_id INT,
    text VARCHAR(255) NOT NULL,
    is_correct BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (question_id) REFERENCES questions(id) ON DELETE CASCADE
);

INSERT INTO topics (name) VALUES 
	('Ģeogrāfija'),
	('Kino'),
	('Video spēles'),
	('Latvija'),
	('Sports');
	
INSERT INTO questions (id, topic_id, text) VALUES 
	(1, 1, 'Kurš ir pasaulē mazākais kontinents pēc platības?'),
	(2, 1, 'Kura valsts atrodas uzreiz divos kontinentos – Āzijā un Eiropā?'),
	(3, 1, 'Kurš okeāns atrodas starp Ameriku un Eiropu/Āfriku?'),
	(4, 1, 'Kā sauc pasaulē augstāko kalnu virsotni?'),
	(5, 1, 'Kurā kontinentā atrodas pasaulē garākā kalnu grēda – Andi?'),
	(6, 1, 'Kurš ir pasaulē lielākais ezers (pēc platības), ko bieži dēvē par jūru?'),
	(7, 1, 'Kurā valstī atrodas slavenais Lielais Barjerrifs?'),
	(8, 1, 'Kura ir pasaulē lielākā valsts pēc platības?'),
	(9, 1, 'Kura pilsēta ir pazīstama kā "Mūžīgā pilsēta"?'),
	(10, 1, 'Kura valsts tiek dēvēta par "Uzlecošās saules zemi"?'),
	(11, 1, 'Kuras valsts sastāvā ir visvairāk salu pasaulē (vairāk nekā 17 000)?'),
	(12, 1, 'Kurā kontinentā nav neviena tuksneša?'),
	(13, 1, 'Kā sauc iedomāto līniju, kas dala Zemi Ziemeļu un Dienvidu puslodēs?'),
	(14, 1, 'Kurā valstī atrodas pasaulē augstākais ūdenskritums – Anhela ūdenskritums?'),
	(15, 1, 'Kura ir visaukstākā apdzīvotā vieta pasaulē (kur fiksētas zemākās temperatūras)?');
	
	
INSERT INTO answers (question_id, text, is_correct) VALUES 
(1, 'Eiropa', 0), (1, 'Antarktīda', 0), (1, 'Austrālija', 1), (1, 'Dienvidamerika', 0),
(2, 'Ķīna', 0), (2, 'Irāna', 0), (2, 'Krievija', 1), (2, 'Indija', 0),
(3, 'Atlantijas okeāns', 1), (3, 'Indijas okeāns', 0), (3, 'Klusais okeāns', 0), (3, 'Dienvidu okeāns', 0),
(4, 'K2', 0), (4, 'Monblāns', 0), (4, 'Everests (Džomolungma)', 1), (4, 'Kilimandžāro', 0),
(5, 'Ziemeļamerikā', 0), (5, 'Āzijā', 0), (5, 'Dienvidamerikā', 1), (5, 'Āfrikā', 0),
(6, 'Baikāls', 0), (6, 'Kaspijas jūra', 1), (6, 'Viktorijas ezers', 0), (6, 'Mičigans', 0),
(7, 'Brazīlijā', 0), (7, 'Japānā', 0), (7, 'Austrālijā', 1), (7, 'Taizemē', 0),
(8, 'Kanāda', 0), (8, 'ASV', 0), (8, 'Krievija', 1), (8, 'Ķīna', 0),
(9, 'Atēnas', 0), (9, 'Parīze', 0), (9, 'Roma', 1), (9, 'Londona', 0),
(10, 'Dienvidkoreja', 0), (10, 'Vjetnama', 0), (10, 'Japāna', 1), (10, 'Jaunzēlande', 0),
(11, 'Filipīnas', 0), (11, 'Indonēzija', 1), (11, 'Grieķija', 0), (11, 'Maldivu salas', 0),
(12, 'Eiropā', 1), (12, 'Antarktīdā', 0), (12, 'Dienvidamerikā', 0), (12, 'Austrālijā', 0),
(13, 'Griničas meridiāns', 0), (13, 'Ziemeļu polārais loks', 0), (13, 'Ekvators', 1), (13, 'Tropu loks', 0),
(14, 'Kanādā', 0), (14, 'Venecuēlā', 1), (14, 'Zambijā', 0), (14, 'Islandē', 0),
(15, 'Aļaska, ASV', 0), (15, 'Lapzeme, Somija', 0), (15, 'Sibīrija, Krievija', 1), (15, 'Nuuka, Grenlande', 0);

INSERT INTO questions (id, topic_id, text) VALUES 
(16, 2, 'Kā sauc galveno varoni – mazo lauvu – populārajā animācijas filmā "Karalis Lauva"?'),
(17, 2, 'Kādas krāsas ogre ir Šreks?'),
(18, 2, 'Kurā filmā galvenā varone Elza dzied slaveno dziesmu "Lai snieg" (Let It Go)?'),
(19, 2, 'Kā sauc mazo, dzelteno radību komandu, kas kalpo Gru filmā "Nejaukais es"?'),
(20, 2, 'Kurš supervaronis lido ar sarkanu un zeltītu metāla tērpu?'),
(21, 2, 'Kas ist slavenais slepenais aģents 007?'),
(22, 2, 'Kurš aktieris tēloja pirātu kapteini Džeku Sperovu filmā "Karību jūras pirāti"?'),
(23, 2, 'Kurā filmu sērijā izmanto "gaismas zobenu" (Lightsaber) un cīnās ar tumšajiem spēkiem?'),
(24, 2, 'Uz kuru skolu dodas bērni, lai mācītos burvestības Harija Potera filmās?'),
(25, 2, 'Kurā filmā milzīgs kuģis saduras ar aisbergu un nogrimst okeānā?'),
(26, 2, 'Kuru no šiem prestižajiem apbalvojumiem pasniedz labākajiem kino industrijā?'),
(27, 2, 'Kurš populārs aktieris ir pazīstams kā "Klinšu kalns" (The Rock)?'),
(28, 2, 'Kā sauc filmu sēriju, kurā galvenā tēma ir ielu autosacīkstes un ģimene?'),
(29, 2, 'Kurā pilsētā atrodas slavenais "Slavas alējas" (Walk of Fame) rajons Holivudā?'),
(30, 2, 'Kā sauc zilo tēlu rasi, kas dzīvo uz Pandoras planētas filmā "Avatars"?');

INSERT INTO answers (question_id, text, is_correct) VALUES 
(16, 'Mufasa', 0), (16, 'Simba', 1), (16, 'Skars', 0), (16, 'Pumba', 0),
(17, 'Zilas', 0), (17, 'Sarkanas', 0), (17, 'Zaļas', 1), (17, 'Dzeltenas', 0),
(18, 'Ledus sirds (Frozen)', 1), (18, 'Salātlapiņa', 0), (18, 'Moana', 0), (18, 'Pelnrušķīte', 0),
(19, 'Smurfi', 0), (19, 'Minioni', 1), (19, 'Hobiti', 0), (19, 'Troļļi', 0),
(20, 'Betmens', 0), (20, 'Zirnekļcilvēks', 0), (20, 'Dzelzs vīrs (Iron Man)', 1), (20, 'Kapteinis Amerika', 0),
(21, 'Džeisons Borns', 0), (21, 'Džeimss Bonds', 1), (21, 'Ītans Hants', 0), (21, 'Šerloks Holmss', 0),
(22, 'Breds Pits', 0), (22, 'Džonijs Deps', 1), (22, 'Leonardo Di Kaprio', 0), (22, 'Toms Krūzs', 0),
(23, 'Gredzenu pavēlnieks', 0), (23, 'Zvaigžņu kari (Star Wars)', 1), (23, 'Harijs Poters', 0), (23, 'Juras laikmeta parks', 0),
(24, 'Kembridža', 0), (24, 'Nārnija', 0), (24, 'Cūkkārpa (Hogwarts)', 1), (24, 'Oksforda', 0),
(25, 'Avatars', 0), (25, 'Jūras dzelmē', 0), (25, 'Titāniks', 1), (25, 'Poseidons', 0),
(26, 'Nobela prēmija', 0), (26, 'Oskars', 1), (26, 'Grammy', 0), (26, 'Olimpiskā medaļa', 0),
(27, 'Vinam Dīzelim', 0), (27, 'Dveins Džonsons', 1), (27, 'Arnolds Švarcenegers', 0), (27, 'Silvestrs Stallone', 0),
(28, 'Need for Speed', 0), (28, 'Ātrs un bez žēlastības (Fast & Furious)', 1), (28, 'Transporteris', 0), (28, 'Formula 1', 0),
(29, 'Ņujorkā', 0), (29, 'Lasvegasā', 0), (29, 'Losandželosā', 1), (29, 'Maiami', 0),
(30, 'Elfi', 0), (30, 'Na\'vi', 1), (30, 'Vulkāni', 0), (30, 'Transformers', 0);

INSERT INTO questions (id, topic_id, text) VALUES 
(31, 3, 'Kā sauc slaveno itāļu santehniķi, kurš ir spēļu kompānijas "Nintendo" simbols?'),
(32, 3, 'Kura spēle ir pazīstama ar dzeltenu, apaļu tēlu, kuram jāēd punktiņi un jābēg no spokiem?'),
(33, 3, 'Kurā spēlē spēlētājam jākārto krītošas dažādu formu figūras, lai aizpildītu līnijas?'),
(34, 3, 'Kā sauc galveno varoni spēļu sērijā "Tomb Raider"?'),
(35, 3, 'Kurā spēlē spēlētāji izdzīvo un būvē visu no kvadrātainiem blokiem?'),
(36, 3, 'Kā sauc populāro "Battle Royale" spēli, kurā var ne tikai šaut, bet arī ātri būvēt sienas un torņus?'),
(37, 3, 'Kurā spēļu sērijā tu vari zagt automašīnas un brīvi pārvietoties pa pilsētu?'),
(38, 3, 'Kā sauc spēli, kurā "iebrucēji" cenšas sabojāt kosmosa kuģi (Impostor)?'),
(39, 3, 'Kura mobilā spēle lika cilvēkiem staigāt pa ielām un "ķert" virtuālus radījumus?'),
(40, 3, 'Ko spēlētāji dara spēlē "Angry Birds"?'),
(41, 3, 'Kā sauc spēli, kurā spēlētāji veido virtuālas ģimenes un kontrolē viņu ikdienu?'),
(42, 3, 'Kā sauc populāro spēļu konsoli, ko ražo uzņēmums "Sony"?'),
(43, 3, 'Kā sauc "Nintendo" konsoli, ko var izmantot gan kā pārnēsājamu, gan pie TV?'),
(44, 3, 'Kāds ir galvenais uzdevums spēlē "Counter-Strike"?'),
(45, 3, 'Kurš no šiem ir pasaulē zināmākais dzeltenais elektriskais tēls no "Pokémon"?');

INSERT INTO answers (question_id, text, is_correct) VALUES 
(31, 'Luidži', 0), (31, 'Vario', 0), (31, 'Mario', 1), (31, 'Jūši', 0),
(32, 'Tetris', 0), (32, 'Sonic', 0), (32, 'Pac-Man', 1), (32, 'Donkey Kong', 0),
(33, 'Tetris', 1), (33, 'Minecraft', 0), (33, 'Candy Crush', 0), (33, 'Sims', 0),
(34, 'Elena Fišere', 0), (34, 'Princese Zelda', 0), (34, 'Lara Krofta', 1), (34, 'Alise', 0),
(35, 'Roblox', 0), (35, 'Minecraft', 1), (35, 'Terraria', 0), (35, 'Fortnite', 0),
(36, 'PUBG', 0), (36, 'Apex Legends', 0), (36, 'Fortnite', 1), (36, 'Call of Duty', 0),
(37, 'Need for Speed', 0), (37, 'Grand Theft Auto (GTA)', 1), (37, 'Mafia', 0), (37, 'Watch Dogs', 0),
(38, 'Among Us', 1), (38, 'Fall Guys', 0), (38, 'Roblox', 0), (38, 'Portal', 0),
(39, 'Angry Birds', 0), (39, 'Pokémon GO', 1), (39, 'Flappy Bird', 0), (39, 'Clash of Clans', 0),
(40, 'Audzē putnus', 0), (40, 'Sacenšas skriešanā', 0), (40, 'Šauj putnus ar kaķeni pa cūku celtnēm', 1), (40, 'Spēlē futbolu', 0),
(41, 'Animal Crossing', 0), (41, 'SimCity', 0), (41, 'The Sims', 1), (41, 'Stardew Valley', 0),
(42, 'Xbox', 0), (42, 'Switch', 0), (42, 'PlayStation', 1), (42, 'Wii', 0),
(43, 'GameBoy', 0), (43, 'Nintendo Switch', 1), (43, 'DS', 0), (43, 'GameCube', 0),
(44, 'Sacenties ar mašīnām', 0), (44, 'Audzēt dārzeņus', 0), (44, 'Cīņa starp teroristiem un specvienību', 1), (44, 'Meklēt dārgumus', 0),
(45, 'Čarmanders', 0), (45, 'Bulbazaurs', 0), (45, 'Pikačū', 1), (45, 'Squirtle', 0);

INSERT INTO questions (id, topic_id, text) VALUES 
(46, 4, 'Kurā gadā tika proklamēta Latvijas Republika?'),
(47, 4, 'Kas ir Latvijas valsts himnas Dievs, svētī Latviju! autors?'),
(48, 4, 'Kurš putns ir izvēlēts par Latvijas nacionālo putnu?'),
(49, 4, 'Cik zvaigznes ir Brīvības pieminekļa galotnē esošās Mildas rokās?'),
(50, 4, 'Kurš ir Latvijas lielākais ezers pēc virsmas platības?'),
(51, 4, 'Kā sauc platāko ūdenskritumu Eiropā, kas atrodas Kuldīgā?'),
(52, 4, 'Kurš ir Latvijas garākais jūras piekrastes posms?'),
(53, 4, 'Kura ir Latvijas dziļākā upe?'),
(54, 4, 'Cik bieži parasti notiek Vispārējie latviešu Dziesmu un Deju svētki?'),
(55, 4, 'Kurā pilsētā atrodas Latvijas Nacionālā bibliotēka jeb Gaismas pils?'),
(56, 4, 'Kas ir latviešu nacionālais dārgakmens?'),
(57, 4, 'Kurā sporta veidā Latvija 2023. gadā izcīnīja bronzas medaļu Pasaules čempionātā?'),
(58, 4, 'Kā sauc Latvijas lielāko salu (upju/jūras sala)?'),
(59, 4, 'Kurš populārs dzēriens tika radīts Rīgā 18. gadsimtā?'),
(60, 4, 'Kurš Latvijas novads ir pazīstams kā Zilo ezeru zeme?');

INSERT INTO answers (question_id, text, is_correct) VALUES 
(46, '1945. gadā', 0), (46, '1918. gadā', 1), (46, '1991. gadā', 0), (46, '1905. gadā', 0),
(47, 'Krišjānis Barons', 0), (47, 'Rainis', 0), (47, 'Baumaņu Kārlis', 1), (47, 'Emīls Dārziņš', 0),
(48, 'Bezdelīga', 0), (48, 'Stārķis', 0), (48, 'Baltā cielava', 1), (48, 'Gulbis', 0),
(49, 'Viena', 0), (49, 'Divas', 0), (49, 'Trīs', 1), (49, 'Piecas', 0),
(50, 'Rāznas ezers', 0), (50, 'Lubāns', 1), (50, 'Engures ezers', 0), (50, 'Burtnieks', 0),
(51, 'Abavas rumba', 0), (51, 'Glāzšķūņa ūdenskritums', 0), (51, 'Ventas rumba', 1), (51, 'Ivandes ūdenskritums', 0),
(52, 'Jūrmala', 0), (52, 'Saulkrasti', 0), (52, 'Dienvidkurzeme', 1), (52, 'Tolsas novads', 0),
(53, 'Gauja', 0), (53, 'Daugava', 1), (53, 'Venta', 0), (53, 'Lielupe', 0),
(54, 'Katru gadu', 0), (54, 'Reizi trijos gados', 0), (54, 'Reizi piecos gados', 1), (54, 'Reizi desmit gados', 0),
(55, 'Jelgavā', 0), (55, 'Cēsīs', 0), (55, 'Rīgā', 1), (55, 'Ventspilī', 0),
(56, 'Dimants', 0), (56, 'Dzintars', 1), (56, 'Kvarcs', 0), (56, 'Rubīns', 0),
(57, 'Basketbolā', 0), (57, 'Hokejā', 1), (57, 'Futbolā', 0), (57, 'Kamaniņu sportā', 0),
(58, 'Dole', 0), (58, 'Lucavsala', 0), (58, 'Buļļu sala', 1), (58, 'Mākslīgā sala', 0),
(59, 'Kvass', 0), (59, 'Kefīrs', 0), (59, 'Rīgas Melnais balzams', 1), (59, 'Limonāde Veselība', 0),
(60, 'Kurzeme', 0), (60, 'Vidzeme', 0), (60, 'Latgale', 1), (60, 'Zemgale', 0);

INSERT INTO questions (id, topic_id, text) VALUES 
(61, 5, 'Cik spēlētāju no vienas komandas vienlaicīgi atrodas uz laukuma futbola spēlē?'),
(62, 5, 'Kurā valstī tika izgudrots basketbols?'),
(63, 5, 'Kā sauc slaveno argentīniešu futbolistu, kurš 2022. gadā kļuva par pasaules čempionu?'),
(64, 5, 'Kurš ir visu laiku rezultatīvākais latviešu basketbolists NBA pēc vidēji gūtajiem punktiem?'),
(65, 5, 'Kā sauc sporta veidu, kurā ar speciālām slotām berž ledu, lai virzītu akmeni mērķī?'),
(66, 5, 'Kurā pilsētā atrodas vienīgā bobsleja un kamaniņu trase Latvijā?'),
(67, 5, 'Kurā sporta veidā brāļi Martins un Tomass Dukuri ir guvuši pasaules mēroga panākumus?'),
(68, 5, 'Kā sauc prestižāko tenisa turnīru, kurā spēlētāji tradicionāli tērpjas tikai baltā?'),
(69, 5, 'Cik kilometru garš ir klasiskais maratona skrējiens?'),
(70, 5, 'Kurā sporta veidā tiek izmantota rakete un vairīte ar spalvām?'),
(71, 5, 'Cik gredzenu ir attēlots uz Olimpiskā karoga?'),
(72, 5, 'Kurā valstī meklējamas Olimpisko spēļu saknes un pirmssākumi?'),
(73, 5, 'Kas ir ātrākais cilvēks pasaulē (pieder pasaules rekords 100m sprintā)?'),
(74, 5, 'Kādā krāsā ir līdera krekliņš riteņbraukšanas braucienā Tour de France?'),
(75, 5, 'Kurš sporta veids tiek spēlēts Latvijas filmā Sapņu komanda 1935?');

INSERT INTO answers (question_id, text, is_correct) VALUES 
(61, '9', 0), (61, '10', 0), (61, '11', 1), (61, '12', 0),
(62, 'Lietuvā', 0), (62, 'ASV', 1), (62, 'Kanādā', 0), (62, 'Anglijā', 0),
(63, 'Krištianu Ronaldu', 0), (63, 'Lionels Mesi', 1), (63, 'Neimars', 0), (63, 'Kiljans Mbapē', 0),
(64, 'Andris Biedriņš', 0), (64, 'Dāvis Bertāns', 0), (64, 'Kristaps Porziņģis', 1), (64, 'Rodions Kuručs', 0),
(65, 'Bobslejs', 0), (65, 'Kērlings', 1), (65, 'Skelets', 0), (65, 'Hokejs', 0),
(66, 'Cēsīs', 0), (66, 'Valmierā', 0), (66, 'Siguldā', 1), (66, 'Rēzeknē', 0),
(67, 'Biatlonā', 0), (67, 'Skeletonā', 1), (67, 'Kamaniņu sportā', 0), (67, 'Tramplīnlēkšanā', 0),
(68, 'US Open', 0), (68, 'Vimbldona', 1), (68, 'French Open', 0), (68, 'Australian Open', 0),
(69, '10 km', 0), (69, '21 km', 0), (69, '42,195 km', 1), (69, '50 km', 0),
(70, 'Galda tenisā', 0), (70, 'Skvošā', 0), (70, 'Badmintonā', 1), (70, 'Golfā', 0),
(71, 'Trīs', 0), (71, 'Četri', 0), (71, 'Pieci', 1), (71, 'Seši', 0),
(72, 'Itālijā', 0), (72, 'Grieķijā', 1), (72, 'Ēģiptē', 0), (72, 'Francijā', 0),
(73, 'Useins Bolts', 1), (73, 'Karls Lūiss', 0), (73, 'Taisons Gejs', 0), (73, 'Maikls Felpss', 0),
(74, 'Sarkanā', 0), (74, 'Zaļā', 0), (74, 'Dzeltenā', 1), (74, 'Zilā', 0),
(75, 'Futbols', 0), (75, 'Basketbols', 1), (75, 'Hokejs', 0), (75, 'Vieglatlētika', 0);
