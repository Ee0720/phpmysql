-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- 主機： 127.0.0.1
-- 產生時間： 2025-06-09 14:20:00
-- 伺服器版本： 10.4.28-MariaDB
-- PHP 版本： 8.0.28

SET SQL_MODE = "NO_AUTO_AUTO_INCREMENT_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- 資料庫： `music_store`
--

-- --------------------------------------------------------

--
-- 資料表結構 `artists`
--

CREATE TABLE `artists` (
  `id` int(11) NOT NULL,
  `name` varchar(64) NOT NULL COMMENT '藝術家/樂團名稱',
  `genre` varchar(32) NOT NULL COMMENT '音樂類型',
  `country` varchar(32) NOT NULL COMMENT '國家',
  `debut_year` int(4) NOT NULL COMMENT '出道年份',
  `description` text NOT NULL COMMENT '簡介'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `artists`
--

INSERT INTO `artists` (`id`, `name`, `genre`, `country`, `debut_year`, `description`) VALUES
(1, 'Imagine Dragons', 'Pop Rock', 'USA', 2008, '美國搖滾樂團，以其動感的現場表演聞名。'),
(2, 'Adele', 'Soul', 'UK', 2006, '英國創作歌手，以其強大的嗓音和感人的歌曲著稱。'),
(3, 'BTS', 'K-Pop', 'South Korea', 2013, '韓國男子團體，在全球範圍內擁有巨大影響力。'),
(4, 'Ed Sheeran', 'Pop', 'UK', 2004, '英國創作歌手，以其原聲吉他表演和動聽的旋律而聞名。'),
(5, 'Taylor Swift', 'Pop', 'USA', 2006, '美國流行樂天后，以其敘事性歌詞和多樣的音樂風格受到歡迎。'),
(6, 'Coldplay', 'Alternative Rock', 'UK', 1996, '英國搖滾樂團，以其深情的歌曲和壯觀的演唱會而聞名。'),
(7, 'BLACKPINK', 'K-Pop', 'South Korea', 2016, '韓國女子團體，以其時尚風格和強勁的音樂在國際上備受矚目。'),
(8, 'The Weeknd', 'R&B', 'Canada', 2010, '加拿大歌手，以其獨特的聲音和黑暗的R&B風格而聞名。'),
(9, 'Billie Eilish', 'Alternative Pop', 'USA', 2015, '美國新生代歌手，以其獨特的音樂風格和視覺形象受到年輕人喜愛。'),
(10, 'Jay Chou', 'Mandopop', 'Taiwan', 2000, '台灣流行音樂天王，華語樂壇最具影響力的音樂人之一。');

-- --------------------------------------------------------

--
-- 資料表結構 `albums`
--

CREATE TABLE `albums` (
  `id` int(11) NOT NULL COMMENT '主鍵',
  `title` varchar(128) NOT NULL COMMENT '專輯名稱',
  `artist_id` int(11) NOT NULL COMMENT '藝術家ID',
  `release_year` int(4) NOT NULL COMMENT '發行年份',
  `price` int(11) NOT NULL COMMENT '定價',
  `cover_url` varchar(255) NOT NULL COMMENT '專輯封面URL',
  `description` text NOT NULL COMMENT '專輯簡介'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `albums`
--

INSERT INTO `albums` (`id`, `title`, `artist_id`, `release_year`, `price`, `cover_url`, `description`) VALUES
(1, 'Night Visions', 1, 2012, 399, 'https://example.com/night_visions.jpg', 'Imagine Dragons的突破性專輯，收錄了熱門歌曲"Radioactive"。'),
(2, '21', 2, 2011, 450, 'https://example.com/adele_21.jpg', 'Adele的第二張錄音室專輯，銷量驚人並贏得多項格萊美獎。'),
(3, 'Love Yourself: Her', 3, 2017, 350, 'https://example.com/bts_love_yourself_her.jpg', 'BTS的迷你專輯，是"Love Yourself"系列的首部曲。'),
(4, '÷ (Divide)', 4, 2017, 420, 'https://example.com/ed_sheeran_divide.jpg', 'Ed Sheeran的第三張專輯，收錄了"Shape of You"等熱門歌曲。'),
(5, '1989', 5, 2014, 499, 'https://example.com/taylor_swift_1989.jpg', 'Taylor Swift的第五張錄音室專輯，標誌著她從鄉村音樂轉向流行音樂。'),
(6, 'A Head Full of Dreams', 6, 2015, 410, 'https://example.com/coldplay_ahfod.jpg', 'Coldplay的第七張錄音室專輯，充滿積極向上的氛圍。'),
(7, 'THE ALBUM', 7, 2020, 380, 'https://example.com/blackpink_the_album.jpg', 'BLACKPINK的首張錄音室專輯，包含多首與國際藝人合作的曲目。'),
(8, 'After Hours', 8, 2020, 460, 'https://example.com/the_weeknd_after_hours.jpg', 'The Weeknd的第四張錄音室專輯，融合了R&B、新浪潮和合成器流行樂。'),
(9, 'When We All Fall Asleep, Where Do We Go?', 9, 2019, 390, 'https://example.com/billie_eillish_wwafawwg.jpg', 'Billie Eilish的首張錄音室專輯，獲得多項格萊美獎。'),
(10, '范特西', 10, 2001, 320, 'https://example.com/jay_chou_fantasy.jpg', '周杰倫的第二張錄音室專輯，確立了他的音樂風格。');

-- --------------------------------------------------------

--
-- 資料表結構 `tracks`
--

CREATE TABLE `tracks` (
  `id` int(11) NOT NULL,
  `album_id` int(11) NOT NULL COMMENT '所屬專輯ID',
  `title` varchar(128) NOT NULL COMMENT '歌曲名稱',
  `duration` int(11) NOT NULL COMMENT '時長 (秒)',
  `track_number` int(11) NOT NULL COMMENT '歌曲在專輯中的序號',
  `audio_url` varchar(255) NOT NULL COMMENT '音檔URL'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `tracks`
--

INSERT INTO `tracks` (`id`, `album_id`, `title`, `duration`, `track_number`, `audio_url`) VALUES
(1, 1, 'Radioactive', 214, 1, 'https://example.com/radioactive.mp3'),
(2, 1, 'Demons', 177, 2, 'https://example.com/demons.mp3'),
(3, 2, 'Rolling in the Deep', 228, 1, 'https://example.com/rolling_in_the_deep.mp3'),
(4, 2, 'Someone Like You', 283, 2, 'https://example.com/someone_like_you.mp3'),
(5, 3, 'DNA', 234, 1, 'https://example.com/dna.mp3'),
(6, 4, 'Shape of You', 233, 1, 'https://example.com/shape_of_you.mp3'),
(7, 5, 'Blank Space', 231, 1, 'https://example.com/blank_space.mp3'),
(8, 6, 'Hymn for the Weekend', 260, 1, 'https://example.com/hymn_for_the_weekend.mp3'),
(9, 7, 'How You Like That', 198, 1, 'https://example.com/how_you_like_that.mp3'),
(10, 8, 'Blinding Lights', 200, 1, 'https://example.com/blinding_lights.mp3'),
(11, 9, 'bad guy', 194, 1, 'https://example.com/bad_guy.mp3'),
(12, 10, '雙截棍', 199, 1, 'https://example.com/nunchucks.mp3'),
(13, 1, 'Believer', 204, 3, 'https://example.com/believer.mp3'),
(14, 2, 'Set Fire to the Rain', 241, 3, 'https://example.com/set_fire_to_the_rain.mp3'),
(15, 3, 'Fake Love', 247, 2, 'https://example.com/fake_love.mp3');

-- --------------------------------------------------------

--
-- 資料表結構 `customers`
--

CREATE TABLE `customers` (
  `id` int(11) NOT NULL,
  `customer_uid` varchar(12) NOT NULL COMMENT '客戶唯一ID',
  `name` varchar(64) NOT NULL COMMENT '姓名',
  `email` varchar(128) NOT NULL COMMENT '電子郵件',
  `phone` varchar(20) NOT NULL COMMENT '電話號碼',
  `address` varchar(255) NOT NULL COMMENT '地址',
  `registered_at` datetime DEFAULT current_timestamp() COMMENT '註冊時間'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `customers`
--

INSERT INTO `customers` (`id`, `customer_uid`, `name`, `email`, `phone`, `address`, `registered_at`) VALUES
(1, 'CUST001', '林小明', 'linxiaoming@example.com', '0912345678', '台北市信義區市府路1號', '2025-05-01 10:00:00'),
(2, 'CUST002', '陳美玲', 'chenmeiling@example.com', '0923456789', '高雄市苓雅區四維三路2號', '2025-05-02 11:30:00'),
(3, 'CUST003', '王大華', 'wangdahua@example.com', '0934567890', '台中市西屯區台灣大道三段3號', '2025-05-03 14:00:00'),
(4, 'CUST004', '張雅君', 'zhangyajun@example.com', '0945678901', '新北市板橋區縣民大道四段4號', '2025-05-04 09:15:00'),
(5, 'CUST005', '黃柏翰', 'huangbohan@example.com', '0956789012', '台南市東區成功路5號', '2025-05-05 16:45:00');

-- --------------------------------------------------------

--
-- 資料表結構 `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL COMMENT '客戶ID',
  `order_date` datetime DEFAULT current_timestamp() COMMENT '訂單日期',
  `total_amount` int(11) NOT NULL COMMENT '總金額',
  `status` varchar(32) NOT NULL COMMENT '訂單狀態 (待處理/已完成/已取消)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- 傾印資料表的資料 `orders`
--

INSERT INTO `orders` (`id`, `customer_id`, `order_date`, `total_amount`, `status`) VALUES
(1, 1, '2025-05-06 09:00:00', 399, '已完成'),
(2, 2, '2025-05-07 10:30:00', 450, '已完成'),
(3, 3, '2025-05-08 11:45:00', 350, '待處理'),
(4, 1, '2025-05-09 13:00:00', 420, '已完成'),
(5, 4, '2025-05-10 15:20:00', 499, '已完成');

--
-- 已傾印資料表的索引
--

--
-- 資料表索引 `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`id`);

--
-- 資料表索引 `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`),
  ADD KEY `artist_id` (`artist_id`);

--
-- 資料表索引 `tracks`
--
ALTER TABLE `tracks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `album_id` (`album_id`);

--
-- 資料表索引 `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `customer_uid` (`customer_uid`),
  ADD UNIQUE KEY `email` (`email`);

--
-- 資料表索引 `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `customer_id` (`customer_id`);

--
-- 在傾印的資料表使用自動遞增(AUTO_INCREMENT)
--

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `artists`
--
ALTER TABLE `artists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `albums`
--
ALTER TABLE `albums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT COMMENT '主鍵', AUTO_INCREMENT=11;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `tracks`
--
ALTER TABLE `tracks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `customers`
--
ALTER TABLE `customers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 使用資料表自動遞增(AUTO_INCREMENT) `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- 已傾印資料表的限制(Foreign Keys)
--

--
-- 資料表限制(Foreign Keys) `albums`
--
ALTER TABLE `albums`
  ADD CONSTRAINT `albums_ibfk_1` FOREIGN KEY (`artist_id`) REFERENCES `artists` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表限制(Foreign Keys) `tracks`
--
ALTER TABLE `tracks`
  ADD CONSTRAINT `tracks_ibfk_1` FOREIGN KEY (`album_id`) REFERENCES `albums` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- 資料表限制(Foreign Keys) `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `orders_ibfk_1` FOREIGN KEY (`customer_id`) REFERENCES `customers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;