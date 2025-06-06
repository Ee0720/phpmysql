-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-04-21 10:04:30
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.0.28

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `school`
--

-- --------------------------------------------------------

--
-- 資料表結構 `book`
--

CREATE TABLE `book` (
  `id` int(11) NOT NULL,
  `bookname` varchar(32) NOT NULL COMMENT '書名',
  `author` varchar(32) NOT NULL COMMENT '作者',
  `publisher` varchar(32) NOT NULL COMMENT '出版社',
  `pubdate` date NOT NULL COMMENT '出版日期',
  `price` int(11) NOT NULL COMMENT '定價',
  `content` text NOT NULL COMMENT '內容說明'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `book`
--

INSERT INTO `book` (`id`, `bookname`, `author`, `publisher`, `pubdate`, `price`, `content`) VALUES
(2, '數據分析入門', '李小華', '數據出版社', '2021-11-20', 499, '本書詳細介紹了數據分析的基本方法及工具使用。'),
(3, '網絡安全基礎', '王偉', '科技出版公司', '2023-01-15', 350, '介紹了網絡安全的基本概念以及實踐技巧。'),
(4, '算法與數據結構', '周珊', '計算機出版社', '2020-08-30', 420, '本書深入介紹了各種算法和數據結構，並結合實例進行分析。'),
(5, '人工智能概論', '劉宇', '未來出版社', '2022-09-05', 550, '這本書是人工智能領域的入門書籍，內容包含AI的基本概念與發展。'),
(6, '現代管理學', '張彥', '商業出版集團', '2019-06-12', 380, '本書介紹了現代企業管理中的主要理論和實踐。'),
(7, '經濟學原理', '高峰', '經濟出版社', '2020-02-25', 450, '這本書闡述了經濟學的基本理論及其應用。'),
(8, '數位設計基礎', '黃亮', '設計出版公司', '2021-04-14', 399, '本書介紹了數位設計的基本原則和技術，適合入門學習。'),
(9, '人類歷史簡史', '尤瓦爾·赫拉利', '文化出版社', '2018-08-15', 520, '這本書概述了人類從古至今的發展歷程。'),
(10, '程式設計入門', '陳靜', '程序出版社', '2023-03-10', 360, '這本書介紹了初學者如何入門程式設計，涵蓋了常見編程語言。'),
(11, '網頁開發實戰', '李建華', '技術出版公司', '2021-12-01', 450, '介紹了如何開發互動型網站，包含HTML、CSS及JavaScript的應用。'),
(12, 'UI/UX設計基礎', '王婷', '設計出版社', '2020-10-12', 480, '本書講解了UI和UX設計的基本理念與實踐技巧。'),
(13, '區塊鏈技術與應用', '林志偉', '科技出版集團', '2022-06-18', 560, '介紹了區塊鏈技術的基本概念及其在金融等領域的應用。'),
(14, '時間管理', '李曉明', '商業出版集團', '2021-07-22', 350, '本書為讀者提供了高效管理時間的方法，適合現代繁忙的工作人群。'),
(15, '微觀經濟學', '張涵', '經濟出版社', '2019-05-10', 400, '深入講解了微觀經濟學的核心理論與市場運作方式。'),
(16, '數位行銷', '宋宇', '行銷出版公司', '2021-01-01', 500, '介紹了數位行銷的基本策略，幫助企業在網絡世界中取得成功。'),
(17, '心理學基礎', '劉婷', '心理學出版社', '2022-02-28', 420, '本書介紹了心理學的基本理論與應用，適合學術及實踐者閱讀。'),
(18, '自我提升與成長', '陳文靜', '自我提升出版社', '2023-01-30', 350, '本書分享了關於如何提升自我與實現人生目標的策略。'),
(19, '行為經濟學', '卡斯·桑斯坦', '經濟出版社', '2021-08-25', 460, '本書介紹了行為經濟學的基本理論，並探討了人類決策過程中的非理性行為。'),
(20, '創新與創業', '劉志明', '創業出版社', '2020-11-15', 480, '本書分享了創業過程中的創新思維與策略，適合創業者閱讀。');

-- --------------------------------------------------------

--
-- 資料表結構 `movie`
--

CREATE TABLE `movie` (
  `id` int(11) NOT NULL COMMENT '主鍵',
  `title` varchar(64) NOT NULL COMMENT '電影名稱',
  `year` int(4) NOT NULL COMMENT '發行年份',
  `director` varchar(64) NOT NULL COMMENT '導演',
  `mtype` varchar(16) NOT NULL COMMENT '類型',
  `mdate` date NOT NULL COMMENT '首映日期',
  `content` text NOT NULL COMMENT '內容簡介'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `movie`
--

INSERT INTO `movie` (`id`, `title`, `year`, `director`, `mtype`, `mdate`, `content`) VALUES
(1, '動作巨星的崛起', 2023, '李安', '動作', '2023-08-15', '一位默默無聞的武術家意外捲入一場國際陰謀。'),
(2, '星際探險：新紀元', 2024, '克里斯多福·諾蘭', '科幻', '2024-05-20', '一群太空探險家前往遙遠的星系尋找新的家園。'),
(3, '浪漫邂逅在巴黎', 2022, '奧黛麗·杜邦', '愛情', '2022-11-01', '兩個陌生人在浪漫的巴黎街頭展開了一段意想不到的戀情。'),
(4, '古宅謎影', 2025, '大衛·芬奇', '懸疑', '2025-03-10', '一對年輕夫婦搬進一棟古老的宅邸，卻發現其中隱藏著令人不安的秘密。'),
(5, '奇幻森林的秘密', 2021, '吉卜力工作室', '動畫', '2021-07-28', '一個小女孩偶然進入了一個充滿奇幻生物的神秘森林。'),
(6, '末日浩劫', 2026, '史蒂芬·史匹柏', '災難', '2026-09-05', '一顆巨大的隕石即將撞擊地球，人類面臨前所未有的危機。'),
(7, '喜劇之王', 2020, '魏德聖', '喜劇', '2020-12-25', '一個懷抱夢想的年輕人努力在競爭激烈的喜劇界闖出一片天。'),
(8, '歷史的印記', 2023, '張藝謀', '劇情', '2023-04-18', '一段發生在動盪年代的感人故事，見證了人性的光輝。'),
(9, '犯罪現場調查', 2024, '昆汀·塔倫提諾', '犯罪', '2024-01-12', '一名資深警探追查一樁手法離奇的連環殺人案。'),
(10, '音樂的靈魂', 2022, '李安', '音樂', '2022-06-30', '一位才華洋溢的音樂家在追尋夢想的過程中經歷了種種挑戰。'),
(11, '深海潛行', 2025, '詹姆斯·卡麥隆', '冒險', '2025-11-22', '一組科學家深入未知的海洋深處，探索神秘的生物和遺跡。'),
(12, '恐怖娃娃屋', 2021, '溫子仁', '恐怖', '2021-10-31', '一群年輕人意外闖入一間廢棄的娃娃屋，卻遭遇了無法解釋的恐怖事件。'),
(13, '青春的色彩', 2026, '是枝裕和', '青春', '2026-02-14', '描繪一群高中生在成長過程中經歷的友誼、愛情和迷惘。'),
(14, '戰爭前線', 2020, '克林·伊斯威特', '戰爭', '2020-07-04', '講述一名士兵在殘酷的戰爭中掙扎求生的故事。'),
(15, '武俠傳奇', 2023, '徐克', '武俠', '2023-09-28', '一位身懷絕技的俠客行走江湖，行俠仗義。'),
(16, '超級英雄聯盟', 2024, '漫威影業', '超級英雄', '2024-08-02', '一群擁有不同超能力的英雄聯手對抗邪惡勢力。'),
(17, '紀錄片：地球之美', 2022, '國家地理', '紀錄片', '2022-04-05', '以壯麗的影像呈現地球上多樣的自然景觀和生態系統。'),
(18, '黑色幽默夜', 2025, '蓋·瑞奇', '黑色幽默', '2025-06-19', '一系列充滿黑色諷刺和荒誕情節的故事。'),
(19, '家庭的羈絆', 2021, '雷利·史考特', '家庭', '2021-03-15', '探討一個家庭成員之間複雜的情感和關係。'),
(20, '動畫短片集', 2026, '皮克斯', '短片', '2026-12-18', '一系列充滿創意和想像力的動畫短片。'),
(21, '動作追擊', 2023, '羅伯特·羅德里格茲', '動作', '2023-11-08', '一場驚險刺激的追逐，主角必須在時間耗盡前完成任務。'),
(22, '星際迷航：重啟', 2024, 'J·J·亞伯拉罕', '科幻', '2024-07-10', '經典科幻系列的全新演繹，探索未知的宇宙。'),
(23, '愛在日落之前', 2022, '理查德·林克萊特', '愛情', '2022-09-23', '一對多年後重逢的戀人，再次面對彼此的感情。'),
(24, '心理遊戲', 2025, '克里斯多福·諾蘭', '懸疑', '2025-01-27', '一個充滿謎團和轉折的故事，挑戰觀眾的認知。'),
(25, '龍貓', 1988, '吉卜力工作室', '動畫', '2021-05-04', '（經典重映）兩個小女孩與神奇的森林精靈龍貓的溫馨故事。'),
(26, '氣象戰', 2017, '羅蘭·艾默里奇', '災難', '2026-10-15', '（重新排檔）人類試圖控制天氣，卻引發了全球性的災難。'),
(27, '功夫', 2004, '周星馳', '喜劇', '2020-03-06', '（經典重映）一個小混混在臥虎藏龍的貧民窟中成長為一代宗師。'),
(28, '霸王別姬', 1993, '陳凱歌', '劇情', '2023-06-21', '（經典重映）兩位京劇演員半個世紀的愛恨情仇。'),
(29, '沉默的羔羊', 1991, '喬納森·戴米', '犯罪', '2024-04-26', '（經典重映）一位年輕的FBI實習生與一位高智商的連環殺手之間的心理博弈。'),
(30, '海上鋼琴師', 1998, '朱塞佩·托納多雷', '音樂', '2022-08-19', '（經典重映）一個在遠洋客輪上出生長大的天才鋼琴師的傳奇一生。');

-- --------------------------------------------------------

--
-- 資料表結構 `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `pname` varchar(64) NOT NULL COMMENT '產品名稱',
  `pspec` varchar(64) NOT NULL COMMENT '產品規格',
  `price` int(11) NOT NULL COMMENT '定價',
  `pdate` date NOT NULL COMMENT '製造日期',
  `content` text NOT NULL COMMENT '內容說明'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `product`
--

INSERT INTO `product` (`id`, `pname`, `pspec`, `price`, `pdate`, `content`) VALUES
(1, '無線滑鼠', '黑色, USB連接', 499, '2024-01-15', '輕巧設計，適合日常使用'),
(2, '機械鍵盤', '青軸, USB-C', 1899, '2024-01-20', '具備背光與高耐用性'),
(3, '27吋顯示器', 'Full HD, HDMI', 3990, '2023-12-10', '支援護眼模式與低藍光'),
(4, 'USB-C 轉 HDMI 轉接器', '鋁合金材質', 299, '2024-02-01', '支援4K輸出'),
(5, '筆記型電腦支架', '鋁合金, 折疊式', 699, '2023-11-30', '多角度可調整，方便攜帶'),
(6, '藍牙喇叭', 'IPX5防水', 899, '2023-10-18', '支援藍牙5.0，高音質輸出'),
(7, '外接硬碟', '1TB, USB 3.0', 2190, '2023-09-22', '適合資料備份與攜帶'),
(8, '無線充電盤', '支援Qi標準', 450, '2024-03-05', '快速充電，兼容多款手機'),
(9, '手機支架', '桌上型, 黑色', 199, '2023-12-01', '適合觀看影片與直播'),
(10, '電競耳機', '7.1聲道, 有線', 1590, '2024-01-08', '適合遊戲與線上會議'),
(11, '筆記型電腦', 'i5, 16GB, 512GB SSD', 28900, '2024-03-15', '高效能辦公用機'),
(12, '手機三腳架', '可伸縮, 藍色', 299, '2023-12-18', '適用於拍照與錄影'),
(13, '網路攝影機', '1080p, USB', 750, '2024-02-22', '適合遠距會議'),
(14, 'LED 燈條', 'RGB, USB供電', 380, '2023-09-15', '可調光，增添氛圍'),
(15, '掃描器', 'A4尺寸, 1200dpi', 3290, '2023-11-12', '適合文件與照片掃描'),
(16, '辦公椅', '可調高度, 黑色', 2490, '2023-10-05', '舒適坐墊，支撐腰背'),
(18, '耳塞式耳機', '有線, 白色', 350, '2024-03-01', '輕巧便攜，音質清晰'),
(19, '滑鼠墊', '大型, 防滑', 220, '2023-07-10', '適合電競與設計工作'),
(20, '電子書閱讀器', '6吋, 內建燈光', 3990, '2024-04-01', '適合長時間閱讀'),
(21, '智慧手環', 'OLED螢幕, 防水', 1290, '2024-02-10', '具備心率偵測、睡眠分析與步數統計功能。\n適合日常運動與健康追蹤使用。'),
(22, '雷射印表機', '黑白, Wi-Fi', 4990, '2023-10-20', '列印速度快且解析度高，支援無線連線。\n搭配手機APP可進行遠端列印，非常方便。'),
(23, '4K 電視盒', '支援Netflix/Youtube', 1990, '2024-01-12', '輕巧設計，支援多種串流平台。\n適合家中娛樂中心使用，也可透過USB播放本地影片。'),
(24, '行動電源', '20000mAh, 雙輸出', 790, '2023-11-05', '容量大，可同時為兩台設備充電。\n支援快充與LED電量顯示，非常實用。'),
(25, '智慧語音助理喇叭', 'Wi-Fi連線, 雙麥克風', 1790, '2023-12-25', '支援多種語音指令操作，可控制智慧家電。\n同時也是一個高品質的藍牙喇叭。'),
(26, '電容式觸控筆', '支援iOS/Android', 380, '2024-03-08', '適合用於繪圖、筆記與簽名。\n筆尖精細、手感舒適，是數位藝術愛好者的好夥伴。');

-- --------------------------------------------------------

--
-- 資料表結構 `student`
--

CREATE TABLE `student` (
  `id` int(11) NOT NULL,
  `schid` varchar(12) NOT NULL COMMENT '學號',
  `name` varchar(32) NOT NULL COMMENT '姓名',
  `gender` varchar(1) NOT NULL COMMENT '性別',
  `birthday` date NOT NULL,
  `email` varchar(64) NOT NULL,
  `address` varchar(64) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `student`
--

INSERT INTO `student` (`id`, `schid`, `name`, `gender`, `birthday`, `email`, `address`) VALUES
(1, 'S001', '陳志明', 'M', '2000-01-15', 'chenzhiming@example.com', '台北市大安區忠孝東路四段200號5樓'),
(2, 'S002', '李雅婷', 'F', '2001-05-20', 'liyating@example.com', '新北市板橋區中山路一段100號3樓'),
(3, 'S003', '王國強', 'M', '2002-09-10', 'wangguoqiang@example.com', '台中市南區建國路二段150號2樓'),
(4, 'S004', '趙婷婷', 'F', '1999-03-25', 'zhaotingting@example.com', '高雄市前金區中華路120號4樓'),
(5, 'S005', '錢志華', 'M', '2000-11-30', 'qianzhihua@example.com', '台南市中西區建國路二段55號6樓'),
(6, 'S006', '孫偉杰', 'M', '2001-07-22', 'sunweijie@example.com', '新竹市東區光復路二段345號7樓'),
(7, 'S007', '周美蓮', 'F', '1998-12-05', 'zhoumeilian@example.com', '基隆市仁愛區愛二路123號8樓'),
(8, 'S008', '吳俊傑', 'M', '2002-04-18', 'wujunjie@example.com', '台中市北區育才路45號2樓'),
(9, 'S009', '鄭佳怡', 'F', '2000-10-13', 'zhengjiayi@example.com', '彰化縣花壇鄉中山路500號10樓'),
(10, 'S010', '冯子豪', 'M', '2001-02-01', 'fengzihao@example.com', '新竹市北區光明路12號4樓');

-- --------------------------------------------------------

--
-- 資料表結構 `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `userpass` varchar(255) NOT NULL,
  `userlevel` int(1) NOT NULL DEFAULT 0,
  `created_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `users`
--

INSERT INTO `users` (`id`, `username`, `userpass`, `userlevel`, `created_at`) VALUES
(1, 'devin', '$2y$10$Zpe/PW.i.ocpWe/puK.WS.Qc0PUcOxz/it.vLw8XKJTrPphB8Yil.', 0, '2025-04-21 14:42:16');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `book`
--
ALTER TABLE `book`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `movie`
--
ALTER TABLE `movie`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `student`
--
ALTER TABLE `student`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `schid` (`schid`);

--
-- 資料表索引 `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `book`
--
ALTER TABLE `book`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `movie`
--
ALTER TABLE `movie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵', AUTO_INCREMENT=32;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=32;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `student`
--
ALTER TABLE `student`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
