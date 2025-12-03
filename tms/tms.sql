-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 04, 2024 at 04:56 AM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tms`
--

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) NOT NULL,
  `UserName` varchar(100) DEFAULT NULL,
  `Name` varchar(250) DEFAULT NULL,
  `EmailId` varchar(250) DEFAULT NULL,
  `MobileNumber` bigint(10) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `updationDate` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `UserName`, `Name`, `EmailId`, `MobileNumber`, `Password`, `updationDate`) VALUES
(1, 'admin', 'Administrator', 'test@gmail.com', 7894561239, 'f925916e2754e5e03f75dd58a5733251', '2024-01-10 11:18:49');

-- --------------------------------------------------------

--
-- Table structure for table `tblbooking`
--

CREATE TABLE `tblbooking` (
  `BookingId` int(11) NOT NULL,
  `PackageId` int(11) DEFAULT NULL,
  `UserEmail` varchar(100) DEFAULT NULL,
  `FromDate` varchar(100) DEFAULT NULL,
  `ToDate` varchar(100) DEFAULT NULL,
  `Comment` mediumtext DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `status` int(11) DEFAULT NULL,
  `CancelledBy` varchar(5) DEFAULT NULL,
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblbooking`
--

INSERT INTO `tblbooking` (`BookingId`, `PackageId`, `UserEmail`, `FromDate`, `ToDate`, `Comment`, `RegDate`, `status`, `CancelledBy`, `UpdationDate`) VALUES
(1, 1, 'test@gmail.com', '2020-07-11', '2020-07-18', 'I want this package.', '2024-01-16 06:38:36', 2, 'u', '2024-01-30 05:18:29'),
(2, 2, 'test@gmail.com', '2020-07-10', '2020-07-13', 'There is some discount', '2024-01-17 06:43:25', 1, NULL, '2024-01-31 01:21:17'),
(3, 4, 'abir@gmail.com', '2020-07-11', '2020-07-15', 'When I get conformation', '2024-01-17 06:44:39', 2, 'a', '2024-01-30 05:18:52'),
(4, 2, 'test@gmail.com', '2024-02-02', '2024-02-08', 'NA', '2024-01-31 02:03:27', 1, NULL, '2024-01-31 06:35:08'),
(5, 3, 'test@gmail.com', '2024-01-31', '2024-02-05', 'please offer some discount', '2024-01-31 05:21:52', 0, NULL, NULL),
(6, 2, 'garima12@gmail.com', '2024-03-01', '2024-03-05', 'NA', '2024-02-03 13:04:33', 1, NULL, '2024-02-03 13:05:29');

-- --------------------------------------------------------

--
-- Table structure for table `tblenquiry`
--

