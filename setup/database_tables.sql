
--
-- Table structure for table `wb_areas`
--

CREATE TABLE IF NOT EXISTS `wb_areas` (
  `area_id` int(4) NOT NULL AUTO_INCREMENT,
  `region_id` int(4) NOT NULL DEFAULT '0',
  `name` varchar(50) NOT NULL DEFAULT '0',
  `description` text NOT NULL,
  PRIMARY KEY (`area_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=27 ;

--
-- Dumping data for table `wb_areas`
--

INSERT INTO `wb_areas` (`area_id`, `region_id`, `name`, `description`) VALUES
(1, 3, 'Snowy Mountains', 'Harsh and uninviting mountains shrouded in a vale of impenetrable fog. A long and treacherous path leads east. Thread carefully!'),
(2, 3, 'Perilous Mountains', 'The path winds its way along the southern side of the mountains. You can glimpse flashes of lightning in the dark clouds above and the thunder echoes ominously around the mountains. The treacherous mountains continue eastward as far as your eyes can see.'),
(3, 3, 'Ancient Rickety Bridge', 'The path leads to an ancient bridge over a vast canyon between the mountains. As if sensing your approach, the bridge begins to shake erratically in the wind as you move towards it'),
(4, 3, 'Perilous Mountains', 'The path continues to wind its way along the southern side of the mountains. The lightning and thunder get more powerful as you continue eastward and the mountains shudder with every ferocious crack of thunder.'),
(5, 3, 'Perilous Mountains', 'The path comes to an abrupt end by a large landslide which completely blocks the path. The landslide looks recent. You can no longer travel eastward.'),
(6, 1, 'A Peaceful River', 'The sun shines brightly over this tranquil land. A large river carves the land in twain but it is slow moving and should be easy to traverse.'),
(12, 1, 'An Idyllic Riverbank', 'A peaceful river glistens in the sunshine and flows gently southwards. A lone bird stares enviously at the small fish swimming just beneath the surface.'),
(16, 1, 'A Peaceful River', 'The sun shines brightly over this tranquil land. A gentle river flows slowly through here.'),
(11, 1, 'The Mysterious Isle of Avalon', '\r\n              .-.\r\n            __| |__\r\n           [__   __]\r\n              | |\r\n              | |\r\n              | |\r\n              ''-''\r\n\r\nThe resting place of the revered King Arthur. The air itself tingles with sorcery and the wind-blown trees seem to cry out in anguish. An ancient monastery dominates the landscape.'),
(7, 4, 'An Ancient Forest', 'The sun shines dimly through the branches of the old trees. The trees seem to be home to a vast array of creatures which stare at you inquisitively as you walk past. The ancient path winds its way east through the trees.'),
(8, 4, 'An Ancient Forest', 'The sun shines dimly through the branches of the old trees. The trees seem to be home to a vast array of creatures which stare at you inquisitively as you walk past. The ancient path winds its way west and south through the trees.'),
(13, 4, 'An Ancient Forest', 'The sun shines dimly through the branches of the old trees. The trees seem to be home to a vast array of creatures which stare at you inquisitively as you walk past. The ancient path winds its way north through the trees.'),
(9, 2, 'The Plains', 'Harsh and unforgiving plains. You can see miles in every direction but nothing is visible except the ever-present Northern Mountains.'),
(10, 2, 'The Plains', 'Harsh and unforgiving plains. You can see miles in every direction but nothing is visible except the ever-present northern mountains.'),
(14, 2, 'The Plains', 'Harsh and unforgiving plains. You can see miles in every direction but nothing is visible except the ever-present northern mountains.'),
(15, 2, 'The Plains', 'Harsh and unforgiving plains. You can see miles in every direction but nothing is visible except the ever-present northern mountains.'),
(20, 2, 'The Plains', '\r\n                        ;;\r\n                      ,;;''\\\\ \r\n           __       ,;;'' '' \\\\ \r\n         /''  ''\\\\''~~''~'' \\\\ /''\\\\.)\r\n      ,;(      )    /  | \r\n     ,;'' \\\\    /-.,,(   )\r\n          ) /|      ) /|\r\n          ||(_\\\\     ||(_\\\\ \r\n          (_\\\\       (_\\\\ \r\n\r\nHarsh and unforgiving plains. You can see miles in every direction but nothing is visible except the ever-present northern mountains.'),
(25, 2, 'The Plains', 'Harsh and unforgiving plains. You can see miles in every direction but nothing is visible except the ever-present northern mountains.'),
(17, 1, 'Grass Lands', 'Green and healthy vegetation flourishes here. You can make out farms in the distance from the gentle wisps of smoke that curl their way out of the chimneys. Camelot castle should be nearby.'),
(18, 1, 'Grass Lands', 'Green and healthy vegetation flourishes here. You can make out farms in the distance from the gentle wisps of smoke that curl their way out of the chimneys. Camelot castle should be nearby.'),
(19, 1, 'Grass Lands', 'Green and healthy vegetation flourishes here. You can make out farms in the distance from the gentle wisps of smoke that curl their way out of the chimneys. Camelot castle should be nearby.'),
(21, 1, 'Grass Lands', 'Green and healthy vegetation flourishes here. You can make out farms in the distance from the gentle wisps of smoke that curl their way out of the chimneys. Camelot castle should be nearby.'),
(22, 1, 'Grass Lands', 'Green and healthy vegetation flourishes here. You can make out farms in the distance from the gentle wisps of smoke that curl their way out of the chimneys. Camelot castle should be nearby.'),
(24, 1, 'Grass Lands', 'Green and healthy vegetation flourishes here. You can make out farms in the distance from the gentle wisps of smoke that curl their way out of the chimneys. Camelot castle should be nearby.'),
(23, 1, 'The Castle of Camelot', '\r\n                   |>>>\r\n                   | \r\n     |>>>      _  _|_  _         |>>>\r\n     |        |;| |;| |;|        |\r\n _  _|_  _    \\\\\\\\.    .  /    _  _|_  _\r\n|;|_|;|_|;|    \\\\\\\\: +   /    |;|_|;|_|;|\r\n\\\\\\\\..      /    ||:+++. |    \\\\\\\\.    .  /\r\n \\\\\\\\.  ,  /     ||:+++  |     \\\\\\\\:  .  /\r\n  ||:+  |_   _ ||_ . _ | _   _||:+  |\r\n  ||+++.|||_|;|_|;|_|;|_|;|_|;||+++ |\r\n  ||+++ ||.    .     .      . ||+++.|\r\n  ||: . ||:.     . .   .  ,   ||:   |\r\n  ||:   ||:  ,     +       .  ||: , |\r\n  ||:   ||:.     +++++      . ||:   |\r\n  ||:   ||.     +++++++  .    ||: . |\r\n  ||: . ||: ,   +++++++ .  .  ||:   |\r\n  ||: . ||: ,   +++++++ .  .  ||:   |\r\n  ||: . ||: ,   +++++++ .  .  ||:   |\r\n\r\nColossal white walls rise up hundreds of feet into the air. Watchful sentries are visible along the top of the wall and archers are carefully examining the throng of people entering the castle.'),
(0, 0, '', '\r\n                                    |>>>\r\n                                    | \r\n                      |>>>      _  _|_  _         |>>>\r\n                      |        |;| |;| |;|        |\r\n                  _  _|_  _    \\\\\\\\.    .  /    _  _|_  _\r\n                 |;|_|;|_|;|    \\\\\\\\: +   /    |;|_|;|_|;|\r\n                 \\\\\\\\..      /    ||:+++. |    \\\\\\\\.    .  /\r\n                  \\\\\\\\.  ,  /     ||:+++  |     \\\\\\\\:  .  /\r\n                   ||:+  |_   _ ||_ . _ | _   _||:+  |\r\n                   ||+++.|||_|;|_|;|_|;|_|;|_|;||+++ |\r\n                   ||+++ ||.    .     .      . ||+++.|\r\n                   ||: . ||:.     . .   .  ,   ||:   |\r\n                   ||:   ||:  ,     +       .  ||: , |\r\n                   ||:   ||:.     +++++      . ||:   |\r\n                   ||:   ||.     +++++++  .    ||: . |\r\n                   ||: . ||: ,   +++++++ .  .  ||:   |\r\n                   ||: . ||: ,   +++++++ .  .  ||:   |\r\n\r\n       _____       ___       ___  ___   _____   _       _____   _____\r\n      /  ___|     /   |     /   |/   | | ____| | |     /  _  \\\\ |_   _|\r\n      | |        / /| |    / /|   /| | | |__   | |     | | | |   | |\r\n      | |       / / | |   / / |__/ | | |  __|  | |     | | | |   | |\r\n      | |___   / /  | |  / /       | | | |___  | |___  | |_| |   | |\r\n      \\\\_____| /_/   |_| /_/        |_| |_____| |_____| \\\\_____/   |_|\r\n\r\n                                                          by Paul Hitz\r\n\r\n                       *** PRESS SPACE TO BEGIN ***');

-- --------------------------------------------------------

--
-- Table structure for table `wb_regions`
--

CREATE TABLE IF NOT EXISTS `wb_regions` (
  `region_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`region_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=5 ;

--
-- Dumping data for table `wb_regions`
--

INSERT INTO `wb_regions` (`region_id`, `name`) VALUES
(1, 'The Kingdom of Camelot'),
(2, 'The Eastern Plains'),
(3, 'The Northern Mountains'),
(4, 'The Forest of Avalon');

-- --------------------------------------------------------

--
-- Table structure for table `wb_resources`
--

CREATE TABLE IF NOT EXISTS `wb_resources` (
  `resource_id` int(11) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL DEFAULT '',
  PRIMARY KEY (`resource_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=latin1 AUTO_INCREMENT=15 ;

--
-- Dumping data for table `wb_resources`
--

INSERT INTO `wb_resources` (`resource_id`, `name`) VALUES
(1, 'Gold'),
(2, 'Food'),
(3, 'Ogre'),
(4, 'Dragon'),
(5, 'Human Bones'),
(6, 'Market'),
(7, 'Horses'),
(8, 'The Throne of Camelot'),
(9, 'The Grave of Arthur'),
(10, 'Excalibur'),
(11, 'Rickety Bridge'),
(12, 'Landslide'),
(13, 'Small Fish'),
(14, 'Bird');

-- --------------------------------------------------------

--
-- Table structure for table `wb_resources_lookup_table`
--

CREATE TABLE IF NOT EXISTS `wb_resources_lookup_table` (
  `area_id` int(11) NOT NULL DEFAULT '0',
  `resource_id` int(11) NOT NULL DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Dumping data for table `wb_resources_lookup_table`
--

INSERT INTO `wb_resources_lookup_table` (`area_id`, `resource_id`) VALUES
(11, 9),
(23, 8),
(11, 10),
(23, 6),
(23, 1),
(3, 4),
(9, 7),
(20, 7),
(13, 3),
(13, 2),
(11, 5),
(5, 12),
(12, 13),
(12, 14);