CREATE TABLE `tblenquiry` (
  `id` int(11) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `EmailId` varchar(100) DEFAULT NULL,
  `MobileNumber` char(10) DEFAULT NULL,
  `Subject` varchar(100) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `Status` int(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblenquiry`
--

INSERT INTO `tblenquiry` (`id`, `FullName`, `EmailId`, `MobileNumber`, `Subject`, `Description`, `PostingDate`, `Status`) VALUES
(2, 'Kishan Twaerea', 'kishan@gmail.com', '6797947987', 'Enquiry', 'Any Offer for North Trip', '2024-01-18 06:31:38', NULL),
(3, 'Jacaob', 'Jai@gmail.com', '1646689721', 'Any offer for North', 'Any Offer for north', '2024-01-19 06:32:41', 1),
(5, 'hohn Doe', 'John12@gmail.com', '142536254', 'Test Subject', 'this is for testing', '2024-02-03 13:07:50', 1);

-- --------------------------------------------------------

--
-- Table structure for table `tblissues`
--

CREATE TABLE `tblissues` (
  `id` int(11) NOT NULL,
  `UserEmail` varchar(100) DEFAULT NULL,
  `Issue` varchar(100) DEFAULT NULL,
  `Description` mediumtext DEFAULT NULL,
  `PostingDate` timestamp NULL DEFAULT current_timestamp(),
  `AdminRemark` mediumtext DEFAULT NULL,
  `AdminremarkDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblissues`
--

INSERT INTO `tblissues` (`id`, `UserEmail`, `Issue`, `Description`, `PostingDate`, `AdminRemark`, `AdminremarkDate`) VALUES
(7, 'test@gmail.com', 'Refund', 'I want my refund', '2024-01-25 06:56:29', NULL, '2024-01-30 05:20:14'),
(10, 'test@gmail.com', 'Other', 'Test Sample', '2024-01-31 05:24:40', NULL, NULL),
(13, 'garima12@gmail.com', 'Booking Issues', 'I want some information ragrding booking', '2024-02-03 13:06:00', 'Infromation provided', '2024-02-03 13:06:26');

-- --------------------------------------------------------

--
-- Table structure for table `tblpages`
--

CREATE TABLE `tblpages` (
  `id` int(11) NOT NULL,
  `type` varchar(255) DEFAULT '',
  `detail` longtext DEFAULT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblpages`
--

INSERT INTO `tblpages` (`id`, `type`, `detail`) VALUES
(1, 'terms', '																				<p align=\"justify\"><span style=\"color: rgb(153, 0, 0); font-size: small; font-weight: 700;\">terms and condition page</span></p>\r\n										\r\n										'),
(2, 'privacy', '										<span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">At vero eos et accusamus et iusto odio dignissimos ducimus qui blanditiis praesentium voluptatum deleniti atque corrupti quos dolores et quas molestias excepturi sint occaecati cupiditate non provident, similique sunt in culpa qui officia deserunt mollitia animi, id est laborum et dolorum fuga. Et harum quidem rerum facilis est et expedita distinctio. Nam libero tempore, cum soluta nobis est eligendi optio cumque nihil impedit quo minus id quod maxime placeat facere possimus, omnis voluptas assumenda est, omnis dolor repellendus. Temporibus autem quibusdam et aut officiis debitis aut rerum necessitatibus saepe eveniet ut et voluptates repudiandae sint et molestiae non recusandae. Itaque earum rerum hic tenetur a sapiente delectus, ut aut reiciendis voluptatibus maiores alias consequatur aut perferendis doloribus asperiores repellat</span>\r\n										'),
(3, 'aboutus', '										<div><span style=\"color: rgb(0, 0, 0); font-family: Georgia; font-size: 15px; text-align: justify; font-weight: bold;\">Welcome to Tourism Management System!!!</span></div><span style=\"font-family: &quot;courier new&quot;;\"><span style=\"color: rgb(0, 0, 0); font-size: 15px; text-align: justify;\">Since then, our courteous and committed team members have always ensured a pleasant and enjoyable tour for the clients. This arduous effort has enabled TMS to be recognized as a dependable Travel Solutions provider with three offices Delhi.</span><span style=\"color: rgb(80, 80, 80); font-size: 13px;\">&nbsp;We have got packages to suit the discerning traveler\'s budget and savor. Book your dream vacation online. Supported quality and proposals of our travel consultants, we have a tendency to welcome you to decide on from holidays packages and customize them according to your plan.</span></span>\r\n										'),
(11, 'contact', '																				<span style=\"color: rgb(0, 0, 0); font-family: &quot;Open Sans&quot;, Arial, sans-serif; font-size: 14px; text-align: justify;\">Address------J-890 Dwarka House New Delhi-110096</span>');

-- --------------------------------------------------------

--
-- Table structure for table `tbltourpackages`
--

CREATE TABLE `tbltourpackages` (
  `PackageId` int(11) NOT NULL,
  `PackageName` varchar(200) DEFAULT NULL,
  `PackageType` varchar(150) DEFAULT NULL,
  `PackageLocation` varchar(100) DEFAULT NULL,
  `PackagePrice` int(11) DEFAULT NULL,
  `PackageFetures` varchar(255) DEFAULT NULL,
  `PackageDetails` mediumtext DEFAULT NULL,
  `PackageImage` varchar(100) DEFAULT NULL,
  `Creationdate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tbltourpackages`
--

INSERT INTO `tbltourpackages` (`PackageId`, `PackageName`, `PackageType`, `PackageLocation`, `PackagePrice`, `PackageFetures`, `PackageDetails`, `PackageImage`, `Creationdate`, `UpdationDate`) VALUES
(1, 'Kathmandu Valley Heritage Tour', 'Cultural Package', 'Kathmandu Valley', 15000, '3 Days / 2 Nights, UNESCO World Heritage Sites tour, Visit Pashupatinath Temple, Boudhanath Stupa, Swayambhunath (Monkey Temple), Kathmandu Durbar Square, Patan Durbar Square, Bhaktapur Durbar Square, Traditional Newari cuisine experience, Professional English-speaking guide, AC transportation, 3-star hotel accommodation', 'Explore the rich cultural heritage of Kathmandu Valley, home to 7 UNESCO World Heritage Sites. Visit ancient temples, stupas, and royal palaces that showcase Nepal\'s artistic and architectural brilliance. Experience the spiritual atmosphere of Pashupatinath, the peaceful vibes of Boudhanath, and the panoramic views from Swayambhunath. Walk through medieval cities of Patan and Bhaktapur, witnessing traditional craftsmanship and Newari culture. This package is perfect for culture enthusiasts and history lovers.', 'kathmandu-valley.jpg', '2024-07-15 05:21:58', NULL),
(2, 'Pokhara Paradise - Lakes & Mountains', 'Adventure Package', 'Pokhara', 18000, '4 Days / 3 Nights, Stunning mountain views, Phewa Lake boat ride, Visit World Peace Pagoda, Davis Falls & Gupteshwor Cave, Mahendra Cave exploration, Sarangkot sunrise viewpoint, Paragliding option (additional cost), Lakeside evening walks, Complimentary breakfast, Transport included', 'Discover the natural beauty of Pokhara, Nepal\'s adventure capital. Wake up to stunning views of Annapurna and Machhapuchhre (Fishtail) mountains. Enjoy peaceful boat rides on Phewa Lake, visit cascading waterfalls, mysterious caves, and witness breathtaking sunrise over the Himalayas from Sarangkot. Perfect blend of relaxation and adventure. Optional activities include paragliding, zip-lining, and ultra-light flights for thrill-seekers.', 'pokhara-lake.jpg', '2024-07-15 05:21:58', NULL),
(3, 'Everest Base Camp Trek - 14 Days', 'Trekking Package', 'Everest Region, Solukhumbu', 125000, '14 Days / 13 Nights trekking, Lukla flight included, Experienced Sherpa guide, Teahouse accommodation, All meals during trek, Sagarmatha National Park permit, TIMS card included, Porter service (1 porter for 2 trekkers), First aid kit & emergency support, Achievement certificate', 'Embark on the adventure of a lifetime! Trek to the base of the world\'s highest mountain - Mount Everest (8,848.86m). Journey through Sherpa villages, Buddhist monasteries, and breathtaking Himalayan landscapes. Visit Namche Bazaar (the Sherpa capital), Tengboche Monastery, and stand at Everest Base Camp (5,364m). Witness sunrise from Kala Patthar (5,545m) with panoramic views of Everest, Lhotse, and Ama Dablam. This trek requires good physical fitness and acclimatization.', 'everest-base-camp.jpg', '2024-07-15 05:21:58', NULL),
(4, 'Chitwan National Park Jungle Safari', 'Wildlife Package', 'Chitwan, Terai Region', 22000, '3 Days / 2 Nights, Jungle safari on elephant or jeep, Canoe ride in Rapti River, Bird watching tours, Visit elephant breeding center, Tharu cultural dance performance, Nature walks with naturalist, Full board accommodation in jungle resort, Park entry fees included', 'Experience Nepal\'s incredible wildlife at Chitwan National Park, a UNESCO World Heritage Site. Home to the endangered one-horned rhinoceros, Royal Bengal tiger, and over 500 species of birds. Enjoy thrilling jungle safaris, peaceful canoe rides, and learn about conservation efforts. Immerse yourself in Tharu culture with traditional dance performances. Perfect for nature lovers and families seeking adventure in the Terai lowlands.', 'chitwan-safari.jpg', '2024-07-15 05:21:58', NULL),
(5, 'Annapurna Base Camp Trek - 10 Days', 'Trekking Package', 'Annapurna Region, Kaski', 85000, '10 Days / 9 Nights trek, Round trip transport Pokhara-Nayapul, Expert trekking guide, Teahouse lodging, 3 meals daily during trek, Annapurna Conservation Area permit, TIMS permit, Porter service available, Hot springs visit at Jhinu Danda, Emergency evacuation support', 'Trek through diverse landscapes from lush rhododendron forests to alpine meadows and finally to the heart of the Annapurna Sanctuary (4,130m). Surrounded by towering peaks including Annapurna I (8,091m), Machhapuchhre, and Hiunchuli. Pass through traditional Gurung villages, experience local hospitality, and witness spectacular sunrise views. Less strenuous than Everest but equally rewarding. Suitable for moderately fit trekkers.', 'annapurna-base-camp.jpg', '2024-07-15 05:21:58', NULL),
(6, 'Lumbini - Birthplace of Buddha', 'Spiritual Package', 'Lumbini, Rupandehi', 12000, '2 Days / 1 Night, Visit Maya Devi Temple, Sacred Garden tour, Ashokan Pillar, International monasteries visit (Myanmar, China, Thailand, etc.), Puskarini Sacred Pond, Lumbini Museum exploration, Tilaurakot archaeological site, Peaceful meditation sessions, English-speaking guide', 'Visit Lumbini, the sacred birthplace of Lord Buddha and a UNESCO World Heritage Site. Explore the Maya Devi Temple marking the exact birthplace, walk through the peaceful Sacred Garden, and visit the ancient Ashokan Pillar. Experience diverse Buddhist architecture from around the world in the monastery zone. Perfect for those seeking peace, spirituality, and historical insights into Buddhism. A must-visit pilgrimage site.', 'lumbini-buddha.jpg', '2024-07-15 05:21:58', NULL),
(7, 'Nagarkot Sunrise & Bhaktapur Heritage', 'Day Tour Package', 'Nagarkot & Bhaktapur', 5500, '1 Day tour, Early morning pickup (4:30 AM), Sunrise view from Nagarkot viewpoint, Himalayan range panorama, Breakfast at hilltop resort, Bhaktapur Durbar Square exploration, Pottery Square visit, Nyatapola Temple, Traditional yogurt tasting, Return by evening, AC vehicle transport', 'Experience magical sunrise over the Himalayas from Nagarkot (2,195m) - see Everest, Langtang, Ganesh Himal, and Manaslu ranges. After breakfast, explore the medieval city of Bhaktapur - a living museum of ancient Newari culture. Admire 55-Window Palace, Nyatapola Temple (tallest in Nepal), and watch traditional pottery making. Taste the famous "Juju Dhau" (King of Yogurt). Perfect day trip from Kathmandu.', 'nagarkot-sunrise.jpg', '2024-07-15 05:21:58', NULL),
(8, 'Upper Mustang - The Last Forbidden Kingdom', 'Cultural Trekking', 'Mustang, Dhaulagiri Zone', 185000, '12 Days / 11 Nights, Special restricted area permit included, Flight Pokhara-Jomsom-Pokhara, Experienced guide familiar with Mustang, Teahouse/camping accommodation, All meals during trek, Lo Manthang (walled city) visit, Ancient monasteries exploration, Tibetan Buddhist culture immersion, Support staff and pack animals', 'Journey to the hidden kingdom of Upper Mustang, a restricted area that preserves ancient Tibetan Buddhist culture. Trek through dramatic desert-like landscapes with colorful rock formations, visit the walled city of Lo Manthang (capital), explore centuries-old monasteries with rare Buddhist art, and experience authentic Tibetan lifestyle. Limited tourists allowed annually, making it an exclusive adventure. Witness landscapes unlike anywhere else in Nepal.', 'upper-mustang.jpg', '2024-07-15 05:21:58', NULL),
(9, 'Langtang Valley Trek - 8 Days', 'Mountain Trekking', 'Langtang Region, Rasuwa', 45000, '8 Days / 7 Nights, Kathmandu-Syabrubesi transport, Langtang National Park permit, Experienced trekking guide, Lodge accommodation, Full board meals, Visit Kyanjin Gompa monastery, Cheese factory tour, Optional Tserko Ri climb (4,984m), Red panda habitat area, Porter service optional', 'Explore the beautiful Langtang Valley, known as the "Valley of Glaciers". Close to Kathmandu yet remote and peaceful. Trek through Tamang villages, rhododendron forests, and high alpine pastures. Visit the famous cheese factory, explore Kyanjin Gompa monastery, and enjoy stunning views of Langtang Lirung (7,227m). This region was affected by the 2015 earthquake but has beautifully recovered. A perfect trek for those with limited time.', 'langtang-valley.jpg', '2024-07-15 05:21:58', NULL),
(10, 'Rara Lake - Nepal\'s Largest Lake', 'Remote Adventure', 'Rara, Mugu District', 95000, '7 Days / 6 Nights, Domestic flight Nepalgunj-Talcha, Rara National Park entry, Camping/lodge accommodation, Full board meals, Lake circuit trek, Visit Rara Village & Murma Top, Rare wildlife spotting opportunity, Pristine nature experience, Return flight included, Guide and porters', 'Discover Rara Lake (2,990m), Nepal\'s largest and deepest lake, located in the remote northwestern region. Crystal clear blue waters surrounded by snow-capped peaks and pine forests create a surreal landscape. Trek around the lake, visit the small Rara village, and spot rare wildlife including red panda, musk deer, and Himalayan black bear. Very few tourists visit this area, offering true wilderness experience. Perfect for those seeking solitude and untouched nature.', 'rara-lake.jpg', '2024-07-15 05:21:58', NULL),
(11, 'Gosaikunda Sacred Lake Trek', 'Spiritual Trekking', 'Langtang Region, Rasuwa', 35000, '6 Days / 5 Nights, Kathmandu transport, Langtang National Park permit, Trekking guide, Teahouse accommodation, All meals included, Visit sacred Gosaikunda Lake (4,380m), Hindu pilgrimage site, Laurebina Pass crossing, Helambu circuit option, Spectacular mountain views', 'Trek to the sacred Gosaikunda Lake, an important Hindu pilgrimage site. According to legend, Lord Shiva created this lake. During the Janai Purnima festival (August), thousands of pilgrims visit. The trek offers beautiful mountain views, pristine alpine lakes, and passes through Sherpa and Tamang villages. Can be combined with Helambu circuit for a longer trek. Moderate difficulty level with high altitude, requiring proper acclimatization.', 'gosaikunda-lake.jpg', '2024-07-15 05:21:58', NULL),
(12, 'Tilicho Lake - Highest Lake Trek', 'Extreme Adventure', 'Annapurna Region, Manang', 95000, '13 Days / 12 Nights, Pokhara-Besisahar transport, Annapurna permit & TIMS, Expert high-altitude guide, Teahouse/camping accommodation, All meals during trek, Tilicho Lake (4,919m) visit, Thorong La Pass option, Ice Lake side trek, Manang acclimatization, Emergency support', 'Challenge yourself with a trek to Tilicho Lake (4,919m), one of the highest lakes in the world. Located in the Annapurna region, this stunning turquoise lake sits in a glacial basin surrounded by towering peaks. The trek is challenging with high altitude and requires good physical fitness. Combines with the famous Annapurna Circuit for an extended adventure. Witness dramatic landscapes, cross high passes, and experience the thrill of extreme altitude trekking.', 'tilicho-lake.jpg', '2024-07-15 05:21:58', NULL);

-- Adding 18 more Nepal tourism packages to reach a total of 30 packages

INSERT INTO `tbltourpackages` (`PackageId`, `PackageName`, `PackageType`, `PackageLocation`, `PackagePrice`, `PackageFetures`, `PackageDetails`, `PackageImage`, `Creationdate`, `UpdationDate`) VALUES
(13, 'Patan Durbar Square Heritage Walk', 'Cultural Package', 'Lalitpur, Kathmandu Valley', 8500, '1 Day Tour, UNESCO World Heritage Site visit, Krishna Temple exploration, Hiranya Varna Mahavihar (Golden Temple), Traditional metal craft workshop, Local Newari cuisine tasting, Professional guide, AC transportation', 'Discover the ancient city of Patan, also known as Lalitpur, one of the three ancient royal cities in the Kathmandu Valley. Explore the magnificent Patan Durbar Square, a UNESCO World Heritage Site, with its intricate temples, statues, and palaces. Visit the Krishna Temple with its beautiful stone carvings, and the Golden Temple (Hiranya Varna Mahavihar), an architectural marvel covered in gold. Experience traditional metal crafting techniques at local workshops and savor authentic Newari cuisine. Perfect for culture enthusiasts and those interested in traditional arts and crafts.', 'patan-heritage.jpg', '2024-07-15 05:21:58', NULL),
(14, 'Bhaktapur Durbar Square Cultural Tour', 'Cultural Package', 'Bhaktapur, Kathmandu Valley', 9500, '1 Day Tour, UNESCO World Heritage Square visit, Pottery Square exploration, Dattatreya Square walk, Traditional pottery making experience, Local handicraft shopping, Nyatapola Temple visit, Authentic Newari lunch, AC transportation', 'Step back in time with a visit to Bhaktapur, the City of Devotees, which has preserved its medieval character better than any other city in the Kathmandu Valley. Wander through the atmospheric Bhaktapur Durbar Square, home to the impressive Nyatapola Temple, the tallest temple in Nepal. Explore Pottery Square where traditional potters still work using ancient techniques, and Dattatreya Square with its unique three-faced statue. Participate in a pottery-making session, shop for traditional handicrafts, and enjoy an authentic Newari lunch. Experience the living culture of medieval Nepal.', 'bhaktapur-cultural.jpg', '2024-07-15 05:21:58', NULL),
(15, 'Pashupatinath Temple Spiritual Journey', 'Spiritual Package', 'Kathmandu, Bagmati Zone', 7500, '1 Day Spiritual Tour, Pashupatinath Temple darshan, Deupatan visit, Guhyeshwari Temple exploration, Bagmati River aarti ceremony, Sadhu interactions, Sacred cremation ghats visit, Vegetarian lunch, AC transportation', 'Embark on a deeply spiritual journey at Pashupatinath, one of the most sacred Hindu temples dedicated to Lord Shiva. Located on the banks of the holy Bagmati River, this UNESCO World Heritage Site attracts millions of devotees annually. Witness the evening aarti ceremony, observe the sacred cremation ghats, and interact with holy men (sadhus) from around the world. Visit the nearby Guhyeshwari Temple and Deupatan, gaining insights into Hindu traditions and rituals. Experience the spiritual energy that makes this place one of the holiest sites for Hindus worldwide.', 'pashupatinath-spiritual.jpg', '2024-07-15 05:21:58', NULL),
(16, 'Boudhanath Stupa Buddhist Pilgrimage', 'Spiritual Package', 'Kathmandu, Boudha Zone', 8000, '1 Day Buddhist Pilgrimage, Boudhanath Stupa circumambulation, Tibetan monastery visits, Buddhist prayer wheel experience, Tibetan cultural center tour, Traditional Buddhist lunch, Meditation session, AC transportation', 'Experience the serene atmosphere of Boudhanath Stupa, one of the largest spherical stupas in Nepal and a UNESCO World Heritage Site. This important Buddhist pilgrimage site is surrounded by numerous monasteries, shops, and restaurants run by the Tibetan community. Walk around the stupa in a clockwise direction (circumambulation), spin the prayer wheels, and visit various Tibetan monasteries representing different Buddhist traditions. Enjoy traditional Buddhist cuisine and participate in a guided meditation session. Perfect for those seeking spiritual enlightenment and cultural immersion.', 'boudhanath-buddhist.jpg', '2024-07-15 05:21:58', NULL),
(17, 'Swayambhunath Monkey Temple Sunrise Visit', 'Spiritual Package', 'Kathmandu, Swayambhu Hill', 6500, 'Early Morning Tour, Sunrise view from temple, 360-degree Himalayan panorama, Ancient Buddhist stupa exploration, Monkey interactions, Prayer wheel experience, Local monastery visits, Traditional breakfast, AC transportation', 'Witness a breathtaking sunrise from the ancient Swayambhunath Stupa, popularly known as the Monkey Temple due to the hundreds of monkeys residing around the site. Perched on a hilltop west of Kathmandu Valley, this UNESCO World Heritage Site offers spectacular 360-degree views of the Himalayas and the valley below. Explore the ancient Buddhist stupa, spin the prayer wheels, and visit the surrounding monasteries. Enjoy interactions with the friendly monkeys and savor a traditional Nepali breakfast while taking in the panoramic views. A perfect spiritual and scenic experience.', 'swayambhunath-monkey.jpg', '2024-07-15 05:21:58', NULL),
(18, 'Nagarkot to Dhulikhel Scenic Drive', 'Scenic Tour Package', 'Nagarkot & Dhulikhel, Bagmati Zone', 11000, '2 Days / 1 Night, Scenic mountain drive, Panoramic Himalayan views, Traditional village walks, Local cultural interactions, Farmhouse experience, Authentic Nepali meals, Comfortable lodge accommodation, AC transportation', 'Enjoy a scenic drive from Nagarkot to Dhulikhel, two of the most beautiful hill stations in the Kathmandu Valley region. Both destinations offer spectacular views of the Himalayan range including Everest, Langtang, and Ganesh Himal. Experience the rural charm of traditional Nepali villages, interact with local communities, and enjoy authentic Nepali cuisine. Overnight in a comfortable lodge and enjoy the peaceful mountain atmosphere away from the hustle and bustle of the city. Perfect for nature lovers and photographers.', 'nagarkot-dhulikhel.jpg', '2024-07-15 05:21:58', NULL),
(19, 'Ilam Tea Garden & Kanyam Viewpoint Tour', 'Scenic Tour Package', 'Ilam, Koshi Province', 13500, '3 Days / 2 Nights, Tea garden tours, Kanyam Sunrise viewpoint, Antu Danda panoramic view, Local tea tasting experience, Traditional Limbu village visit, Authentic local cuisine, Lodge accommodation, AC transportation', 'Discover the beautiful tea gardens of Ilam, often called the "Darjeeling of Nepal." Visit the famous Kanyam Tea Estate and learn about tea processing from plantation to cup. Wake up early to witness spectacular sunrise views from Kanyam Viewpoint, with panoramic vistas of the Eastern Himalayas. Explore Antu Danda for more breathtaking mountain views, visit traditional Limbu villages, and taste authentic local cuisine including Gundruk and Kinema. Experience the unique culture of the Limbu community and enjoy the tranquil atmosphere of the tea gardens.', 'ilam-tea-garden.jpg', '2024-07-15 05:21:58', NULL),
(20, 'Bandipur Heritage Village Experience', 'Cultural Package', 'Bandipur, Gandaki Province', 10500, '2 Days / 1 Night, Traditional Newari village walk, Stone paved street exploration, Local museum visit, Traditional handicraft workshops, Authentic Newari cuisine, Panoramic mountain views, Heritage hotel accommodation, AC transportation', 'Step into a living museum at Bandipur, a beautifully preserved Newari village perched on a ridge between Kathmandu and Pokhara. Explore the stone-paved streets lined with traditional houses, temples, and shops. Visit the local museum showcasing traditional artifacts and learn about the history of this ancient trading post. Participate in handicraft workshops to learn traditional skills like pottery and weaving. Enjoy stunning views of the Annapurna and Manaslu ranges from various viewpoints. Experience authentic Newari hospitality and cuisine at a heritage hotel.', 'bandipur-heritage.jpg', '2024-07-15 05:21:58', NULL),
(21, 'Dhampus & Sarangkot Sunrise Trek', 'Trekking Package', 'Dhampus & Sarangkot, Gandaki Province', 12500, '3 Days / 2 Nights, Moderate trekking experience, Sunrise view from Sarangkot, Phewa Lake panoramic view, Traditional Gurung village visit, Local cultural interactions, Authentic Nepali meals, Teahouse accommodation, Experienced trekking guide', 'Experience a moderate trek through beautiful landscapes from Dhampus to Sarangkot, offering some of the best sunrise views in Nepal. Begin in the traditional Gurung village of Dhampus and trek through terraced fields and rhododendron forests. Reach Sarangkot, famous for its spectacular sunrise views over the Annapurna and Dhaulagiri ranges. Enjoy panoramic views of Phewa Lake and the Pokhara Valley. Visit local Gurung villages to experience their unique culture and traditions. Perfect for those looking for a short but rewarding trek with stunning mountain views.', 'dhampus-sarangkot.jpg', '2024-07-15 05:21:58', NULL),
(22, 'Muktinath Temple & Mustang Road Tour', 'Spiritual Package', 'Mustang, Gandaki Province', 28500, '5 Days / 4 Nights, Muktinath Temple pilgrimage, Jomsom airport transfer, Kagbeni ancient village visit, Mustang Road scenic drive, 108 water spouts darshan, Bon Buddhist monastery visit, Traditional Thakali lunch, Lodge accommodation, AC transportation', 'Embark on a spiritual journey to Muktinath Temple, one of the most sacred pilgrimage sites for both Hindus and Buddhists. Located in the Mustang region at an altitude of 3,710m, this temple is dedicated to Lord Vishnu and is famous for its 108 water spouts. Travel through the scenic Mustang Road, passing through the ancient walled village of Kagbeni. Visit Bon Buddhist monasteries and experience the unique Trans-Himalayan culture. Enjoy traditional Thakali cuisine and witness the stark beauty of the Mustang landscape. A deeply spiritual and culturally enriching experience.', 'muktinath-temple.jpg', '2024-07-15 05:21:58', NULL),
(23, 'Shivapuri National Park Hiking', 'Adventure Package', 'Shivapuri, Kathmandu', 7000, '1 Day Adventure, National park hiking, Bageshwori Temple visit, Nagarjun Temple exploration, Spring water source visit, Bird watching, Nature photography, Picnic lunch, AC transportation', 'Explore the pristine beauty of Shivapuri National Park, Kathmandu's closest national park and an important watershed area. This park is home to diverse flora and fauna, including over 90 bird species and the elusive Himalayan black bear. Hike through rhododendron and oak forests, visit the sacred Bageshwori and Nagarjun temples, and see the source of several important springs that supply drinking water to Kathmandu Valley. Perfect for nature lovers, bird watchers, and photographers seeking a peaceful escape from the city.', 'shivapuri-hiking.jpg', '2024-07-15 05:21:58', NULL),
(24, 'Patan Museum & Art Gallery Tour', 'Cultural Package', 'Lalitpur, Kathmandu Valley', 9000, '1 Day Cultural Tour, Patan Museum exploration, Traditional art gallery visit, Metal craft workshops, Ancient sculpture viewing, Local artisan interactions, Traditional Newari lunch, AC transportation', 'Discover the rich artistic heritage of Patan through a comprehensive tour of Patan Museum and local art galleries. Patan Museum, housed in a beautifully restored traditional building, showcases an impressive collection of bronze and metal sculptures, traditional paintings, and religious artifacts. Visit local art galleries featuring contemporary Nepali artists and traditional craft workshops where skilled artisans create intricate metalwork, pottery, and wood carvings. Interact with local artists and craftspeople, gaining insights into traditional techniques passed down through generations. Enjoy a traditional Newari lunch to complete this cultural experience.', 'patan-museum.jpg', '2024-07-15 05:21:58', NULL),
(25, 'Kirtipur Ancient City Exploration', 'Cultural Package', 'Kirtipur, Kathmandu Valley', 8500, '1 Day Historical Tour, Ancient city walk, Durbar Square exploration, Traditional architecture viewing, Local market visit, Handicraft shopping, Traditional Nepali lunch, AC transportation', 'Explore the ancient city of Kirtipur, one of the oldest settlements in the Kathmandu Valley with a rich history dating back over 2000 years. Wander through the narrow streets of this UNESCO World Heritage tentative site, exploring its traditional Newari architecture, ancient temples, and historic buildings. Visit the Kirtipur Durbar Square with its beautifully carved windows and traditional structures. Browse the local markets for traditional handicrafts and interact with friendly locals. Experience the authentic atmosphere of this less-touristed but historically significant city.', 'kirtipur-ancient.jpg', '2024-07-15 05:21:58', NULL),
(26, 'Naukunda Tal Sacred Lake Trek', 'Spiritual Trekking', 'Myagdi, Gandaki Province', 32000, '7 Days / 6 Nights, Sacred lake pilgrimage, Remote mountain trekking, Traditional village visits, Local cultural experiences, Mountain panorama views, Teahouse accommodation, Experienced guide, Full board meals', 'Trek to the sacred Naukunda Tal (Crystal Lake), a pristine alpine lake surrounded by dramatic peaks in the remote Myagdi region. This challenging trek takes you through traditional Gurung and Magar villages, offering authentic cultural experiences with local communities. The lake holds religious significance for both Hindus and Buddhists and is believed to grant wishes to sincere pilgrims. Enjoy breathtaking views of Annapurna, Dhaulagiri, and Nilgiri ranges throughout the trek. Experience the solitude and spiritual energy of this remote and sacred destination.', 'naukunda-tal.jpg', '2024-07-15 05:21:58', NULL),
(27, 'Pharping & Champadevi Hill Pilgrimage', 'Spiritual Package', 'Pharping, Kathmandu Valley', 6800, '1 Day Spiritual Tour, Pharping village exploration, Champadevi Temple visit, Dakshinkali Temple darshan, Ancient monastery visits, Local cultural interactions, Vegetarian lunch, AC transportation', 'Undertake a spiritual pilgrimage to Pharping, an important Hindu and Buddhist pilgrimage site located in the southern part of Kathmandu Valley. Visit the sacred Champadevi Temple dedicated to Goddess Chamunda, one of the ten Mahavidyas, and the Dakshinkali Temple, dedicated to Goddess Kali. Explore ancient monasteries and interact with local devotees. The area is also known for its traditional metal craft workshops and ancient Newari architecture. Experience the spiritual atmosphere away from the crowds of more popular pilgrimage sites.', 'pharping-pilgrimage.jpg', '2024-07-15 05:21:58', NULL),
(28, 'Daman Viewpoint & Traditional Village Tour', 'Scenic Tour Package', 'Daman, Makawanpur', 9500, '2 Days / 1 Night, Panoramic Himalayan views, Traditional village walk, Local cultural interactions, Handicraft workshops, Authentic Nepali meals, Comfortable lodge accommodation, AC transportation', 'Experience the breathtaking panoramic views from Daman Viewpoint, one of the best viewpoints in Nepal for seeing the entire Himalayan range from Everest to Annapurna. Located at an altitude of 2,250m, Daman offers 360-degree views of the Himalayas, including 14 peaks over 7,000m. Explore the traditional Tamang village, interact with local communities, and participate in handicraft workshops. Enjoy the peaceful mountain atmosphere and authentic Nepali hospitality at a comfortable lodge. Perfect for photographers and those seeking tranquil mountain views.', 'daman-viewpoint.jpg', '2024-07-15 05:21:58', NULL),
(29, 'Helambu Trek - Short Himalayan Adventure', 'Trekking Package', 'Helambu, Bagmati Province', 21000, '6 Days / 5 Nights, Short Himalayan trek, Traditional Sherpa village visits, Buddhist monastery exploration, Langtang National Park buffer zone, Mountain panorama views, Teahouse accommodation, Experienced guide, Full board meals', 'Experience the beauty of the Helambu region on this short but rewarding trek that takes you through traditional Sherpa villages and beautiful mountain landscapes. Visit ancient Buddhist monasteries, interact with friendly Sherpa communities, and enjoy spectacular views of the Langtang and Ganesh Himal ranges. The trek passes through the buffer zone of Langtang National Park, offering opportunities to see diverse flora and fauna. Perfect for those with limited time who want to experience authentic Himalayan culture and stunning mountain scenery.', 'helambu-trek.jpg', '2024-07-15 05:21:58', NULL),
(30, 'Makalu Base Camp Trek - Ultimate Adventure', 'Extreme Adventure', 'Makalu, Sagarmatha Province', 165000, '18 Days / 17 Nights, Ultimate mountain adventure, Makalu Base Camp visit, Barun Valley exploration, Traditional village visits, Remote mountain trekking, Spectacular peak views, Full camping equipment, Experienced high-altitude guide, Emergency support', 'Embark on the ultimate adventure to Makalu Base Camp, a challenging and remote trek to the base of the world's fifth highest mountain (8,485m). This trek takes you through the pristine Barun Valley, one of the most remote and beautiful valleys in Nepal. Experience the unique culture of the Sherpa and Rai communities, witness spectacular views of Makalu, Everest, Lhotse, and Cho Oyu, and explore unspoiled natural beauty. This is a true expedition for experienced trekkers seeking the ultimate Himalayan adventure.', 'makalu-base-camp.jpg', '2024-07-15 05:21:58', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `tblusers`
--

CREATE TABLE `tblusers` (
  `id` int(11) NOT NULL,
  `FullName` varchar(100) DEFAULT NULL,
  `MobileNumber` char(10) DEFAULT NULL,
  `EmailId` varchar(70) DEFAULT NULL,
  `Password` varchar(100) DEFAULT NULL,
  `RegDate` timestamp NULL DEFAULT current_timestamp(),
  `UpdationDate` timestamp NULL DEFAULT NULL ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `tblusers`
--

INSERT INTO `tblusers` (`id`, `FullName`, `MobileNumber`, `EmailId`, `Password`, `RegDate`, `UpdationDate`) VALUES
(1, 'Manju Srivatav', '4456464654', 'manju@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-01-16 06:33:20', '2024-01-31 02:00:40'),
(2, 'Kishan', '9871987979', 'kishan@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-01-16 06:33:20', '2024-01-31 02:00:48'),
(3, 'Salvi Chandra', '1398756416', 'salvi@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-01-16 06:33:20', '2024-01-31 02:00:48'),
(4, 'Abir', '4789756456', 'abir@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-01-16 06:33:20', '2024-01-31 02:00:48'),
(5, 'Test', '1987894654', 'test@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2024-01-16 06:33:20', '2024-01-31 02:00:48'),
(9, 'Test Sample', '4654654564', 'testsample@gmail.com', '202cb962ac59075b964b07152d234b70', '2024-01-31 06:32:51', NULL),
(10, 'Garima Singh', '1425362540', 'garima12@gmail.com', 'f925916e2754e5e03f75dd58a5733251', '2024-02-03 13:03:43', '2024-02-03 13:04:02');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblbooking`
--
ALTER TABLE `tblbooking`
  ADD PRIMARY KEY (`BookingId`);

--
-- Indexes for table `tblenquiry`
--
ALTER TABLE `tblenquiry`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblissues`
--
ALTER TABLE `tblissues`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tblpages`
--
ALTER TABLE `tblpages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tbltourpackages`
--
ALTER TABLE `tbltourpackages`
  ADD PRIMARY KEY (`PackageId`);

--
-- Indexes for table `tblusers`
--
ALTER TABLE `tblusers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `EmailId` (`EmailId`),
  ADD KEY `EmailId_2` (`EmailId`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `tblbooking`
--
ALTER TABLE `tblbooking`
  MODIFY `BookingId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `tblenquiry`
--
ALTER TABLE `tblenquiry`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `tblissues`
--
ALTER TABLE `tblissues`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `tblpages`
--
ALTER TABLE `tblpages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT for table `tbltourpackages`
--
ALTER TABLE `tbltourpackages`
  MODIFY `PackageId` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `tblusers`
--
ALTER TABLE `tblusers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
